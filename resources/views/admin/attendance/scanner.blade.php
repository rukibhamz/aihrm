<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900">Attendance Scanner</h1>
                <p class="mt-1 text-sm text-neutral-500 font-medium font-mono">Real-time gatekeeper mode</p>
            </div>
            <div class="text-right">
                <div id="scanner-status" class="px-3 py-1 bg-neutral-100 text-[10px] font-black uppercase tracking-widest rounded-full text-neutral-400">Waiting...</div>
            </div>
        </div>

        <div class="card overflow-hidden bg-black border-4 border-black">
            <div id="reader" style="width: 100%; min-height: 400px; background: #000;"></div>
        </div>

        <div id="scan-result" class="mt-8 hidden">
            <div class="card p-6 border-l-8 border-black shadow-lg">
                <div class="flex items-center gap-4">
                    <div id="result-icon" class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center text-2xl"></div>
                    <div>
                        <div id="result-title" class="text-xs font-black uppercase tracking-widest text-neutral-400 mb-1">Checking...</div>
                        <div id="result-message" class="text-lg font-black text-black"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4">
            <div class="card p-4 text-center">
                <div class="text-[10px] font-black uppercase tracking-widest text-neutral-400 mb-1">Today's Scans</div>
                <div id="scans-count" class="text-2xl font-black text-black">0</div>
            </div>
            <div class="card p-4 text-center">
                <div class="text-[10px] font-black uppercase tracking-widest text-neutral-400 mb-1">Last Scan</div>
                <div id="last-scan-time" class="text-lg font-black text-black">--:--</div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const scannerStatus = document.getElementById('scanner-status');
        const scanResult = document.getElementById('scan-result');
        const resultTitle = document.getElementById('result-title');
        const resultMessage = document.getElementById('result-message');
        const resultIcon = document.getElementById('result-icon');
        const scansCount = document.getElementById('scans-count');
        const lastScanTime = document.getElementById('last-scan-time');
        
        let counter = 0;

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Scan result: ${decodedText}`);
            
            // Pause scanner
            html5QrcodeScanner.pause();
            scannerStatus.innerText = "Processing...";
            scannerStatus.classList.add('bg-black', 'text-white');

            // Send to verify
            fetch("{{ route('attendance.verifyQr') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_data: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                showResult(data);
                // Resume after 2 seconds
                setTimeout(() => {
                    html5QrcodeScanner.resume();
                    scannerStatus.innerText = "Scanning...";
                    scannerStatus.classList.remove('bg-black', 'text-white');
                }, 3000);
            })
            .catch(err => {
                console.error(err);
                showResult({ success: false, message: "System Error" });
                setTimeout(() => html5QrcodeScanner.resume(), 3000);
            });
        }

        function showResult(data) {
            scanResult.classList.remove('hidden');
            resultMessage.innerText = data.message;
            
            if (data.success) {
                resultTitle.innerText = data.type === 'in' ? "Entry Authorized" : "Exit Logged";
                resultIcon.innerHTML = "✓";
                resultIcon.className = "w-12 h-12 bg-black text-white rounded-full flex items-center justify-center text-2xl";
                counter++;
                scansCount.innerText = counter;
                lastScanTime.innerText = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                
                // Trigger toast for secondary confirmation
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: data.message, type: 'success' }
                }));
            } else {
                resultTitle.innerText = "Access Denied";
                resultIcon.innerHTML = "✕";
                resultIcon.className = "w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-2xl";
                
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: data.message, type: 'error' }
                }));
            }
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>
    @endpush
</x-app-layout>
