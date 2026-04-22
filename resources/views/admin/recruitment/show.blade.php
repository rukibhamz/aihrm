<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.applications.kanban') }}" class="text-sm text-neutral-500 hover:text-black transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Recruitment Board
                </a>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">{{ $application->candidate_name }}</h1>
            <p class="text-neutral-500">Applying for: <span class="font-semibold text-neutral-800">{{ $application->jobPosting->title }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 text-sm font-bold rounded-full uppercase tracking-wide
                {{ $application->status === 'hired' ? 'bg-green-100 text-green-800' : 
                   ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                {{ $application->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Details -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Contact Information
                </h2>
                <div class="space-y-4">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Email Address</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->candidate_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Phone Number</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->candidate_phone ?? 'N/A' }}</dd>
                    </div>
                    @if($application->linkedin_url)
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">LinkedIn</dt>
                        <dd><a href="{{ $application->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all font-medium">{{ $application->linkedin_url }}</a></dd>
                    </div>
                    @endif
                    @if($application->portfolio_url)
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Portfolio</dt>
                        <dd><a href="{{ $application->portfolio_url }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all font-medium">{{ $application->portfolio_url }}</a></dd>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Application Summary
                </h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Experience</dt>
                            <dd class="text-neutral-900 font-medium">{{ $application->years_of_experience ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Current City</dt>
                            <dd class="text-neutral-900 font-medium">{{ $application->current_city ?? 'N/A' }}</dd>
                        </div>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Current Role</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->current_job_title ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Expected Salary</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->expected_salary ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Notice Period</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->notice_period ?? 'N/A' }}</dd>
                    </div>
                </div>
            </div>

            <div class="bg-black text-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4">Pipeline Status</h2>
                <form action="{{ route('admin.applications.status', $application) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <p class="text-xs text-neutral-400">Move candidate to the next stage in the recruitment process.</p>
                    <div class="space-y-2">
                        @foreach(['applied', 'screening', 'interview', 'offer', 'hired', 'rejected'] as $status)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-neutral-800 hover:bg-primary cursor-pointer transition {{ $application->status === $status ? 'bg-primary border-neutral-700' : '' }}">
                            <input type="radio" name="status" value="{{ $status }}" {{ $application->status === $status ? 'checked' : '' }} class="text-white focus:ring-offset-black">
                            <span class="text-sm font-medium capitalize">{{ $status }}</span>
                        </label>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full bg-white text-black font-bold py-3 rounded-lg hover:bg-neutral-200 transition">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Right Column: Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Interview Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="bg-neutral-50 px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-neutral-900 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Interview Schedule
                    </h2>
                    <button onclick="document.getElementById('scheduleInterviewModal').showModal()" class="btn-primary text-xs px-3 py-1.5">+ Schedule Interview</button>
                </div>
                <div class="p-6">
                    @if($application->interviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($application->interviews->sortBy('scheduled_at') as $interview)
                            <div class="relative border border-neutral-200 rounded-lg p-5 {{ $interview->status === 'completed' ? 'bg-green-50/50' : ($interview->status === 'cancelled' ? 'bg-neutral-50 opacity-60' : 'bg-white') }}">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider
                                                {{ $interview->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $interview->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $interview->status === 'cancelled' ? 'bg-neutral-200 text-neutral-600' : '' }}
                                                {{ $interview->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ $interview->status }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                                                Round {{ $interview->round }} — {{ ucfirst(str_replace('_', ' ', $interview->type)) }}
                                            </span>
                                        </div>
                                        <h4 class="font-bold text-neutral-900">{{ $interview->scheduled_at->format('l, M d, Y \\a\\t h:i A') }}</h4>
                                        <p class="text-sm text-neutral-500 mt-1">
                                            Interviewer: <span class="font-medium text-neutral-700">{{ $interview->interviewer->name }}</span>
                                        </p>
                                        @if($interview->location_or_link)
                                            <p class="text-sm text-neutral-500 mt-1">
                                                📍 @if(filter_var($interview->location_or_link, FILTER_VALIDATE_URL))
                                                    <a href="{{ $interview->location_or_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $interview->location_or_link }}</a>
                                                @else
                                                    {{ $interview->location_or_link }}
                                                @endif
                                            </p>
                                        @endif
                                        @if($interview->notes)
                                            <p class="text-sm text-neutral-500 mt-2 italic">"{{ $interview->notes }}"</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($interview->status === 'scheduled')
                                        <form action="{{ route('admin.interviews.update', $interview) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-xs px-3 py-1.5 font-semibold bg-green-600 text-white rounded hover:bg-green-700 transition">Mark Done</button>
                                        </form>
                                        <form action="{{ route('admin.interviews.update', $interview) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="text-xs px-3 py-1.5 font-semibold bg-neutral-200 text-neutral-700 rounded hover:bg-neutral-300 transition">Cancel</button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.interviews.destroy', $interview) }}" method="POST" onsubmit="return confirm('Delete this interview?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-neutral-400 hover:text-red-600 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Scorecard Section -->
                                @if($interview->scorecard)
                                    <div class="mt-4 pt-4 border-t border-neutral-100">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs font-bold uppercase tracking-widest text-neutral-400">Scorecard</span>
                                            <span class="text-sm font-bold {{ $interview->scorecard->total_score >= 70 ? 'text-green-600' : ($interview->scorecard->total_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $interview->scorecard->total_score }}/100
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-3">
                                            @foreach($interview->scorecard->criteria_scores as $criteria => $score)
                                            <div class="text-center">
                                                <div class="text-lg font-black {{ $score >= 4 ? 'text-green-600' : ($score >= 3 ? 'text-yellow-600' : 'text-red-600') }}">{{ $score }}/5</div>
                                                <div class="text-[10px] font-medium text-neutral-500 uppercase tracking-wide">{{ str_replace('_', ' ', $criteria) }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold px-2 py-1 rounded-full
                                                {{ $interview->scorecard->recommendation === 'strong_hire' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $interview->scorecard->recommendation === 'hire' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                {{ $interview->scorecard->recommendation === 'no_hire' ? 'bg-orange-100 text-orange-800' : '' }}
                                                {{ $interview->scorecard->recommendation === 'strong_no_hire' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst(str_replace('_', ' ', $interview->scorecard->recommendation)) }}
                                            </span>
                                        </div>
                                        @if($interview->scorecard->strengths)
                                            <p class="text-sm text-neutral-600 mt-2"><strong>Strengths:</strong> {{ $interview->scorecard->strengths }}</p>
                                        @endif
                                        @if($interview->scorecard->weaknesses)
                                            <p class="text-sm text-neutral-600 mt-1"><strong>Areas for Improvement:</strong> {{ $interview->scorecard->weaknesses }}</p>
                                        @endif
                                    </div>
                                @elseif($interview->status === 'completed')
                                    <!-- Scorecard Form for completed interviews -->
                                    <div class="mt-4 pt-4 border-t border-neutral-200">
                                        <button onclick="document.getElementById('scorecardForm{{ $interview->id }}').classList.toggle('hidden')" class="text-sm font-semibold text-primary hover:text-indigo-800 transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                            Submit Scorecard
                                        </button>
                                        <form id="scorecardForm{{ $interview->id }}" action="{{ route('admin.interviews.scorecard', $interview) }}" method="POST" class="hidden mt-4 space-y-4 p-4 bg-indigo-50/50 rounded-lg border border-indigo-100">
                                            @csrf
                                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                                @foreach(['communication', 'technical_skills', 'problem_solving', 'cultural_fit', 'leadership'] as $criteria)
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1">{{ str_replace('_', ' ', $criteria) }}</label>
                                                    <select name="{{ $criteria }}" required class="w-full px-2 py-1.5 text-sm border border-neutral-200 rounded focus:ring-primary focus:border-primary">
                                                        <option value="">-</option>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-neutral-600 mb-1">Recommendation</label>
                                                <select name="recommendation" required class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-primary focus:border-primary">
                                                    <option value="">Select...</option>
                                                    <option value="strong_hire">👍👍 Strong Hire</option>
                                                    <option value="hire">👍 Hire</option>
                                                    <option value="no_hire">👎 No Hire</option>
                                                    <option value="strong_no_hire">👎👎 Strong No Hire</option>
                                                </select>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-neutral-600 mb-1">Strengths</label>
                                                    <textarea name="strengths" rows="2" class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-primary focus:border-primary" placeholder="Key strengths observed..."></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-neutral-600 mb-1">Areas for Improvement</label>
                                                    <textarea name="weaknesses" rows="2" class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-primary focus:border-primary" placeholder="Areas needing development..."></textarea>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-neutral-600 mb-1">Additional Comments</label>
                                                <textarea name="comments" rows="2" class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-primary focus:border-primary"></textarea>
                                            </div>
                                            <button type="submit" class="btn-primary text-sm">Submit Scorecard</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-neutral-500">
                            <svg class="w-10 h-10 mx-auto text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="font-medium">No interviews scheduled yet</p>
                            <p class="text-sm mt-1">Click "Schedule Interview" to set up the first round.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Resume Preview --}}
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="bg-neutral-50 px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-neutral-900 uppercase tracking-widest">Resume / CV</h2>
                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                        Download Original
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                </div>
                <div class="aspect-[4/5] w-full bg-neutral-100">
                    <iframe src="{{ asset('storage/' . $application->resume_path) }}" class="w-full h-full" frameborder="0"></iframe>
                </div>
            </div>

            {{-- Cover Letter / Motivation --}}
            @if($application->cover_letter || $application->motivation)
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
                @if($application->cover_letter)
                <div class="mb-8">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Cover Letter</h2>
                    <div class="prose prose-neutral max-w-none text-neutral-700 leading-relaxed italic">
                        {!! nl2br(e($application->cover_letter)) !!}
                    </div>
                </div>
                @endif

                @if($application->motivation)
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Motivation / Why Us?</h2>
                    <div class="bg-neutral-50 p-6 rounded-lg text-neutral-700 leading-relaxed border border-neutral-100">
                        {{ $application->motivation }}
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Schedule Interview Modal -->
    <dialog id="scheduleInterviewModal" class="p-0 rounded-xl shadow-2xl backdrop:bg-black/40 w-full max-w-lg open:animate-fade-in-up">
        <div class="bg-white">
            <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                <h3 class="text-lg font-bold">Schedule Interview</h3>
                <button onclick="document.getElementById('scheduleInterviewModal').close()" class="text-neutral-400 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.interviews.store', $application) }}" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-neutral-700 mb-1">Interviewer *</label>
                        <select name="interviewer_id" required class="w-full px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="">Select interviewer...</option>
                            @foreach($interviewers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-neutral-700 mb-1">Round *</label>
                        <select name="round" required class="w-full px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $application->interviews->count() + 1 == $i ? 'selected' : '' }}>Round {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-neutral-700 mb-1">Date & Time *</label>
                        <input type="datetime-local" name="scheduled_at" required class="w-full px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-neutral-700 mb-1">Interview Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                            <option value="video">Video Call</option>
                            <option value="phone">Phone Screen</option>
                            <option value="in_person">In Person</option>
                            <option value="technical">Technical</option>
                            <option value="panel">Panel Interview</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-neutral-700 mb-1">Location / Meeting Link</label>
                    <input type="text" name="location_or_link" placeholder="e.g. https://meet.google.com/... or Room 3B" class="w-full px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-neutral-700 mb-1">Notes for Interviewer</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm" placeholder="Any preparation notes..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-100">
                    <button type="button" onclick="document.getElementById('scheduleInterviewModal').close()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Schedule Interview</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>


