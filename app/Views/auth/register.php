<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-center min-h-[calc(100vh-10rem)] px-4">
    <div class="glass-panel w-full max-w-md p-8 rounded-2xl shadow-2xl relative overflow-hidden group">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full blur-2xl -mr-16 -mt-16 group-hover:bg-brand-500/20 transition-all duration-700"></div>

        <h2 class="text-3xl font-bold mb-2 text-center animate-fade-in"><span class="gradient-text">Create Account</span></h2>
        <p class="text-dark-muted text-center mb-8 text-sm animate-fade-in delay-100">Secure. Private. Encrypted.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-lg mb-6 text-sm">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="post" id="registerForm" class="space-y-5 animate-slide-up">
            <?= csrf_field() ?>
            
            <!-- Hidden inputs for Keys -->
            <textarea name="public_key" id="publicKey" class="hidden"></textarea>
            <input type="hidden" name="encrypted_private_key" id="encryptedPrivateKey">

            <div>
                <label for="username" class="block text-xs font-medium text-dark-muted uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" id="username" required 
                    class="input-field w-full px-4 py-3 rounded-lg text-sm bg-dark-input/50 focus:bg-dark-input" 
                    placeholder="Choose a unique username">
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-dark-muted uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="password" required 
                    class="input-field w-full px-4 py-3 rounded-lg text-sm bg-dark-input/50 focus:bg-dark-input" 
                    placeholder="Strong password required">
            </div>

            <div>
                <label for="confirm_password" class="block text-xs font-medium text-dark-muted uppercase tracking-wider mb-2">Confirm Password</label>
                <input type="password" id="confirm_password" required 
                    class="input-field w-full px-4 py-3 rounded-lg text-sm bg-dark-input/50 focus:bg-dark-input" 
                    placeholder="Repeat password">
            </div>

            <!-- Loading State for Key Generation -->
            <div id="keyGenStatus" class="hidden text-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500 mx-auto mb-2"></div>
                <p class="text-xs text-brand-400">Generating 2048-bit RSA Keypair...</p>
                <p class="text-[10px] text-dark-muted">This happens locally on your device.</p>
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-semibold py-3 px-4 rounded-lg shadow-lg shadow-brand-500/30 transition-all duration-300 transform hover:scale-[1.02]">
                Create Account
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-dark-muted">
            Already have an account? <a href="/login" class="text-brand-400 hover:text-brand-300 transition-colors">Log in</a>
        </p>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('submitBtn');
        const status = document.getElementById('keyGenStatus');
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const confirmAnd = document.getElementById('confirm_password').value;

        if(!username || !password || !confirmAnd) {
            Toast.error("Please fill in all fields");
            return;
        }

        if(password !== confirmAnd) {
            Toast.error("Passwords do not match!");
            return;
        }

        // Show loading state
        btn.classList.add('hidden');
        status.classList.remove('hidden');

        try {
            // 1. Generate RSA Keypair
            const keys = await SecureMsg.generateKeys();
            
            // 2. Encrypt Private Key with User's Password (AES)
            // Warning: In production, use a stronger KDF.
            const encryptedPrivKey = SecureMsg.encryptAES(keys.private, password);

            // 3. Store in Form
            document.getElementById('publicKey').value = keys.public;
            document.getElementById('encryptedPrivateKey').value = encryptedPrivKey;

            // 4. Also stash in LocalStorage for immediate session usage (optional now, but good for cache)
            localStorage.setItem('secure_msg_priv_key_' + username, encryptedPrivKey);
            
            // 5. Submit Form via Fetch to ensure localStorage is ready
            const formData = new FormData(document.getElementById('registerForm'));
            // Manually append keys since we set them in DOM elements but FormData might miss dynamic sets if not careful
            formData.set('public_key', keys.public);
            formData.set('encrypted_private_key', encryptedPrivKey);

            const response = await fetch("<?= base_url('register') ?>", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.redirected) {
                // Should redirect to login now
                window.location.href = response.url;
            } else {
                // If success, manual redirect
                window.location.href = "<?= base_url('login') ?>?registered=true";
            }
        } catch (err) {
            console.error(err);
            Toast.error("Registration failed: " + err.message);
            btn.classList.remove('hidden');
            status.classList.add('hidden');
        }
    });
</script>
<?= $this->endSection() ?>
