<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - SecureMsg</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        },
                        dark: {
                            bg: '#0f172a',
                            card: '#1e293b',
                            input: '#334155',
                            text: '#f8fafc',
                            muted: '#94a3b8'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                        'shake': 'shake 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        shake: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '10%, 30%, 50%, 70%, 90%': { transform: 'translateX(-4px)' },
                            '20%, 40%, 60%, 80%': { transform: 'translateX(4px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0f172a; /* dark.bg */
            color: #f8fafc; /* dark.text */
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .gradient-text {
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .input-field {
            background: #334155;
            border: 1px solid #475569;
            color: white;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2);
            outline: none;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
    <!-- ReactBits Aesthetics: Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Crypto Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/3.3.2/jsencrypt.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/forge/1.3.1/forge.min.js"></script>
</head>
<body class="antialiased min-h-screen flex flex-col relative selection:bg-brand-500 selection:text-white">

    <!-- Background Decoration -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-brand-500/20 rounded-full blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-500/20 rounded-full blur-[120px] animate-pulse-slow delay-1000"></div>
    </div>

    <!-- Navbar -->
    <nav class="glass-panel sticky top-0 z-50 border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= base_url() ?>" class="text-xl font-bold tracking-tight">
                        <span class="gradient-text">SecureMsg</span>
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <?php if (session()->get('user_id')): ?>
                            <a href="<?= base_url('dashboard') ?>" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Inbox</a>
                            <a href="<?= base_url('logout') ?>" onclick="localStorage.removeItem('secure_msg_priv_key_<?= session()->get('username') ?>')" class="text-gray-400 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Logout</a>
                            <button onclick="resetApp()" class="group relative bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 px-4 py-2 rounded-md text-sm font-medium transition-all flex items-center gap-2 hover:shadow-[0_0_15px_rgba(239,68,68,0.3)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Reset Identity</span>
                                <div class="absolute top-full right-0 mt-2 w-48 p-2 bg-black/90 text-xs text-center rounded border border-white/10 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
                                    DANGER: Wipes all keys. Old messages will be unreadable.
                                </div>
                            </button>
                        <?php else: ?>
                            <a href="<?= base_url('login') ?>" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Login</a>
                            <a href="<?= base_url('register') ?>" class="bg-brand-600 hover:bg-brand-500 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-lg shadow-brand-500/20">Get Started</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative z-0">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/5 py-8 mt-12 bg-dark-bg/50">
        <div class="max-w-7xl mx-auto px-4 text-center text-dark-muted text-sm">
            <p>&copy; <?= date('Y') ?> SecureMsg. End-to-End Encrypted.</p>
        </div>
    </footer>

    <?= $this->include('partials/debug_console') ?>
    
    <script src="<?= base_url('js/toast.js') ?>"></script>
    <script src="<?= base_url('js/crypto-app.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
    <script>
        function resetApp() {
            if(confirm('Reset App? This will clear all keys and logout.')) {
                localStorage.clear();
                window.location.href = "<?= base_url('logout') ?>";
            }
        }
    </script>
</body>
</html>
