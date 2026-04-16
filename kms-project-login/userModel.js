const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

const userSchema = mongoose.Schema(
    {
        username: {
            type: String,
            required: [true, 'Username is required'],
            unique: true,
            trim: true,
            minlength: [3, 'Username must be at least 3 characters'],
            maxlength: [30, 'Username cannot exceed 30 characters'],
            match: [/^[a-zA-Z0-9_]+$/, 'Username can only contain letters, numbers and underscores']
        },
        email: {
            type: String,
            required: [true, 'Email is required'],
            unique: true,
            trim: true,
            lowercase: true,
            match: [/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/, 'Please enter a valid email']
        },
        name: {
            type: String,
            trim: true,
            maxlength: [50, 'Name cannot exceed 50 characters']
        },
        password: {
            type: String,
            required: [true, 'Password is required'],
            minlength: [8, 'Password must be at least 8 characters'],
            validate: {
                validator: function (value) {
                    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(value);
                },
                message: 'Password must include uppercase, lowercase, number, and symbol'
            },
            select: false
        },
        role: {
            type: String,
            required: true,
            enum: ['user', 'admin'],
            default: 'user',
        },
        isActive: {
            type: Boolean,
            required: true,
            default: true,
        },
        isApproved: {
            type: Boolean,
            required: true,
            default: function() {
                return this.role === 'admin';
            }
        },
        approvedAt: {
            type: Date,
            default: function() {
                return this.role === 'admin' ? new Date() : undefined;
            }
        },
        approvedBy: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'User',
            default: function() {
                return this.role === 'admin' ? this._id : undefined;
            }
        },
        lastLogin: {
            type: Date
        },
        loginCount: {
            type: Number,
            default: 0
        },
        failedLoginAttempts: {
            type: Number,
            default: 0
        },
        lockUntil: {
            type: Date,
            default: null
        },
        avatar: {
            type: String,
            default: ''
        },
        division: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'Division',
            default: null
        }
    },
    {
        timestamps: true,
    }
);

// ==================== INDEXES ====================
userSchema.index({ role: 1 });
userSchema.index({ isActive: 1 });
userSchema.index({ isApproved: 1 });
userSchema.index({ createdAt: -1 });
userSchema.index({ role: 1, isActive: 1 });
userSchema.index({ isApproved: 1, role: 1 });
userSchema.index({ division: 1 });

// ==================== MIDDLEWARE ====================

// Hash password before saving - FIXED VERSION
userSchema.pre('save', async function (next) {
    // Only run this function if password was actually modified
    if (!this.isModified('password')) return next();

    try {
        // Validate password exists
        if (!this.password) {
            return next(new Error('Password is required'));
        }

        // Hash the password with cost of 12
        const salt = await bcrypt.genSalt(12);
        this.password = await bcrypt.hash(this.password, salt);
        next();
    } catch (error) {
        next(error);
    }
});

// Auto-approve admin users on save
userSchema.pre('save', function (next) {
    if (this.role === 'admin' && !this.isApproved) {
        this.isApproved = true;
        this.approvedAt = this.approvedAt || new Date();
        this.approvedBy = this.approvedBy || this._id;
    }
    next();
});

// ==================== INSTANCE METHODS ====================

// Compare password method - FIXED VERSION
userSchema.methods.matchPassword = async function (enteredPassword) {
    try {
        // Validate inputs
        if (!enteredPassword || typeof enteredPassword !== 'string') {
            throw new Error('Invalid password provided for comparison');
        }

        if (!this.password) {
            throw new Error('No stored password found for user');
        }

        return await bcrypt.compare(enteredPassword, this.password);
    } catch (error) {
        console.error('Password comparison error:', error);
        throw new Error('Password comparison failed');
    }
};

// Update login info method
userSchema.methods.updateLoginInfo = async function () {
    this.lastLogin = new Date();
    this.loginCount += 1;
    return await this.save();
};

// Approve user method
userSchema.methods.approve = async function(adminId) {
    this.isApproved = true;
    this.approvedAt = new Date();
    this.approvedBy = adminId;
    return await this.save();
};

// Deactivate user method
userSchema.methods.deactivate = async function() {
    this.isActive = false;
    return await this.save();
};

// Activate user method
userSchema.methods.activate = async function() {
    this.isActive = true;
    return await this.save();
};

// ==================== STATIC METHODS ====================

// Get pending users
userSchema.statics.getPendingUsers = function() {
    return this.find({ 
        isApproved: false,
        role: 'user'
    }).select('-password');
};

// Get active users
userSchema.statics.getActiveUsers = function() {
    return this.find({ 
        isActive: true,
        isApproved: true 
    }).select('-password');
};

// Get users by status
userSchema.statics.getUsersByStatus = function(status) {
    const filters = {
        'active': { isActive: true, isApproved: true },
        'inactive': { isActive: false },
        'pending': { isApproved: false, role: 'user' },
        'admin': { role: 'admin' },
        'user': { role: 'user' }
    };
    
    return this.find(filters[status] || {}).select('-password');
};

// Search users
userSchema.statics.searchUsers = function(searchTerm) {
    return this.find({
        $or: [
            { name: { $regex: searchTerm, $options: 'i' } },
            { email: { $regex: searchTerm, $options: 'i' } },
            { username: { $regex: searchTerm, $options: 'i' } }
        ]
    }).select('-password');
};

// ==================== VIRTUAL FIELDS ====================

userSchema.virtual('status').get(function() {
    if (!this.isActive) return 'inactive';
    if (!this.isApproved && this.role === 'user') return 'pending';
    return 'active';
});

userSchema.virtual('displayName').get(function() {
    return this.name || this.username;
});

userSchema.virtual('isAdmin').get(function() {
    return this.role === 'admin';
});

userSchema.virtual('accountAge').get(function() {
    return Math.floor((Date.now() - this.createdAt) / (1000 * 60 * 60 * 24));
});

// ==================== TOJSON TRANSFORM ====================

userSchema.set('toJSON', {
    virtuals: true,
    transform: function(doc, ret) {
        delete ret.password;
        ret.displayName = doc.displayName;
        ret.isAdmin = doc.isAdmin;
        ret.status = doc.status;
        ret.accountAge = doc.accountAge;
        return ret;
    }
});

const User = mongoose.model('User', userSchema);

module.exports = User;



