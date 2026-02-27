<x-app-layout>
    <div class="max-w-md mx-auto" x-data="{ secondsLeft: 300 }" x-init="setInterval(() => { secondsLeft--; if(secondsLeft <= 0) window.location.reload(); }, 1000)">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black uppercase tracking-tight text-neutral-900">Clock-in QR</h1>
            <p class="mt-2 text-sm text-neutral-500 font-medium">Present this code to the office scanner</p>
        </div>

        <div class="card p-8 bg-white border-2 border-black flex flex-col items-center">
            <div id="qrcode" class="mb-6 p-4 bg-white border border-neutral-100 rounded-xl shadow-inner"></div>
            
            <div class="text-center">
                <div class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Status</div>
                <div class="text-sm font-black text-black">Active & Secure Token</div>
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
        document.addEventListener('DOMContentLoaded', function() {
            var qrData = @json($qrData);
            new QRCode(document.getElementById("qrcode"), {
                text: qrData,
                width: 256,
                height: 256,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    </script>
    @endpush
</x-app-layout>
