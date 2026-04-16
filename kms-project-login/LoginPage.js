// src/pages/LoginPage.js
import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { toast } from 'react-toastify';
import { useAuth } from '../contexts/AuthContext';
import api from '../api/axios';

// ==================== REUSABLE COMPONENTS ====================

const EyeIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" fill="none"
       viewBox="0 0 24 24" strokeWidth={1.5}
       stroke="currentColor" className="w-5 h-5">
    <path strokeLinecap="round" strokeLinejoin="round"
      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 
      7.36 4.5 12 4.5c4.638 0 8.573 3.007 
      9.963 7.178.07.207.07.431 0 
      .639C20.577 16.49 16.64 19.5 
      12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
    <path strokeLinecap="round" strokeLinejoin="round"
      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
  </svg>
);

const EyeSlashIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" fill="none"
       viewBox="0 0 24 24" strokeWidth={1.5}
       stroke="currentColor" className="w-5 h-5">
    <path strokeLinecap="round" strokeLinejoin="round"
      d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
  </svg>
);

const LoadingSpinner = () => (
  <div className="flex items-center justify-center">
    <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
      xmlns="http://www.w3.org/2000/svg" fill="none"
      viewBox="0 0 24 24">
      <circle className="opacity-25" cx="12" cy="12"
        r="10" stroke="currentColor" strokeWidth="4" />
      <path className="opacity-75" fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373
        0 0 5.373 0 12h4zm2 5.291A7.962 
        7.962 0 014 12H0c0 3.042 1.135 
        5.824 3 7.938l3-2.647z" />
    </svg>
    Signing in...
  </div>
);

const ErrorAlert = ({ message }) => (
  <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
    <svg className="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
      <path
        fillRule="evenodd"
        d="M18 10a8 8 0 11-16 0 8 8
        0 0116 0zm-7-4a1 1 0 11-2 0 1 1
        0 012 0zM9 9a1 1 0 000 2v3a1 1
        0 001 1h1a1 1 0 100-2v-3a1 1
        0 00-1-1H9z"
        clipRule="evenodd"
      />
    </svg>
    <span className="text-sm">{message}</span>
  </div>
);

// ==================== SECTION COMPONENTS ====================

const BrandSection = ({ logoError, onLogoError }) => (
  <div className="lg:w-1/2 bg-gradient-to-br from-teal-600 to-cyan-700 flex items-center justify-center p-8 lg:p-12 relative">
    <div className="text-center text-white z-10">
      <div className="mb-8">
        {logoError ? (
          <div className="text-center">
            <h1 className="text-4xl lg:text-5xl font-bold tracking-tight mb-4">
              PLN
            </h1>
            <div className="w-24 h-1 bg-white/60 mx-auto rounded-full mb-4" />
            <h2 className="text-2xl lg:text-3xl font-semibold opacity-90">
              Suku Cadang
            </h2>
          </div>
        ) : (
          <div className="flex justify-center mb-6">
            <div className="w-32 h-32 lg:w-48 lg:h-48 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30 shadow-2xl p-4">
              <img
                src="/images/logo-plnsc.png"
                alt="PLN Suku Cadang"
                className="w-full h-full object-contain"
                onError={onLogoError}
              />
            </div>
          </div>
        )}
      </div>

      <p className="text-white/70 text-base lg:text-lg mt-6 max-w-md mx-auto leading-relaxed">
        Management System for efficient spare parts inventory and distribution
      </p>
    </div>
  </div>
);

const BackgroundEffects = () => (
  <div className="absolute inset-0 opacity-10">
    <div className="absolute top-1/4 left-1/4 w-64 h-64 bg-white rounded-full blur-3xl" />
    <div className="absolute bottom-1/4 right-1/4 w-80 h-80 bg-cyan-200 rounded-full blur-3xl" />
    <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-teal-300 rounded-full blur-3xl" />
  </div>
);

