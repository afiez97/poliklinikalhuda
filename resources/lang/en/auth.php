<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Custom auth messages
    'login_success' => 'Login successful! Welcome back.',
    'registration_success' => 'Registration successful! Welcome to our clinic.',
    'account_linked' => 'Your Google account has been linked successfully.',
    'google_login_failed' => 'Google login failed. Please try again.',
    'logout_success' => 'You have been logged out successfully.',
    'google_login' => 'Login with Google',
    'access_denied' => 'Access denied. Your account is not authorized to access this system.',

    'login' => [
        'title' => 'Login to Your Account',
        'email' => 'Email Address',
        'password' => 'Password',
        'remember' => 'Remember Me',
        'submit' => 'Login',
        'forgot_password' => 'Forgot Your Password?',
        'no_account' => "Don't have an account?",
        'register_link' => 'Register here',
    ],

    'admin' => [
        'welcome' => 'Welcome',
        'system_title' => 'Poliklinik Al-Huda Management System',
        'welcome_message' => 'Please login to your admin account to access the dashboard.',
        'login_title' => 'Admin Login',
        'login_subtitle' => 'Enter your login details',
        'username' => 'Username',
        'username_placeholder' => 'Enter username',
        'password' => 'Password',
        'password_placeholder' => 'Enter password',
        'remember_me' => 'Remember me',
        'login_button' => 'Login',
        'or' => 'or',
        'google_login' => 'Login with Google',
        'back_home' => 'Back to Home',
        'invalid_credentials' => 'Username or password is invalid.',
        'invalid_email' => 'The information provided does not match our records.',
    ],

    'register' => [
        'title' => 'Create New Account',
        'name' => 'Full Name',
        'email' => 'Email Address',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'submit' => 'Register',
        'have_account' => 'Already have an account?',
        'login_link' => 'Login here',
    ],

    'logout' => [
        'success' => 'You have been logged out successfully.',
    ],

];
