<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('employee.peer-feedback.index') }}" class="text-neutral-500 hover:text-neutral-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Peer Feedback Details</h1>
            </div>
            <p class="text-sm text-neutral-500 ml-8">Review the feedback request and submit your response.</p>
        </div>
        <div class="flex gap-3">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $peerFeedbackRequest->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                Status: {{ ucfirst($peerFeedbackRequest->status) }}
            </span>
        </div>
    </div>

    <x-flash-messages
        successClass="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm"
        errorClass="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm"
    />
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Request Info sidebar -->
        <div class="lg:col-span-1">
            <div class="card p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4 border-b border-neutral-100 pb-2">Request Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block">Requested By</span>
                        <div class="mt-1 flex items-center">
                            <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs ring-2 ring-white">
                                {{ collect(explode(' ', $peerFeedbackRequest->requester->name))->map(fn($s) => substr($s,0,1))->take(2)->join('') }}
                            </div>
                            <span class="ml-3 font-medium text-neutral-900">{{ $peerFeedbackRequest->requester->name }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block">Reviewer</span>
                        <div class="mt-1 flex items-center">
                            <div class="h-8 w-8 rounded-full bg-neutral-100 text-neutral-700 flex items-center justify-center font-bold text-xs ring-2 ring-white">
                                {{ collect(explode(' ', $peerFeedbackRequest->targetUser->name))->map(fn($s) => substr($s,0,1))->take(2)->join('') }}
                            </div>
                            <span class="ml-3 font-medium text-neutral-900">{{ $peerFeedbackRequest->targetUser->name }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block">Date Requested</span>
                        <p class="mt-1 text-sm text-neutral-900">{{ $peerFeedbackRequest->created_at->format('M d, Y') }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block">Due Date</span>
                        <p class="mt-1 text-sm font-medium {{ $peerFeedbackRequest->due_date->isPast() && $peerFeedbackRequest->status !== 'completed' ? 'text-red-600' : 'text-neutral-900' }}">
                            {{ $peerFeedbackRequest->due_date->format('M d, Y') }}
                            @if($peerFeedbackRequest->due_date->isPast() && $peerFeedbackRequest->status !== 'completed')
                                (Overdue)
                            @endif
                        </p>
                    </div>

                    @if($peerFeedbackRequest->context)
                    <div class="pt-4 mt-4 border-t border-neutral-100">
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block mb-2">Focus Area / Context</span>
                        <div class="bg-blue-50/50 p-4 rounded-md border border-blue-100 text-sm italic text-neutral-700">
                            "{{ $peerFeedbackRequest->context }}"
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Feedback Form / Content area -->
        <div class="lg:col-span-2">
            @if($peerFeedbackRequest->status === 'pending')
                @if(auth()->id() === $peerFeedbackRequest->target_user_id)
                <div class="card p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-6 border-b border-neutral-100 pb-2">Provide Your Feedback</h3>
                    
                    <form action="{{ route('employee.peer-feedback.submit', $peerFeedbackRequest) }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Qualitative Feedback -->
                            <div>
                                <x-form.label for="strengths" value="Key Strengths" required />
                                <p class="text-xs text-neutral-500 mb-2">What does this person do really well? Give specific examples.</p>
                                <textarea id="strengths" name="strengths" rows="4" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required placeholder="Outline their main strengths based on your experience working with them...">{{ old('strengths') }}</textarea>
                                <x-form.error for="strengths" />
                            </div>

                            <div>
                                <x-form.label for="areas_for_improvement" value="Areas for Improvement" required />
                                <p class="text-xs text-neutral-500 mb-2">What is one thing this person could do differently to be more effective?</p>
                                <textarea id="areas_for_improvement" name="areas_for_improvement" rows="4" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required placeholder="Constructive feedback...">{{ old('areas_for_improvement') }}</textarea>
                                <x-form.error for="areas_for_improvement" />
                            </div>

                            <!-- Quantitative Feedback -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-neutral-100">
                                <div>
                                    <x-form.label for="collaboration_rating" value="Collaboration Rating (1-5)" required />
                                    <p class="text-xs text-neutral-500 mb-2">How well do they work in a team?</p>
                                    <select id="collaboration_rating" name="collaboration_rating" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                        <option value="" disabled selected>Select rating...</option>
                                        <option value="5" {{ old('collaboration_rating') == '5' ? 'selected' : '' }}>5 - Excellent team player, always supportive</option>
                                        <option value="4" {{ old('collaboration_rating') == '4' ? 'selected' : '' }}>4 - Very good, collaborates well</option>
                                        <option value="3" {{ old('collaboration_rating') == '3' ? 'selected' : '' }}>3 - Solid, reliable partner</option>
                                        <option value="2" {{ old('collaboration_rating') == '2' ? 'selected' : '' }}>2 - Needs improvement</option>
                                        <option value="1" {{ old('collaboration_rating') == '1' ? 'selected' : '' }}>1 - Difficult to work with</option>
                                    </select>
                                    <x-form.error for="collaboration_rating" />
                                </div>

                                <div>
                                    <x-form.label for="communication_rating" value="Communication Rating (1-5)" required />
                                    <p class="text-xs text-neutral-500 mb-2">How clearly and effectively do they communicate?</p>
                                    <select id="communication_rating" name="communication_rating" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                        <option value="" disabled selected>Select rating...</option>
                                        <option value="5" {{ old('communication_rating') == '5' ? 'selected' : '' }}>5 - Clear, proactive, and empathetic</option>
                                        <option value="4" {{ old('communication_rating') == '4' ? 'selected' : '' }}>4 - Good communicator</option>
                                        <option value="3" {{ old('communication_rating') == '3' ? 'selected' : '' }}>3 - Adequate</option>
                                        <option value="2" {{ old('communication_rating') == '2' ? 'selected' : '' }}>2 - Sometimes unclear or delayed</option>
                                        <option value="1" {{ old('communication_rating') == '1' ? 'selected' : '' }}>1 - Poor communication</option>
                                    </select>
                                    <x-form.error for="communication_rating" />
                                </div>
                            </div>

                            <div>
                                <x-form.label for="additional_comments" value="Additional Comments" />
                                <p class="text-xs text-neutral-500 mb-2">Anything else you'd like to share?</p>
                                <textarea id="additional_comments" name="additional_comments" rows="3" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Optional.">{{ old('additional_comments') }}</textarea>
                                <x-form.error for="additional_comments" />
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-neutral-100 flex justify-end">
                            <button type="submit" class="btn-primary items-center inline-flex">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="card p-12 flex flex-col items-center justify-center text-center">
                    <svg class="w-16 h-16 text-yellow-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-xl font-medium text-neutral-900">Waiting for Feedback</h3>
                    <p class="mt-2 text-neutral-500 text-sm max-w-sm">This request has been sent to {{ $peerFeedbackRequest->targetUser->name }}. You will be able to view the feedback once they respond.</p>
                </div>
                @endif
            @else
                <!-- Completed Review Display -->
                @php $response = $peerFeedbackRequest->responses->first(); @endphp
                @if($response)
                <div class="card p-6 divide-y divide-neutral-100">
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Feedback Submitted
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                            <div>
                                <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block mb-1">Collaboration</span>
                                <div class="flex items-center">
                                    <span class="font-bold text-lg text-neutral-900 mr-2">{{ $response->collaboration_rating }}/5</span>
                                    <div class="flex text-yellow-400">
                                        @for($i=0; $i<$response->collaboration_rating; $i++)
                                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block mb-1">Communication</span>
                                <div class="flex items-center">
                                    <span class="font-bold text-lg text-neutral-900 mr-2">{{ $response->communication_rating }}/5</span>
                                    <div class="flex text-yellow-400">
                                        @for($i=0; $i<$response->communication_rating; $i++)
                                            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-900 mb-2">Key Strengths</h4>
                                <div class="prose prose-sm text-neutral-700 max-w-none bg-white border border-neutral-100 p-4 rounded-md">
                                    {!! nl2br(e($response->strengths)) !!}
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-semibold text-neutral-900 mb-2">Areas for Improvement</h4>
                                <div class="prose prose-sm text-neutral-700 max-w-none bg-white border border-neutral-100 p-4 rounded-md">
                                    {!! nl2br(e($response->areas_for_improvement)) !!}
                                </div>
                            </div>

                            @if($response->additional_comments)
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-900 mb-2">Additional Comments</h4>
                                <div class="prose prose-sm text-neutral-700 max-w-none bg-white border border-neutral-100 p-4 rounded-md italic">
                                    {!! nl2br(e($response->additional_comments)) !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                    <div class="card p-12 text-center text-red-500">Error: Response data missing.</div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
