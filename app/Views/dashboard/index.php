<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-panel p-6 rounded-2xl shadow-xl animate-fade-in relative min-h-[700px] flex flex-col">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Encrypted Vault</h1>
                <p class="text-dark-muted text-sm mt-1 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-brand-400 font-medium"><?= session()->get('username') ?></span>
                    <span class="text-dark-input">|</span>
                    <input type="hidden" id="checkKeyInput">
                </p>
            </div>
            <button onclick="openComposeModal()" class="bg-brand-600 hover:bg-brand-500 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all shadow-lg shadow-brand-500/20 flex items-center gap-2 transform hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Message
            </button>
        </div>

        <!-- Labs / Tabs -->
        <div class="flex space-x-1 bg-dark-card/50 p-1 rounded-xl mb-6 w-fit border border-white/5">
            <button onclick="switchTab('inbox')" id="tab-inbox" class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-white bg-white/10 shadow-sm">
                Inbox
            </button>
            <button onclick="switchTab('sent')" id="tab-sent" class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-dark-muted hover:text-white hover:bg-white/5">
                Sent
            </button>
        </div>

        <!-- Messages List Container -->
        <div id="messagesContainer" class="flex-grow space-y-4 relative">
             <!-- Skeleton Loader -->
            <div id="skeletonLoader" class="animate-pulse space-y-4 hidden">
                <?php for($i=0; $i<3; $i++): ?>
                <div class="h-24 bg-white/5 rounded-xl"></div>
                <?php endfor; ?>
            </div>
            
            <div id="messagesList" class="space-y-4"></div>
        </div>

        <!-- Footer Polling Status -->
        <div class="mt-4 pt-4 border-t border-white/5 flex justify-between items-center text-[10px] text-dark-muted uppercase tracking-wider">
            <span id="lastUpdated">Last updated: Just now</span>
            <span class="flex items-center gap-1"><svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Auto-syncing</span>
        </div>
    </div>

    <!-- Debug Console moved to Global Partial -->
</div>

<!-- Compose Modal -->
<div id="composeModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity opacity-0" id="composeModalBg" onclick="closeComposeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="glass-panel w-full max-w-lg rounded-2xl shadow-2xl transform transition-all scale-95 opacity-0 p-6" id="composeModalContent">
            <h3 class="text-xl font-bold mb-4">New Encrypted Message</h3>
            
            <div class="space-y-4">
                <div class="relative">
                    <label class="block text-xs font-medium text-dark-muted uppercase mb-1">Recipient</label>
                    <input type="text" id="recipientSearch" 
                        class="input-field w-full px-4 py-3 rounded-lg text-sm bg-dark-input/50 focus:bg-dark-input transition-all focus:scale-[1.01]" 
                        placeholder="Search username (min 2 chars)..." autocomplete="off">
                    <div id="searchResults" class="absolute left-0 right-0 mt-1 bg-dark-card border border-white/10 rounded-lg shadow-xl hidden z-50 max-h-40 overflow-y-auto backdrop-blur-xl"></div>
                    <input type="hidden" id="selectedRecipientId">
                    <input type="hidden" id="selectedRecipientKey">
                </div>

                <div>
                    <label class="block text-xs font-medium text-dark-muted uppercase mb-1">Message</label>
                    <textarea id="messageBody" rows="5" 
                        class="input-field w-full px-4 py-3 rounded-lg text-sm resize-none bg-dark-input/50 focus:bg-dark-input transition-all focus:scale-[1.01]" 
                        placeholder="Type your secure message..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
                    <button onclick="closeComposeModal()" class="px-4 py-2 rounded-lg text-sm text-dark-muted hover:text-white transition-colors hover:bg-white/5">Cancel</button>
                    <button id="sendBtn" onclick="sendMessage()" class="bg-brand-600 hover:bg-brand-500 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all shadow-lg shadow-brand-500/20 disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-brand-500/40 active:scale-95">
                        Encrypt & Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Unlock Modal -->
