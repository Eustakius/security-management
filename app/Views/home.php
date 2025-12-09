<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Welcome<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="relative overflow-hidden">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 text-center lg:pt-32">
        <h1 class="mx-auto max-w-4xl font-display text-5xl font-medium tracking-tight text-slate-900 sm:text-7xl">
            <span class="inline-block gradient-text font-bold animate-fade-in">End-to-End Encryption</span>
            <span class="inline-block text-white animate-fade-in delay-100">made simple.</span>
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg tracking-tight text-dark-muted animate-fade-in delay-200">
            SecureMsg guarantees that your conversations stay private. 
            Client-side RSA encryption ensures we never see your data.
        </p>
        <div class="mt-10 flex justify-center gap-x-6 animate-slide-up delay-300">
            <?php if (session()->get('user_id')): ?>
                <a href="/dashboard" class="bg-brand-600 hover:bg-brand-500 text-white px-8 py-3 rounded-full font-semibold transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40">
                    Go to Dashboard
                </a>
            <?php else: ?>
                <a href="/register" class="bg-brand-600 hover:bg-brand-500 text-white px-8 py-3 rounded-full font-semibold transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40">
                    Get Started Free
                </a>
                <a href="/login" class="text-white hover:text-brand-400 px-8 py-3 rounded-full font-semibold transition-colors border border-white/10 hover:border-brand-500/30">
                    Sign In
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Features -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div class="glass-panel p-8 rounded-2xl hover:border-brand-500/30 transition-colors cursor-default group">
                <div class="w-12 h-12 bg-brand-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Client-Side Encryption</h3>
                <p class="text-dark-muted text-sm leading-relaxed">
                    Your messages are encrypted on your device before they ever reach our servers. We couldn't read them even if we wanted to.
                </p>
            </div>
            
            <div class="glass-panel p-8 rounded-2xl hover:border-purple-500/30 transition-colors cursor-default group">
                <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Digital Signatures</h3>
                <p class="text-dark-muted text-sm leading-relaxed">
                    Every message is cryptographically signed, guaranteeing that it came from the sender and hasn't been tampered with.
                </p>
            </div>

            <div class="glass-panel p-8 rounded-2xl hover:border-green-500/30 transition-colors cursor-default group">
                <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Blazing Fast</h3>
                <p class="text-dark-muted text-sm leading-relaxed">
                    Built with CodeIgniter 4 and optimized vanilla JS, ensuring a lightweight and responsive experience.
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
