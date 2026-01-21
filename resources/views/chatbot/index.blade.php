<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">HR Assistant</h1>
        <p class="mt-1 text-sm text-neutral-500">Ask me anything about HR policies, leave, or procedures</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Chat Area -->
        <div class="lg:col-span-3">
            <div class="card overflow-hidden flex flex-col" style="height: 600px;">
                <!-- Chat Header -->
                <div class="bg-neutral-50 px-6 py-4 border-b border-neutral-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold">AI HR Assistant</div>
                            <div class="text-xs text-neutral-500">Powered by Claude AI</div>
                        </div>
                    </div>
                </div>

                <!-- Messages Container -->
                <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4">
                    @forelse($messages as $msg)
                        <!-- User Message -->
                        <div class="flex justify-end">
                            <div class="bg-black text-white rounded-lg px-4 py-2 max-w-md">
                                <p class="text-sm">{{ $msg->message }}</p>
                                <span class="text-xs opacity-70">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                        </div>

                        <!-- AI Response -->
                        <div class="flex justify-start">
                            <div class="bg-neutral-100 rounded-lg px-4 py-2 max-w-md">
                                <p class="text-sm text-neutral-900">{{ $msg->response }}</p>
                                <span class="text-xs text-neutral-500">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-neutral-400 py-12">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-sm font-medium">No messages yet</p>
                            <p class="text-xs mt-1">Start a conversation with the AI assistant</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <div class="border-t border-neutral-200 p-4">
                    <form id="chat-form" class="flex gap-2">
                        @csrf
                        <input 
                            type="text" 
                            id="message-input"
                            name="message"
                            placeholder="Ask me anything about HR policies, leave, procedures..."
                            class="flex-1 px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                            required
                        >
                        <button 
                            type="submit"
                            id="send-button"
                            class="btn-primary px-6"
                        >
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold mb-4">Quick Questions</h3>
                <div class="space-y-2">
                    <button onclick="askQuestion('How many leave days do I have?')" class="w-full text-left text-sm text-neutral-600 hover:text-black transition p-2 hover:bg-neutral-50 rounded">
                        💼 Leave balance
                    </button>
                    <button onclick="askQuestion('What are the working hours?')" class="w-full text-left text-sm text-neutral-600 hover:text-black transition p-2 hover:bg-neutral-50 rounded">
                        ⏰ Working hours
                    </button>
                    <button onclick="askQuestion('How do I request leave?')" class="w-full text-left text-sm text-neutral-600 hover:text-black transition p-2 hover:bg-neutral-50 rounded">
                        📝 Request leave
                    </button>
                    <button onclick="askQuestion('What is the remote work policy?')" class="w-full text-left text-sm text-neutral-600 hover:text-black transition p-2 hover:bg-neutral-50 rounded">
                        🏠 Remote work
                    </button>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold mb-2">About AI Assistant</h3>
                <p class="text-xs text-neutral-600">
                    This AI assistant can help you with HR-related questions, company policies, and procedures. It's powered by Claude AI and available 24/7.
                </p>
            </div>
        </div>
    </div>

    <script>
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        const chatMessages = document.getElementById('chat-messages');

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            // Disable input
            messageInput.disabled = true;
            sendButton.disabled = true;
            sendButton.textContent = 'Sending...';

            // Add user message to UI
            addMessage(message, 'user');
            messageInput.value = '';

            try {
                const response = await fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                if (data.success) {
                    addMessage(data.response, 'ai', data.timestamp);
                } else {
                    addMessage('Sorry, I encountered an error. Please try again.', 'ai');
                }
            } catch (error) {
                addMessage('Sorry, I encountered an error. Please try again.', 'ai');
            } finally {
                messageInput.disabled = false;
                sendButton.disabled = false;
                sendButton.textContent = 'Send';
                messageInput.focus();
            }
        });

        function addMessage(text, type, timestamp = null) {
            const time = timestamp || new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
            
            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'user' ? 'flex justify-end' : 'flex justify-start';
            
            const bubbleClass = type === 'user' 
                ? 'bg-black text-white' 
                : 'bg-neutral-100 text-neutral-900';
            
            const timeClass = type === 'user' ? 'opacity-70' : 'text-neutral-500';
            
            messageDiv.innerHTML = `
                <div class="${bubbleClass} rounded-lg px-4 py-2 max-w-md">
                    <p class="text-sm">${text}</p>
                    <span class="text-xs ${timeClass}">${time}</span>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function askQuestion(question) {
            messageInput.value = question;
            messageInput.focus();
        }

        // Auto-scroll to bottom on load
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>
</x-app-layout>