const LoginForm = ({
  formData,
  showPassword,
  rememberMe,
  isLoading,
  error,
  onInputChange,
  onTogglePassword,
  onToggleRemember,
  onSubmit,
  onForgotPassword,
}) => (
  <div className="lg:w-1/2 p-6 lg:p-8 xl:p-12 flex flex-col justify-center">
    <div className="text-center mb-6 lg:mb-8">
      <h1 className="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">
        Welcome Back
      </h1>
      <p className="text-gray-600 text-sm lg:text-base">
        Sign in to your account
      </p>
    </div>

    {error && <ErrorAlert message={error} />}

    <form onSubmit={onSubmit} className="space-y-4 lg:space-y-6">
      <div>
        <label
          htmlFor="email"
          className="block text-gray-700 text-sm font-semibold mb-2"
        >
          Email Address
        </label>
        <input
          type="email"
          id="email"
          name="email"
          value={formData.email}
          onChange={onInputChange}
          required
          className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-200 text-sm lg:text-base"
          placeholder="Enter your email"
          disabled={isLoading}
          autoComplete="email"
        />
      </div>

      <div>
        <label
          htmlFor="password"
          className="block text-gray-700 text-sm font-semibold mb-2"
        >
          Password
        </label>
        <div className="relative">
          <input
            type={showPassword ? 'text' : 'password'}
            id="password"
            name="password"
            value={formData.password}
            onChange={onInputChange}
            required
            className="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-200 text-sm lg:text-base"
            placeholder="Enter your password"
            disabled={isLoading}
            autoComplete="current-password"
          />
          <button
            type="button"
            onClick={onTogglePassword}
            className="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-teal-600 focus:outline-none transition duration-200"
            disabled={isLoading}
          >
            {showPassword ? <EyeSlashIcon /> : <EyeIcon />}
          </button>
        </div>
      </div>

      <div className="flex justify-between items-center">
        <div className="flex items-center">
          <input
            type="checkbox"
            id="remember"
            checked={rememberMe}
            onChange={onToggleRemember}
            className="w-4 h-4 text-teal-500 focus:ring-teal-500 rounded border-gray-300"
            disabled={isLoading}
          />
          <label
            htmlFor="remember"
            className="text-gray-600 text-sm ml-2 cursor-pointer select-none"
          >
            Remember Me
          </label>
        </div>

        <button
          type="button"
          onClick={onForgotPassword}
          className="text-teal-600 hover:text-teal-700 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 rounded px-2 py-1 transition duration-200 disabled:opacity-50"
          disabled={isLoading}
        >
          Forgot Password?
        </button>
      </div>

      <button
        type="submit"
        disabled={isLoading}
        className="w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg text-sm lg:text-base"
      >
        {isLoading ? <LoadingSpinner /> : 'Sign In'}
      </button>
    </form>

    <div className="mt-6 lg:mt-8 text-center text-sm text-gray-600">
      Don't have an account?{' '}
      <Link
        to="/register"
        className="font-medium text-teal-600 hover:text-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 rounded px-1 transition duration-200"
      >
        Register now
      </Link>
    </div>
  </div>
);

// ==================== MAIN COMPONENT ====================

const LoginPage = () => {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });
  const [showPassword, setShowPassword] = useState(false);
  const [rememberMe, setRememberMe] = useState(
    localStorage.getItem('rememberMe') === 'true'
  );
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');
  const [logoError, setLogoError] = useState(false);

  const navigate = useNavigate();
  const { login, user } = useAuth();

  useEffect(() => {
    if (user) {
      const redirectPath =
        user.role?.toLowerCase() === 'admin' ? '/admin' : '/repository';
      console.log('🔄 Already logged in, redirecting to:', redirectPath);
      navigate(redirectPath, { replace: true });
    }
  }, [user, navigate]);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
    if (error) setError('');
  };

  const validateForm = () => {
    if (!formData.email.trim() || !formData.password.trim()) {
      setError('Please fill in all fields');
      return false;
    }
    if (!/\S+@\S+\.\S+/.test(formData.email)) {
      setError('Please enter a valid email address');
      return false;
    }
    return true;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log('🔄 Login process started...');

    if (!validateForm()) return;

    setIsLoading(true);
    setError('');

    try {
      const payload = {
        email: formData.email.trim().toLowerCase(),
        password: formData.password.trim(),
      };

      console.log('📤 Sending login request...', {
        ...payload,
        password: '***',
      });

      const response = await api.post('/auth/login', payload);
      console.log('✅ Login response:', response.data);

      const userData = response.data?.data;
      const token = response.data?.token;

      if (!userData || !token) {
        throw new Error('Invalid response structure from server');
      }

      console.log('✅ Extracted user data:', userData);
      console.log('✅ Token received:', !!token);

      login(userData, token);

      if (rememberMe) {
        localStorage.setItem('rememberMe', 'true');
      } else {
        localStorage.removeItem('rememberMe');
      }

      const displayName = userData.name || userData.username || userData.email;
      toast.success(`Welcome back, ${displayName}!`);

      const redirectPath =
        userData.role?.toLowerCase() === 'admin' ? '/admin' : '/repository';

      console.log('🔄 Redirecting to:', redirectPath);
      navigate(redirectPath, { replace: true });
    } catch (err) {
      console.error('❌ Login failed:', err);
      console.error('❌ Error details:', err.response?.data);

      let errorMessage =
        err.response?.data?.message ||
        err.response?.data?.error ||
        err.message ||
        'Login failed. Please check your credentials and try again.';

      setError(errorMessage);
      toast.error(errorMessage);
    } finally {
      setIsLoading(false);
    }
  };

  const handleForgotPassword = () => {
    toast.info('Fitur lupa password akan segera tersedia. Silakan hubungi administrator.');
  };

  if (user) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-teal-500 via-teal-600 to-cyan-600 flex items-center justify-center p-4">
        <div className="text-center text-white">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4" />
          <p className="text-lg">Redirecting...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-teal-500 via-teal-600 to-cyan-600 flex items-center justify-center p-4">
      <BackgroundEffects />

      <div className="relative z-10 w-full max-w-6xl bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row min-h-[600px]">
        <BrandSection
          logoError={logoError}
          onLogoError={() => setLogoError(true)}
        />

        <LoginForm
          formData={formData}
          showPassword={showPassword}
          rememberMe={rememberMe}
          isLoading={isLoading}
          error={error}
          onInputChange={handleInputChange}
          onTogglePassword={() => setShowPassword((prev) => !prev)}
          onToggleRemember={(e) => setRememberMe(e.target.checked)}
          onSubmit={handleSubmit}
          onForgotPassword={handleForgotPassword}
        />
      </div>
    </div>
  );
};

export default LoginPage;
