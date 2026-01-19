<div x-data="{ open: false, minimized: false }" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="bg-white rounded-2xl shadow-2xl w-80 sm:w-96 mb-4 border border-neutral-200 overflow-hidden flex flex-col"
         style="height: 500px; max-height: 80vh;">
        
        <!-- Header -->
        <div class="bg-neutral-900 p-4 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-sm">AI Assistant</h3>
                    <p class="text-xs text-neutral-400">Ask me anything about HR</p>
                </div>
            </div>
            <button @click="open = false" class="text-neutral-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-neutral-50" id="chat-messages">
            <!-- Welcome Message -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-neutral-900 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-bold">AI</span>
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-neutral-100 text-sm text-neutral-700">
                    Hello! I'm your AI HR Assistant. How can I help you today?
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-neutral-100">
            <form @submit.prevent="sendMessage" x-data="{ 
                message: '', 
                loading: false,
                sendMessage() {
                    if (!this.message.trim()) return;
                    
                    const userMsg = this.message;
                    this.message = '';
                    this.loading = true;
                    
                    // Add user message to UI
                    const messagesDiv = document.getElementById('chat-messages');
                    const userDiv = document.createElement('div');
                    userDiv.className = 'flex items-start gap-3 justify-end';
                    userDiv.innerHTML = `
                        <div class='bg-neutral-900 text-white p-3 rounded-2xl rounded-tr-none shadow-sm text-sm'>${userMsg}</div>
                        <div class='w-8 h-8 bg-neutral-200 rounded-full flex items-center justify-center flex-shrink-0'>
                            <span class='text-neutral-600 text-xs font-bold'>Me</span>
                        </div>
                    `;
                    messagesDiv.appendChild(userDiv);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;

                    // Send to API
                    fetch('{{ route('chatbot.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: userMsg })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Add AI response
                        const aiDiv = document.createElement('div');
                        aiDiv.className = 'flex items-start gap-3';
                        aiDiv.innerHTML = `
                            <div class='w-8 h-8 bg-neutral-900 rounded-full flex items-center justify-center flex-shrink-0'>
                                <span class='text-white text-xs font-bold'>AI</span>
                            </div>
                            <div class='bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-neutral-100 text-sm text-neutral-700'>${data.response}</div>
                        `;
                        messagesDiv.appendChild(aiDiv);
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                }
            }">
                <div class="relative">
                    <input type="text" 
                           x-model="message" 
                           placeholder="Type your question..." 
                           class="w-full pl-4 pr-12 py-3 bg-neutral-50 border-none rounded-xl focus:ring-2 focus:ring-neutral-900 text-sm"
                           :disabled="loading">
                    
                    <button type="submit" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-neutral-900 text-white rounded-lg hover:bg-neutral-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="loading || !message.trim()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!loading">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" x-show="loading" style="display: none;">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button @click="open = !open" 
            class="group flex items-center justify-center w-14 h-14 bg-neutral-900 text-white rounded-full shadow-lg hover:bg-neutral-800 transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-4 focus:ring-neutral-300">
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </span>
        <svg class="w-6 h-6 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!open">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="open" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
</div>
