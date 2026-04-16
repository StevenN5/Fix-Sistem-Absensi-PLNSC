const asyncHandler = require('express-async-handler');
const crypto = require('crypto');
const User = require('../models/userModel.js');
const Division = require('../models/divisionModel');
const PASSWORD_REGEX = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

// ==================== USER PROFILE CONTROLLERS ====================

// @desc    Get user profile
// @route   GET /api/users/profile
// @access  Private
const getUserProfile = asyncHandler(async (req, res) => {
    const user = await User.findById(req.user._id)
        .select('-password')
        .populate('division', 'name isActive');
    
    if (user) {
        res.json({
            success: true,
            data: {
                _id: user._id,
                username: user.username,
                email: user.email,
                name: user.name,
                role: user.role,
                isActive: user.isActive,
                isApproved: user.isApproved,
                lastLogin: user.lastLogin,
                loginCount: user.loginCount,
                status: user.status, // Virtual field
                division: user.division || null
            }
        });
    } else {
        res.status(404);
        throw new Error('User not found');
    }
});

// @desc    Update user profile
// @route   PUT /api/users/profile
// @access  Private
const updateUserProfile = asyncHandler(async (req, res) => {
    const user = await User.findById(req.user._id);

    if (user) {
        const { username, email, name } = req.body;

        // Check duplicate username
        if (username && username !== user.username) {
            const userExists = await User.findOne({ username });
            if (userExists) {
                res.status(400);
                throw new Error('Username already taken');
            }
            user.username = username;
        }

        // Check duplicate email
        if (email && email !== user.email) {
            const emailExists = await User.findOne({ email });
            if (emailExists) {
                res.status(400);
                throw new Error('Email already in use');
            }
            user.email = email;
        }

        user.name = name || user.name;

        const updatedUser = await user.save(); //

        res.json({
            success: true,
            message: "Profile updated successfully",
            data: {
                _id: updatedUser._id,
                username: updatedUser.username,
                email: updatedUser.email,
                name: updatedUser.name,
                role: updatedUser.role,
                isActive: updatedUser.isActive,
                isApproved: updatedUser.isApproved
            }
        });

    } else {
        res.status(404);
        throw new Error('User not found');
    }
});

// @desc    Update user password (PERBAIKAN LOGIKA)
// @route   PUT /api/users/change-password
// @access  Private
const updateUserPassword = asyncHandler(async (req, res) => {
    const { currentPassword, newPassword } = req.body;

    if (!currentPassword || !newPassword) {
        res.status(400);
        throw new Error('Please provide current and new password');
    }

    if (!PASSWORD_REGEX.test(newPassword)) {
        res.status(400);
        throw new Error('New password must be at least 8 characters and include uppercase, lowercase, number, and symbol');
    }

    // Ambil user dan sertakan field password untuk komparasi
    const user = await User.findById(req.user._id).select('+password');

    // Verifikasi password lama menggunakan method matchPassword
    if (user && (await user.matchPassword(currentPassword))) {
        user.password = newPassword;
        await user.save(); // save() akan memicu pre-save hook hashing
        
        res.json({ 
            success: true,
            message: 'Password updated successfully' 
        });
    } else {
        res.status(401);
        throw new Error('Invalid current password');
    }
});

// ==================== ADMIN USER MANAGEMENT CONTROLLERS ====================

// @desc    Get all users (Admin only)
// @route   GET /api/users
// @access  Private/Admin
const getUsers = asyncHandler(async (req, res) => {
    const { page = 1, limit = 10, search = '', status = '', role = '' } = req.query;
    
    let filter = {};
    if (search) {
        filter.$or = [
            { name: { $regex: search, $options: 'i' } },
            { email: { $regex: search, $options: 'i' } },
            { username: { $regex: search, $options: 'i' } }
        ];
    }
    
    if (status === 'active') filter.isActive = true;
    else if (status === 'inactive') filter.isActive = false;
    else if (status === 'pending') { filter.isApproved = false; filter.role = 'user'; }
    
    if (role && ['admin', 'user'].includes(role)) filter.role = role;

    const users = await User.find(filter)
        .select('-password')
        .populate('division', 'name isActive')
        .sort({ createdAt: -1 })
        .limit(limit * 1)
        .skip((page - 1) * limit);

    const total = await User.countDocuments(filter);

    res.json({
        success: true,
        count: users.length,
        total,
        pages: Math.ceil(total / limit),
        currentPage: parseInt(page),
        data: users
    });
});

// @desc    Get pending users (Admin only)
const getPendingUsers = asyncHandler(async (req, res) => {
    const { page = 1, limit = 10 } = req.query;
    const pendingUsers = await User.find({ isApproved: false, role: 'user' })
        .select('-password').sort({ createdAt: -1 })
        .limit(limit * 1).skip((page - 1) * limit);

    const total = await User.countDocuments({ isApproved: false, role: 'user' });

    res.json({ success: true, count: pendingUsers.length, total, data: pendingUsers });
});

