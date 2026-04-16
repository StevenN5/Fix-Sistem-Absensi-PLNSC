const express = require('express');
const router = express.Router();
const {
    getUserProfile,
    updateUserProfile,
    updateUserPassword,
    getUsers,
    deleteUser,
    toggleUserStatus,
    approveUser,
    toggleUserApproval,
    getPendingUsers,
    updateUserRole,
    updateUserDivision,
    resetUserPassword
} = require('../controllers/userController');
const { protect, admin } = require('../middleware/authMiddleware');

// ==================== USER PROFILE ROUTES ====================
router.get('/profile', protect, getUserProfile);
router.put('/profile', protect, updateUserProfile);
router.put('/change-password', protect, updateUserPassword);

// ==================== ADMIN USER MANAGEMENT ROUTES ====================
router.get('/', protect, admin, getUsers);
router.get('/pending', protect, admin, getPendingUsers);
router.delete('/:id', protect, admin, deleteUser);
router.put('/:id/toggle-status', protect, admin, toggleUserStatus);
router.put('/:id/approve', protect, admin, approveUser);
router.put('/:id/approval', protect, admin, toggleUserApproval);
router.put('/:id/role', protect, admin, updateUserRole);
router.put('/:id/division', protect, admin, updateUserDivision);
router.put('/:id/reset-password', protect, admin, resetUserPassword);

// Get specific user by ID (Admin only)
router.get('/:id', protect, admin, async (req, res) => {
    try {
        const User = require('../models/userModel');
        const user = await User.findById(req.params.id)
            .select('-password')
            .populate('division', 'name isActive');
        
        if (!user) {
            return res.status(404).json({
                success: false,
                message: 'User not found'
            });
        }

        res.json({
            success: true,
            data: user
        });
    } catch (error) {
        console.error('Error fetching user:', error);
        res.status(500).json({
            success: false,
            message: 'Failed to fetch user data'
        });
    }
});

module.exports = router;