<div id="keyModal" class="fixed inset-0 z-[200] hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="glass-panel w-full max-w-sm rounded-2xl p-8 text-center border-red-500/30 shadow-red-500/20 shadow-2xl">
            <div class="w-12 h-12 bg-red-500/10 text-red-400 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Unlock Vault</h3>
            <p class="text-dark-muted text-sm mb-6">Enter your password to decrypt your Private Key.</p>
            <input type="password" id="unlockPassword" class="input-field w-full px-4 py-2 rounded-lg text-sm mb-4" placeholder="Password" onkeyup="if(event.key === 'Enter') unlockPrivateKey()">
            <button onclick="unlockPrivateKey()" class="w-full bg-brand-600 hover:bg-brand-500 text-white px-4 py-2 rounded-lg text-sm font-medium mb-4">Unlock</button>
            <div class="text-xs text-dark-muted">
                <a href="/logout" onclick="localStorage.removeItem('secure_msg_priv_key_' + USERNAME)" class="underline hover:text-white">Forgot Password? (Reset)</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const USERNAME = "<?= session()->get('username') ?>";
    const BASE_URL = "<?= rtrim(base_url(), '/') ?>";
    
    // Safely pass keys using JSON to avoid HTML escaping corrupting Base64 (e.g. +, /, =)
    const MY_PUBLIC_KEY = <?= json_encode(session()->get('public_key')) ?>;
    const SERVER_ENCRYPTED_KEY = <?= json_encode(session()->get('encrypted_private_key')) ?>;
    
    let PRIVATE_KEY = null;
    let CURRENT_TAB = 'inbox';
    let POLL_INTERVAL = null;

    // --- Core & Auth ---
    async function init() {
        const localEncryptedKey = localStorage.getItem('secure_msg_priv_key_' + USERNAME);

        console.log("Init Debug:", {
            serverKeyLen: SERVER_ENCRYPTED_KEY ? SERVER_ENCRYPTED_KEY.length : 0,
            localKeyLen: localEncryptedKey ? localEncryptedKey.length : 0
        });

        // SELF-HEALING: If server has a key but local doesn't (or they differ), trust the server.
        // This fixes "Split Brain" and allows login from new devices.
        if (SERVER_ENCRYPTED_KEY && (!localEncryptedKey || localEncryptedKey !== SERVER_ENCRYPTED_KEY)) {
            console.warn("Syncing Key from Server to LocalStorage...");
            localStorage.setItem('secure_msg_priv_key_' + USERNAME, SERVER_ENCRYPTED_KEY);
            Toast.success("Identity synced from server.");
        }

        const finalKey = localStorage.getItem('secure_msg_priv_key_' + USERNAME);
        if (!finalKey) {
            Toast.error("Private Key not found! Please Logout & Reset.");
            return;
        }
        document.getElementById('keyModal').classList.remove('hidden');
    }

    async function unlockPrivateKey() {
        const pwd = document.getElementById('unlockPassword').value;
        const encryptedKey = localStorage.getItem('secure_msg_priv_key_' + USERNAME);
        
        try {
            const decryptedKey = SecureMsg.decryptAES(encryptedKey, pwd);
            // Allow both "BEGIN RSA PRIVATE KEY" (Legacy) and "BEGIN PRIVATE KEY" (Modern/Native)
            if(!decryptedKey || !decryptedKey.includes('PRIVATE KEY')) throw new Error("Invalid password or Key Format");
            
            PRIVATE_KEY = decryptedKey;

            // --- KEY INTEGRITY CHECK ---
            // Verify that this Private Key actually matches the Public Key we told the server
            // If they don't match, encryption will ALWAYS fail.
            const derivedPublic = new JSEncrypt();
            derivedPublic.setPrivateKey(PRIVATE_KEY);
            const pubKeyString = derivedPublic.getPublicKey();
            
            // Normalize for comparison (strip newlines/spaces)
            const norm1 = pubKeyString.replace(/\s+/g, '');
            const norm2 = MY_PUBLIC_KEY.replace(/\s+/g, '');

            // Note: JSEncrypt format might slightly differ from direct DB dump depending on generation,
            // but usually they should match if generated by same lib. 
            // If completely different (different modulus), then we have a split-brain.
            // Let's do a basic length/content check.
            
            if (norm1 !== norm2) {
               console.warn("Key Mismatch Detected!");
               console.warn("Local:", norm1.substring(0, 50));
               console.warn("Server:", norm2.substring(0, 50));
               
               // Auto-fix: This user identity is broken.
               // Wipe local key and force logout/reset.
               Toast.error("Security Mismatch Detected. Resetting session...");
               
               // WIPE EVERYTHING
               localStorage.removeItem('secure_msg_priv_key_' + USERNAME);
               
               setTimeout(() => {
                   window.location.href = BASE_URL + '/logout';
               }, 1500);
               return;
            }
            // ---------------------------
            
            // Success! Hide modal immediately. 
            // We use style.display = 'none' to be 100% sure, then classList
            const modal = document.getElementById('keyModal');
            modal.style.display = 'none'; 
            modal.classList.add('hidden');

            Toast.success("Vault Unlocked");
            startPolling();
        } catch (e) {
            console.error(e);
            let msg = e.message || "Incorrect password.";
            if(msg.includes('Malformed UTF-8')) msg = "Incorrect Password!"; // Technical error -> User error
            
            Toast.error(msg);
            document.getElementById('unlockPassword').classList.add('border-red-500');
        }
    }

    // --- Tabs & Polling ---
    function switchTab(tab) {
        CURRENT_TAB = tab;
        // UI Toggle
        const inboxBtn = document.getElementById('tab-inbox');
        const sentBtn = document.getElementById('tab-sent');
        
        if (tab === 'inbox') {
            inboxBtn.className = "px-4 py-2 rounded-lg text-sm font-medium transition-all text-white bg-white/10 shadow-sm";
            sentBtn.className = "px-4 py-2 rounded-lg text-sm font-medium transition-all text-dark-muted hover:text-white hover:bg-white/5";
        } else {
            sentBtn.className = "px-4 py-2 rounded-lg text-sm font-medium transition-all text-white bg-white/10 shadow-sm";
            inboxBtn.className = "px-4 py-2 rounded-lg text-sm font-medium transition-all text-dark-muted hover:text-white hover:bg-white/5";
        }

        loadMessages();
    }

    function startPolling() {
        loadMessages();
        POLL_INTERVAL = setInterval(() => {
            loadMessages(true); // silent update
        }, 10000); // 10 sec
    }

    // --- Message Loading ---
    let LAST_MESSAGES_HASH = '';

    async function loadMessages(silent = false) {
        const list = document.getElementById('messagesList');
        const loader = document.getElementById('skeletonLoader');
        
        if(!silent) {
            // Only show loader if we genuinely don't have content yet
            if(list.children.length === 0) {
                loader.classList.remove('hidden');
            }
        }

        try {
            const endpoint = CURRENT_TAB === 'inbox' ? `${BASE_URL}/api/messages` : `${BASE_URL}/api/messages/sent`;
            const res = await fetch(endpoint);
            window.lastFetchResponse = res.clone(); 
            const messages = await res.json();
            
            if(!silent) loader.classList.add('hidden');
            
            document.getElementById('lastUpdated').innerText = 'Last updated: ' + new Date().toLocaleTimeString();

            // OPTIMIZATION: Check if data changed
            const currentHash = JSON.stringify(messages.map(m => m.id + m.created_at)); // Simple hash based on IDs and timestamps
            if (silent && currentHash === LAST_MESSAGES_HASH) {
                // No changes, skip DOM update to preserve open states/scroll
                return; 
            }
            LAST_MESSAGES_HASH = currentHash;

            if (messages.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-12 animate-fade-in">
                        <div class="w-16 h-16 bg-dark-input rounded-full flex items-center justify-center mx-auto mb-4 text-dark-muted">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-1">No messages</h3>
                        <p class="text-dark-muted text-sm">Your ${CURRENT_TAB} is empty.</p>
                    </div>`;
                return;
            }

            let html = '';
            for (const msg of messages) {
                const isSentBox = (CURRENT_TAB === 'sent');
                const targetEncryptedKey = isSentBox ? msg.my_encrypted_aes_key : msg.encrypted_aes_key;
                const otherPartyName = isSentBox ? `To: ${msg.receiver_name}` : `From: ${msg.sender_name}`;
                
                try {
                    if(!targetEncryptedKey) throw new Error("Key data missing (Old message?)");
                    
                    const aesKey = SecureMsg.decryptRSA(targetEncryptedKey, PRIVATE_KEY);
                    if(!aesKey) throw new Error("Key Mismatch (Reset happened?)");

                    const plainText = SecureMsg.decryptAES(msg.encrypted_content, aesKey);

                    let verified = false;
                    if (!isSentBox && msg.sender_public_key) {
                            verified = SecureMsg.verifySignature(plainText, msg.signature, msg.sender_public_key);
                    } else {
                        verified = true; 
                    }

                    html += buildMessageCard(msg, plainText, otherPartyName, verified);

                } catch (err) {
                    console.error("Msg Error", err);
                    // UX Improvement: Show specific error hint
                    const errorMsg = err.message.includes("Key Mismatch") ? "Decryption Failed (Key Mismatch)" : "Decryption Failed";
                    html += buildMessageCard(msg, `• ${errorMsg} •`, otherPartyName, false, true);
                }
            }
            
            // Re-render
            // Ideally we would diff the DOM, but replacing innerHTML is "fast enough" for < 100 items if we gate it with the Hash check.
            // But to be even nicer, we could try to preserve open 'raw' states if we really wanted to, 
            // but the Hash check solves 99% of the 'random closing' annoyance because it won't update if nothing changed.
            list.innerHTML = html;

        } catch (e) {
            console.error(e);
            if(window.lastFetchResponse) {
                const text = await window.lastFetchResponse.text();
                console.error("Server Body:", text);
            }
            if(!silent) Toast.error("Sync failed");
        }
    }

    function buildMessageCard(msg, content, title, verified, error = false) {
        const statusColor = verified ? 'text-green-400 border-green-500/20 bg-green-500/5' : (error ? 'text-red-400 border-red-500/20 bg-red-500/5' : 'text-yellow-400 border-yellow-500/20');
        const icon = verified ? 
            `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Verified` : 
            (error ? `Decryption Failed` : `Unverified`);

        const rawId = 'raw_' + msg.id;
        const plainId = 'plain_' + msg.id;

        return `
        <div class="glass-panel p-4 rounded-xl border border-white/5 hover:border-brand-500/30 hover:shadow-lg hover:shadow-brand-500/10 transition-all duration-300 animate-slide-up group relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-brand-500/0 via-brand-500/50 to-brand-500/0 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex justify-between items-start mb-2 pl-2">
                <div>
                    <h4 class="font-bold text-sm text-white group-hover:text-brand-300 transition-colors">${title}</h4>
                    <p class="text-[10px] text-dark-muted font-mono uppercase tracking-wider">${msg.created_at}</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="toggleRaw('${msg.id}')" class="text-[10px] px-2 py-1 rounded-full border border-white/10 text-dark-muted hover:text-brand-400 hover:border-brand-500/30 transition-all opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0">
                        &lt;/&gt; Raw
                    </button>
                    <span class="text-[10px] px-2 py-1 rounded-full border flex items-center gap-1 ${statusColor} shadow-sm">
                        ${icon}
                    </span>
                </div>
            </div>
            <div class="prose prose-invert prose-sm max-w-none pl-2">
                <p id="${plainId}" class="text-gray-300 leading-relaxed font-light group-hover:text-gray-200 transition-colors">${content}</p>
                <div id="${rawId}" class="hidden space-y-2 relative">
                    <div class="relative group/code">
                        <p class="text-[10px] font-mono text-brand-400 break-all bg-black/30 p-2 rounded border border-white/5 cursor-pointer hover:bg-black/50 transition-colors" title="Click to copy" onclick="navigator.clipboard.writeText(this.innerText); Toast.success('Copied AES Ciphertext')">AES: ${msg.encrypted_content}</p>
                    </div>
                    <div class="relative group/code">
                        <p class="text-[10px] font-mono text-yellow-500 break-all bg-black/30 p-2 rounded border border-white/5 cursor-pointer hover:bg-black/50 transition-colors" title="Click to copy" onclick="navigator.clipboard.writeText('${msg.signature}'); Toast.success('Copied Signature')">Sig: ${msg.signature.substring(0, 50)}...</p>
                    </div>
                    <p class="text-[9px] text-dark-muted text-center italic">* Click code to copy</p>
                </div>
            </div>
        </div>
        `;
    }

    function toggleRaw(id) {
        const raw = document.getElementById('raw_' + id);
        const plain = document.getElementById('plain_' + id);
        if(raw.classList.contains('hidden')) {
            raw.classList.remove('hidden');
            plain.classList.add('hidden');
        } else {
            raw.classList.add('hidden');
            plain.classList.remove('hidden');
        }
    }

    // --- Sending ---
    // Search logic same as before...
    const searchInput = document.getElementById('recipientSearch');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;

    // Global cache for search results
    let SEARCH_RESULTS_CACHE = [];

    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        if(query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(async () => {
            const res = await fetch(`${BASE_URL}/api/users/search?username=${query}`);
            const users = await res.json();
            
            // Store in cache
            SEARCH_RESULTS_CACHE = users;

            if(users.length > 0) {
                searchResults.innerHTML = users.map(u => `
                    <div onclick="selectRecipient(${u.id})" class="px-4 py-2 hover:bg-white/5 cursor-pointer text-sm transition-colors border-b border-white/5 last:border-0">
                        <span class="font-bold text-white">${u.username}</span>
                    </div>
                `).join('');
                searchResults.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
            }
        }, 300);
    });

    function selectRecipient(id) {
        const user = SEARCH_RESULTS_CACHE.find(u => u.id == id);
        if(!user) return;

        document.getElementById('recipientSearch').value = user.username;
        document.getElementById('selectedRecipientId').value = user.id;
        document.getElementById('selectedRecipientKey').value = user.public_key; 
        searchResults.classList.add('hidden');
    }

    async function sendMessage() {
        const btn = document.getElementById('sendBtn');
        const receiverId = document.getElementById('selectedRecipientId').value;
        const receiverPubKey = document.getElementById('selectedRecipientKey').value;
        const content = document.getElementById('messageBody').value;

        if(!receiverId || !content) {
            Toast.info("Please select a recipient and write a message.");
            return;
        }

        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Encrypting...`;

        try {
            // 1. Generate random AES key
            const aesKey = SecureMsg.generateRandomKey();

            // 2. Encrypt Content
            const encryptedContent = SecureMsg.encryptAES(content, aesKey);

            // 3. Encrypt AES Key for RECEIVER
            const encryptedAesKey = SecureMsg.encryptRSA(aesKey, receiverPubKey);

            // 4. Encrypt AES Key for SENDER (Self) - NEW!
            if(!MY_PUBLIC_KEY) throw new Error("My Public Key missing from session. Re-login required.");
            const encryptedAesKeySender = SecureMsg.encryptRSA(aesKey, MY_PUBLIC_KEY);

            // 5. Sign Message
            const signature = SecureMsg.signMessage(content, PRIVATE_KEY);

            // 6. Send
            const formData = new FormData();
            formData.append('receiver_id', receiverId);
            formData.append('encrypted_content', encryptedContent);
            formData.append('encrypted_aes_key', encryptedAesKey);
            formData.append('encrypted_aes_key_sender', encryptedAesKeySender); // New field
            formData.append('iv', 'default'); 
            formData.append('signature', signature);

            const res = await fetch(`${BASE_URL}/api/messages/send`, { method: 'POST', body: formData });

            if(res.ok) {
                closeComposeModal();
                Toast.success("Message sent securely!");
                
                // Switch to Sent tab to show it
                if(CURRENT_TAB !== 'sent') switchTab('sent');
                else loadMessages();

                // Clear
                document.getElementById('messageBody').value = '';
                document.getElementById('recipientSearch').value = '';
            } else {
                Toast.error("Failed to send message.");
            }

        } catch (e) {
            console.error(e);
            Toast.error("Encryption failed: " + e.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = "Encrypt & Send";
        }
    }

    // Modal UI
    function openComposeModal() {
        const modal = document.getElementById('composeModal');
        const bg = document.getElementById('composeModalBg');
        const content = document.getElementById('composeModalContent');
        
        modal.classList.remove('hidden');
        // Small delay for transition
        requestAnimationFrame(() => {
            bg.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('scale-100');
        });
    }

    function closeComposeModal() {
        const modal = document.getElementById('composeModal');
        const bg = document.getElementById('composeModalBg');
        const content = document.getElementById('composeModalContent');

        bg.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('opacity-0', 'scale-95');

        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    // Init
    init();

</script>
<?= $this->endSection() ?>
