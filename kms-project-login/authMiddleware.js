const jwt = require('jsonwebtoken');
const User = require('../models/userModel.js');

const protect = async (req, res, next) => {
    try {
        let token;

        if (req.headers.authorization && req.headers.authorization.startsWith('Bearer')) {
            token = req.headers.authorization.split(' ')[1];
            
            const decoded = jwt.verify(token, process.env.JWT_SECRET);
            
            req.user = await User.findById(decoded.id).select('-password');
            
            if (!req.user) {
                return res.status(401).json({ 
                    success: false,
                    message: 'Not authorized, user not found' 
                });
            }

            // ✅ FIXED: Hanya cek isApproved untuk operasi admin
            // Download boleh untuk semua user yang aktif
            if (!req.user.isActive) {
                return res.status(403).json({
                    success: false,
                    message: 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.'
                });
            }

            next();
        } else {
            return res.status(401).json({ 
                success: false,
                message: 'Not authorized, no token' 
            });
        }
    } catch (error) {
        console.error('Token error:', error);
        return res.status(401).json({ 
            success: false,
            message: 'Not authorized, token failed' 
        });
    }
};

const protectMedia = async (req, res, next) => {
    try {
        let token;

        if (req.headers.authorization && req.headers.authorization.startsWith('Bearer')) {
            token = req.headers.authorization.split(' ')[1];
        } else if (req.query && req.query.token) {
            token = req.query.token;
        }

        if (!token) {
            return res.status(401).json({
                success: false,
                message: 'Not authorized, no token'
            });
        }

        const decoded = jwt.verify(token, process.env.JWT_SECRET);
        req.user = await User.findById(decoded.id).select('-password');

        if (!req.user) {
            return res.status(401).json({
                success: false,
                message: 'Not authorized, user not found'
            });
        }

        if (!req.user.isActive) {
            return res.status(403).json({
                success: false,
                message: 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.'
            });
        }

        next();
    } catch (error) {
        console.error('Token error:', error);
        return res.status(401).json({
            success: false,
            message: 'Not authorized, token failed'
        });
    }
};

const admin = (req, res, next) => {
    if (req.user && req.user.role === 'admin') {
        next();
    } else {
        return res.status(403).json({ 
            success: false,
            message: 'Not authorized as an admin' 
        });
    }
};

// ✅ FIXED: Middleware khusus untuk upload/delete (perlu isApproved)
const protectWithApproval = async (req, res, next) => {
    try {
        let token;

        if (req.headers.authorization && req.headers.authorization.startsWith('Bearer')) {
            token = req.headers.authorization.split(' ')[1];
            
            const decoded = jwt.verify(token, process.env.JWT_SECRET);
            
            req.user = await User.findById(decoded.id).select('-password');
            
            if (!req.user) {
                return res.status(401).json({ 
                    success: false,
                    message: 'Not authorized, user not found' 
                });
            }

            if (!req.user.isActive) {
                return res.status(403).json({
                    success: false,
                    message: 'Akun Anda telah dinonaktifkan.'
                });
            }

            // Untuk user biasa, perlu approval untuk upload/delete
            if (req.user.role === 'user' && !req.user.isApproved) {
                return res.status(403).json({
                    success: false,
                    message: 'Akun Anda menunggu persetujuan admin. Silakan hubungi administrator.'
                });
            }

            next();
        } else {
            return res.status(401).json({ 
                success: false,
                message: 'Not authorized, no token' 
            });
        }
    } catch (error) {
        console.error('Token error:', error);
        return res.status(401).json({ 
            success: false,
            message: 'Not authorized, token failed' 
        });
    }
};

module.exports = { protect, protectMedia, admin, protectWithApproval };
