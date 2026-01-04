<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIMKAS</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />

    <!-- Iconify -->
    <script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3.0.0/dist/iconify.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Decorations -->
    <div
        class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary-500/20 to-cyan-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
    </div>
    <div
        class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-br from-cyan-500/15 to-primary-500/15 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md relative z-10">
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Header -->
            <div class="text-center mb-8">
                <div
                    class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-gradient-to-br from-primary-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-primary-500/30">
                    <span class="iconify w-10 h-10 text-white" data-icon="heroicons:banknotes"></span>
                </div>
                <h1 class="text-2xl font-bold text-gradient mb-1">SIMKAS</h1>
                <p class="text-slate-500 text-sm">Sistem Manajemen Uang Kas</p>
            </div>

            <!-- Error Alert -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                    <span class="iconify w-5 h-5 text-red-500 shrink-0 mt-0.5"
                        data-icon="heroicons:exclamation-circle"></span>
                    <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="form-label">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <span class="iconify w-5 h-5" data-icon="heroicons:envelope"></span>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input pl-12"
                            placeholder="nama@email.com" required autofocus>
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="form-label">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <span class="iconify w-5 h-5" data-icon="heroicons:lock-closed"></span>
                        </span>
                        <input type="password" id="password" name="password" class="form-input pl-12"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full py-3">
                    <span class="iconify w-5 h-5" data-icon="heroicons:arrow-right-on-rectangle"></span>
                    Masuk
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center mt-6 text-slate-400 text-sm">
            © {{ date('Y') }} SIMKAS. All rights reserved.
        </p>
    </div>
</body>

</html>