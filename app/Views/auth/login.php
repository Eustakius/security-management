<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-center min-h-[calc(100vh-10rem)] px-4">
    <div class="glass-panel w-full max-w-md p-8 rounded-2xl shadow-2xl relative overflow-hidden group">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl -ml-16 -mt-16 group-hover:bg-purple-500/20 transition-all duration-700"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full blur-2xl -mr-16 -mb-16 group-hover:bg-brand-500/20 transition-all duration-700"></div>

        <h2 class="text-3xl font-bold mb-2 text-center animate-fade-in"><span class="gradient-text">Welcome Back</span></h2>
        <p class="text-dark-muted text-center mb-8 text-sm animate-fade-in delay-100">Sign in to access your encrypted vault.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-lg mb-6 text-sm animate-shake">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-500/10 border border-green-500/20 text-green-500 px-4 py-3 rounded-lg mb-6 text-sm">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post" class="space-y-5 animate-slide-up" onsubmit="document.getElementById('loginBtn').disabled = true; document.getElementById('loginBtn').innerHTML = '<svg class=\'animate-spin h-5 w-5 mx-auto\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg>';">
            <?= csrf_field() ?>
            
            <div>
                <label for="username" class="block text-xs font-medium text-dark-muted uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" id="username" required 
                    class="input-field w-full px-4 py-3 rounded-lg text-sm bg-dark-input/50 focus:bg-dark-input transition-all focus:scale-[1.01]" 
                    placeholder="Enter your username">
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-dark-muted uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="password" required 
                    class="input-field w-full px-4 py-3 rounded-lg text-sm bg-dark-input/50 focus:bg-dark-input transition-all focus:scale-[1.01]" 
                    placeholder="Enter your password">
            </div>

            <button type="submit" id="loginBtn" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-semibold py-3 px-4 rounded-lg shadow-lg shadow-brand-500/30 transition-all duration-300 transform hover:scale-[1.02] active:scale-95">
                Sign In
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-dark-muted">
            Don't have an account? <a href="<?= base_url('register') ?>" class="text-brand-400 hover:text-brand-300 transition-colors underline-offset-4 hover:underline">Create one</a>
        </p>
    </div>
</div>
<?= $this->endSection() ?>
