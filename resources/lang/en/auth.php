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