// @desc    Delete a user (Admin only)
const deleteUser = asyncHandler(async (req, res) => {
    const user = await User.findById(req.params.id);
    if (!user) { res.status(404); throw new Error('User not found'); }
    if (req.user._id.equals(user._id)) { res.status(400); throw new Error('Cannot delete your own admin account'); }
    await user.deleteOne();
    res.json({ success: true, message: 'User removed successfully' });
});

// @desc    Toggle user active status (Admin only)
const toggleUserStatus = asyncHandler(async (req, res) => {
    const user = await User.findById(req.params.id);
    if (!user) { res.status(404); throw new Error('User not found'); }
    if (req.user._id.equals(user._id)) { res.status(400); throw new Error('Cannot deactivate your own account'); }
    user.isActive = !user.isActive;
    const updatedUser = await user.save();
    res.json({ success: true, message: `User status updated`, data: updatedUser });
});

// @desc    Approve user account (Admin only)
const approveUser = asyncHandler(async (req, res) => {
    const user = await User.findById(req.params.id);
    if (!user) { res.status(404); throw new Error('User not found'); }
    if (user.isApproved) { res.status(400); throw new Error('User is already approved'); }
    user.isApproved = true;
    user.approvedAt = new Date();
    user.approvedBy = req.user._id;
    const updatedUser = await user.save();
    res.json({ success: true, message: 'User approved successfully', data: updatedUser });
});

// @desc    Toggle user approval status (Admin only)
const toggleUserApproval = asyncHandler(async (req, res) => {
    const user = await User.findById(req.params.id);
    if (!user) { res.status(404); throw new Error('User not found'); }
    if (req.user._id.equals(user._id) || user.role === 'admin') {
        res.status(400); throw new Error('Cannot change approval status for this account');
    }
    user.isApproved = !user.isApproved;
    if (user.isApproved) {
        user.approvedAt = new Date();
        user.approvedBy = req.user._id;
    } else {
        user.approvedAt = undefined;
        user.approvedBy = undefined;
    }
    const updatedUser = await user.save();
    res.json({ success: true, message: 'Approval toggled', data: updatedUser });
});

// @desc    Update user role (Admin only)
const updateUserRole = asyncHandler(async (req, res) => {
    const { role } = req.body;
    const user = await User.findById(req.params.id);
    if (!user) { res.status(404); throw new Error('User not found'); }
    if (!['admin', 'user'].includes(role)) { res.status(400); throw new Error('Invalid role'); }
    if (req.user._id.equals(user._id) && role !== 'admin') { res.status(400); throw new Error('Cannot remove own admin privileges'); }
    user.role = role;
    if (role === 'admin') user.isApproved = true;
    const updatedUser = await user.save();
    res.json({ success: true, message: 'Role updated successfully', data: updatedUser });
});

// @desc    Update user division (Admin only)
// @route   PUT /api/users/:id/division
// @access  Private/Admin
const updateUserDivision = asyncHandler(async (req, res) => {
    const { divisionId } = req.body;
    const user = await User.findById(req.params.id);

    if (!user) {
        res.status(404);
        throw new Error('User not found');
    }

    if (divisionId) {
        const division = await Division.findById(divisionId);
        if (!division || !division.isActive) {
            res.status(400);
            throw new Error('Division is not valid or inactive');
        }
        user.division = division._id;
    } else {
        user.division = null;
    }

    const updatedUser = await user.save();
    await updatedUser.populate('division', 'name isActive');

    res.json({
        success: true,
        message: 'User division updated',
        data: updatedUser
    });
});

const generateTempPassword = () => {
    const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const lower = 'abcdefghijklmnopqrstuvwxyz';
    const digits = '0123456789';
    const symbols = '!@#$%^&*';
    const all = upper + lower + digits + symbols;

    const pick = (set) => set[Math.floor(Math.random() * set.length)];
    const base = [pick(upper), pick(lower), pick(digits), pick(symbols)];

    while (base.length < 10) {
        base.push(pick(all));
    }

    for (let i = base.length - 1; i > 0; i -= 1) {
        const j = Math.floor(Math.random() * (i + 1));
        [base[i], base[j]] = [base[j], base[i]];
    }

    return base.join('');
};

// @desc    Reset user password (Admin only)
// @route   PUT /api/users/:id/reset-password
// @access  Private/Admin
const resetUserPassword = asyncHandler(async (req, res) => {
    const user = await User.findById(req.params.id).select('+password');

    if (!user) {
        res.status(404);
        throw new Error('User not found');
    }

    if (String(req.user._id) === String(user._id)) {
        res.status(400);
        throw new Error('Cannot reset your own password here');
    }

    if (user.role === 'admin') {
        res.status(400);
        throw new Error('Cannot reset admin password here');
    }

    const tempPassword = generateTempPassword();
    user.password = tempPassword;
    user.failedLoginAttempts = 0;
    user.lockUntil = null;
    await user.save();

    res.json({
        success: true,
        message: 'Password reset successful',
        tempPassword
    });
});

module.exports = {
    getUserProfile, updateUserProfile, updateUserPassword,
    getUsers, deleteUser, toggleUserStatus, approveUser,
    toggleUserApproval, getPendingUsers, updateUserRole, updateUserDivision,
    resetUserPassword
};
