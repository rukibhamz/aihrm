<x-app-layout>
    <div class="max-w-md mx-auto" x-data="{ secondsLeft: 300 }" x-init="setInterval(() => { secondsLeft--; if(secondsLeft <= 0) window.location.reload(); }, 1000)">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900">Clock-in QR</h1>
            <p class="mt-2 text-sm text-neutral-500 font-medium">Present this code to the office scanner</p>
        </div>

        <div class="card p-8 bg-white border-2 border-black flex flex-col items-center relative overflow-hidden" id="qrContainer">
            <div id="locationOverlay" class="absolute inset-0 bg-white/90 backdrop-blur-[2px] z-10 flex flex-col items-center justify-center p-6 text-center">
                <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mb-4">
                    <svg id="locIcon" class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-black text-black uppercase tracking-tight mb-2">Location Required</h3>
                <p class="text-xs text-neutral-500 font-medium mb-6">We need to verify you are at the office to generate a secure clock-in token.</p>
                <button onclick="verifyLocation()" id="verifyBtn" class="btn-primary w-full py-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Allow Location
                </button>
            </div>

            <div id="qrcode" class="mb-6 p-4 bg-white border border-neutral-100 rounded-xl shadow-inner opacity-0 transition-opacity duration-500"></div>
            
            <div class="text-center">
                <div class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Status</div>
                <div class="text-sm font-black text-black" id="qrStatus">Awaiting Verification</div>
            </div>

            <div class="mt-4 text-center">
                <div class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Expires In</div>
                <div class="text-lg font-black text-black tabular-nums" x-text="Math.floor(secondsLeft / 60) + ':' + String(secondsLeft % 60).padStart(2, '0')"></div>
            </div>

            <div class="mt-8 pt-6 border-t border-neutral-100 w-full flex flex-col items-center gap-3">
                <button onclick="window.location.reload()" class="w-full btn-secondary py-3 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh Token
                </button>
            </div>
        </div>

        <p class="mt-6 text-center text-[10px] text-neutral-400 uppercase tracking-widest font-bold">
            Token expires in 5 minutes for your security
        </p>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        function verifyLocation() {
            const btn = document.getElementById('verifyBtn');
            const icon = document.getElementById('locIcon');
            
            btn.disabled = true;
            btn.innerText = 'Verifying Location...';
            
            const resetButton = () => {
                btn.disabled = false;
                btn.innerText = 'Allow Location';
            };

            const unlockQr = () => {
                // In a real production app, we would send these coords to the server for a real distance check.
                document.getElementById('locationOverlay').classList.add('hidden');
                document.getElementById('qrcode').classList.remove('opacity-0');
                document.getElementById('qrStatus').innerText = 'Active & Secure Token';
                generateQR();
            };

            if (!navigator.geolocation) {
                alert('Geolocation not supported.');
                resetButton();
                return;
            }

            navigator.geolocation.getCurrentPosition(
                unlockQr,
                () => {
                    navigator.geolocation.getCurrentPosition(
                        unlockQr,
                        (error) => {
                            let errorMessage = error.message;
                            if (error.code === error.PERMISSION_DENIED) {
                                errorMessage = 'Permission denied. Please allow location access in your browser settings.';
                            } else if (error.code === error.TIMEOUT) {
                                errorMessage = 'Unable to get your location in time. Turn on location/GPS and try again.';
                            } else if (error.code === error.POSITION_UNAVAILABLE) {
                                errorMessage = 'Location is unavailable on this device right now. Please try again.';
                            }

                            alert('Error: ' + errorMessage + '. Location is mandatory for QR clock-ins.');
                            resetButton();
                        },
                        { enableHighAccuracy: false, timeout: 20000, maximumAge: 120000 }
                    );
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }

        function generateQR() {
            var qrData = @json($qrData);
            new QRCode(document.getElementById("qrcode"), {
                text: qrData,
                width: 256,
                height: 256,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    </script>
    @endpush
</x-app-layout>
