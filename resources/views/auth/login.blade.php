<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Vehicle Inspection System</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; }

        .panel-bg {
            background: linear-gradient(145deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
        }

        /* animated floating circles */
        .blob {
            position: absolute;
            border-radius: 50%;
            opacity: 0.12;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 { width: 320px; height: 320px; background: #3b82f6; top: -60px; left: -80px; animation-delay: 0s; }
        .blob-2 { width: 240px; height: 240px; background: #60a5fa; bottom: 40px; right: -60px; animation-delay: 2.5s; }
        .blob-3 { width: 160px; height: 160px; background: #93c5fd; top: 45%; left: 55%; animation-delay: 5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-24px) scale(1.04); }
        }

        /* input focus ring */
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.20);
        }

        /* submit button shimmer */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            transition: all .2s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,.45);
        }
        .btn-primary:active { transform: translateY(0); }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- ── Left decorative panel ── -->
    <div class="hidden lg:flex lg:w-1/2 panel-bg relative overflow-hidden flex-col items-center justify-center p-12 text-white">
        <!-- background blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="relative z-10 text-center max-w-sm">
            <img src="{{ asset('logo.png') }}" alt="VIS Logo" class="w-24 h-24 object-contain mx-auto mb-8 drop-shadow-xl">
            <h1 class="text-4xl font-bold leading-tight mb-4">Vehicle Inspection System</h1>
            <p class="text-blue-200 text-base leading-relaxed mb-10">
                Streamlined inspections, real-time compliance tracking, and comprehensive reporting for fleet management.
            </p>

            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <i class="fas fa-clipboard-check text-2xl text-blue-300 mb-2 block"></i>
                    <div class="text-xs text-blue-200 font-medium">Fast Inspections</div>
                </div>
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <i class="fas fa-chart-line text-2xl text-green-300 mb-2 block"></i>
                    <div class="text-xs text-blue-200 font-medium">Live Analytics</div>
                </div>
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <i class="fas fa-shield-alt text-2xl text-yellow-300 mb-2 block"></i>
                    <div class="text-xs text-blue-200 font-medium">Compliance</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Right sign-in panel ── -->
    <div class="flex-1 flex items-center justify-center bg-gray-50 px-6 py-12">
        <div class="w-full max-w-md">

            <!-- Mobile logo (visible only on small screens) -->
            <div class="flex flex-col items-center mb-8 lg:hidden">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-16 h-16 object-contain mb-3">
                <h2 class="text-2xl font-bold text-gray-800">Vehicle Inspection System</h2>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">

                <div class="mb-8 lg:block hidden">
                    <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                    <p class="text-gray-500 text-sm mt-1">Sign in to your account to continue</p>
                </div>

                @if($errors->any())
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                    <i class="fas fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm">
                    <i class="fas fa-circle-check mt-0.5 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email address
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400 text-sm"></i>
                            </span>
                            <input id="email" name="email" type="email" required autocomplete="email"
                                   value="{{ old('email') }}"
                                   placeholder="you@example.com"
                                   class="form-input w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-900 bg-gray-50 transition">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 text-sm"></i>
                            </span>
                            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                   placeholder="••••••••"
                                   class="form-input w-full pl-10 pr-11 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-900 bg-gray-50 transition">
                            <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fas text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" id="remember"
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="btn-primary w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-white text-sm font-semibold shadow-md">
                        <i class="fas fa-right-to-bracket"></i>
                        Sign In
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Vehicle Inspection System &mdash; Nigeria
            </p>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
</body>
</html>
