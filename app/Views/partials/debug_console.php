<!-- Global Debug Console -->
<div id="debugPanel" class="fixed bottom-0 left-0 right-0 z-[9999] transform transition-transform duration-300 translate-y-full hover:translate-y-0 peer-checked:translate-y-0 group">
    <!-- Handle/Toggle -->
    <div onclick="toggleDebug()" class="absolute -top-8 right-8 bg-black/80 text-brand-400 px-4 py-1 rounded-t-lg cursor-pointer text-xs font-mono border-t border-x border-brand-500/30 flex items-center gap-2 hover:bg-black">
        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
        DEBUG LOG
    </div>

    <div class="bg-black/90 backdrop-blur-md border-t border-brand-500/30 h-64 flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-4 py-2 border-b border-white/10 bg-white/5">
            <h3 class="text-xs font-bold text-brand-400 font-mono flex items-center gap-2">
                SECURE_MSG // TRANSPARENCY_MODE
            </h3>
            <div class="flex gap-4 text-[10px] text-dark-muted font-mono">
                <span>[KeyGen: Yellow]</span>
                <span>[Encrypt: Blue]</span>
                <span>[Decrypt: Green]</span>
                <span>[Error: Red]</span>
                <button onclick="clearLogs()" class="hover:text-white transition-colors">[CLEAR]</button>
            </div>
        </div>
        
        <div id="globalDebugConsole" class="flex-grow overflow-y-auto font-mono text-[10px] p-4 space-y-1 text-gray-400 select-text">
            <div class="text-brand-500/50">>> Logger initialized...</div>
        </div>
    </div>
</div>

<script>
    let isDebugOpen = false;

    function toggleDebug() {
        const panel = document.getElementById('debugPanel');
        isDebugOpen = !isDebugOpen;
        if(isDebugOpen) {
            panel.classList.remove('translate-y-full');
        } else {
            panel.classList.add('translate-y-full');
        }
    }

    // Capture Logs
    window.addEventListener('securemsg-log', (e) => {
        const consoleEl = document.getElementById('globalDebugConsole');
        const { timestamp, action, details } = e.detail;
        
        // Auto-open on error
        if(action === 'Error') {
            const panel = document.getElementById('debugPanel');
            if(panel.classList.contains('translate-y-full')) {
               toggleDebug();
            }
        }

        const line = document.createElement('div');
        line.className = 'border-l-2 border-brand-500/30 pl-2 hover:bg-brand-500/10 transition-colors py-0.5';
        
        let color = 'text-gray-300';
        if(action === 'Error') color = 'text-red-400 font-bold';
        if(action === 'EncryptAES' || action === 'EncryptRSA') color = 'text-blue-300';
        if(action === 'DecryptAES' || action === 'DecryptRSA') color = 'text-green-300';
        if(action === 'KeyGen') color = 'text-yellow-300';

        line.innerHTML = `<span class="text-gray-600">[${timestamp}]</span> <span class="${color}">${action}</span> <span class="text-gray-400">: ${details}</span>`;
        
        consoleEl.appendChild(line);
        consoleEl.scrollTop = consoleEl.scrollHeight;
    });

    function clearLogs() {
        document.getElementById('globalDebugConsole').innerHTML = '<div class="text-brand-500/50">>> Logger cleared</div>';
    }
</script>
