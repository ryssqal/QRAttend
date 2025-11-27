<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QRAttend - Registration</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ececec;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1000px;
            height: 550px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .left-section {
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .right-section {
            background-color: #2F44D6;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            border-radius: 0 20px 20px 0;
            position: relative;
        }
        .right-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 100px;
            height: 100px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
        }
        .right-section > * {
            position: relative;
            z-index: 1;
        }
        .form-container {
            width: 100%;
            max-width: 350px;
        }
        .title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .input-field {
            background-color: #f3f3f3;
            border-radius: 10px;
            padding: 15px 20px;
            border: none;
            outline: none;
            width: 100%;
            margin-bottom: 20px;
            position: relative;
            font-size: 1rem;
        }
        .input-field:focus {
            box-shadow: 0 0 0 2px rgba(47, 68, 214, 0.2);
        }
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #bfbfc3;
            width: 20px;
            height: 20px;
        }
        .btn-primary {
            background-color: #2F44D6;
            color: white;
            border-radius: 10px;
            padding: 15px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .btn-primary:hover {
            background-color: #253bb8;
            box-shadow: 0 4px 8px rgba(47, 68, 214, 0.3);
        }
        .social-text {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .social-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #ddd;
        }
        .social-facebook { background-color: #1877f2; }
        .social-github { background-color: #333; }
        .social-linkedin { background-color: #0077b5; }
        .social-twitter { background-color: #1da1f2; }
        .social-icon:hover {
            transform: scale(1.1);
        }
        .welcome-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .welcome-subtitle {
            font-size: 1.1rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        .btn-outline {
            background-color: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
        }
        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: white;
        }
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                height: auto;
                max-width: 500px;
            }
            .right-section {
                border-radius: 0 0 20px 20px;
                padding: 40px 20px;
            }
            .left-section {
                padding: 40px 20px;
            }
            .welcome-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section: Registration Form -->
        <div class="left-section">
            <div class="form-container">
                <h1 class="title">Registration</h1>
                <form method="POST" action="/register">
                    @csrf
                    <div class="relative">
                        <input type="text" name="name" placeholder="Username" class="input-field" value="{{ old('name') }}" required>
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                    @enderror
                    <div class="relative">
                        <input type="email" name="email" placeholder="Email" class="input-field" value="{{ old('email') }}" required>
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                    @enderror
                    <div class="relative">
                        <input type="password" name="password" placeholder="Password" class="input-field" required>
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                    @enderror
                    <div class="relative">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="input-field" required>
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="btn-primary">Register</button>
                </form>
                <p class="social-text">or register with social platforms</p>
                <div class="social-icons">
                    <div class="social-facebook social-icon">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </div>
                    <div class="social-github social-icon">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </div>
                    <div class="social-linkedin social-icon">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </div>
                    <div class="social-twitter social-icon">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section: Welcome Panel -->
        <div class="right-section">
            <h2 class="welcome-title">Welcome Back!</h2>
            <p class="welcome-subtitle">Already Have an Account?</p>
            <a href="/login" class="btn-outline" style="text-decoration: none; display: inline-block;">Login</a>
        </div>
    </div>
</body>
</html>
