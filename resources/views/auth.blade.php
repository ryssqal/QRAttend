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
                    <input type="hidden" name="role" value="client">
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
