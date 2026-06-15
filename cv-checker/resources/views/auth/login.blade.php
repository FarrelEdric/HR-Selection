<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR CV Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full">
        <!-- Logo Area -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-brand rounded-3xl shadow-xl shadow-brand/20 mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray-500">Sign in to access your HR Dashboard</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none transition-all @error('email') border-red-500 @enderror" placeholder="admin@gmail.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Password</label>
                    <input type="password" name="password" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none transition-all" placeholder="••••••••">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-5 bg-brand text-white text-lg font-bold rounded-2xl shadow-xl shadow-brand/30 hover:bg-brand-600 transition-all">
                        Sign In
                    </button>
                </div>

                <div class="bg-blue-50 p-4 rounded-2xl mt-6">
                    <p class="text-xs text-blue-600 font-medium text-center">
                        <span class="font-bold">Pro Tip:</span> Use email <strong>admin@gmail.com</strong> and password <strong>admin</strong> to access.
                    </p>
                </div>
            </form>
        </div>

        <p class="text-center mt-10 text-gray-400 text-sm">
            &copy; {{ date('Y') }} HR Hub CV Checker. All rights reserved.
        </p>
    </div>
</body>
</html>
