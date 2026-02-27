<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Performance Review</h1>
            <p class="mt-1 text-sm text-neutral-500">{{ $review->period }} &middot; {{ ucfirst($review->type) }} Review</p>
        </div>
        <a href="{{ route('performance.reviews.index') }}" class="btn-secondary text-sm">← Back to Reviews</a>
    </div>

    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Status --}}
        <div class="rounded-xl p-4 flex items-center gap-3
            {{ $review->status === 'completed' ? 'bg-green-50 border border-green-200' :
               ($review->status === 'submitted' ? 'bg-blue-50 border border-blue-200' : 'bg-neutral-50 border border-neutral-200') }}">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                {{ $review->status === 'completed' ? 'bg-green-100 text-green-600' :
                   ($review->status === 'submitted' ? 'bg-blue-100 text-blue-600' : 'bg-neutral-100 text-neutral-500') }}">
                @if($review->status === 'completed') ✓
                @elseif($review->status === 'submitted') →
                @else ✎
                @endif
            </div>
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-neutral-400">Status</div>
                <div class="text-sm font-bold text-neutral-900">{{ ucfirst($review->status) }}</div>
            </div>
        </div>

        {{-- Participants --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <h2 class="text-lg font-bold text-neutral-900 mb-4">Review Participants</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Employee (Reviewee)</dt>
                    <dd class="mt-1 text-sm font-medium text-neutral-900">{{ $review->reviewee->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Reviewer</dt>
                    <dd class="mt-1 text-sm font-medium text-neutral-900">{{ $review->reviewer->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Review Period</dt>
                    <dd class="mt-1 text-sm font-medium text-neutral-900">{{ $review->period }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">Type</dt>
                    <dd class="mt-1 text-sm font-medium text-neutral-900">{{ ucfirst($review->type) }} Review</dd>
                </div>
            </dl>
        </div>

        {{-- Review Content / Evaluation Form --}}
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <h2 class="text-lg font-bold text-neutral-900 mb-4">Evaluation</h2>

            @if($review->content && is_array($review->content))
                <div class="space-y-4">
                    @foreach($review->content as $key => $value)
                    <div class="border-b border-neutral-100 pb-3">
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                        <dd class="mt-1 text-sm text-neutral-700 leading-relaxed">{{ is_array($value) ? json_encode($value) : $value }}</dd>
                    </div>
                    @endforeach
                </div>
            @elseif($review->status === 'draft' && $review->reviewer_id === Auth::id())
                <form method="POST" action="{{ route('performance.reviews.update', $review) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Overall Performance Rating</label>
                        <select name="content[rating]" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">Select rating...</option>
                            <option value="5">5 — Exceptional</option>
                            <option value="4">4 — Exceeds Expectations</option>
                            <option value="3">3 — Meets Expectations</option>
                            <option value="2">2 — Needs Improvement</option>
                            <option value="1">1 — Unsatisfactory</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Strengths</label>
                        <textarea name="content[strengths]" rows="3" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm" placeholder="Key strengths demonstrated..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Areas for Improvement</label>
                        <textarea name="content[improvements]" rows="3" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm" placeholder="Areas where improvement is needed..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Overall Comments</label>
                        <textarea name="content[comments]" rows="4" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm" placeholder="General comments and feedback..."></textarea>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-neutral-200">
                        <button type="submit" name="status" value="draft" class="btn-secondary">Save as Draft</button>
                        <button type="submit" name="status" value="submitted" class="btn-primary">Submit Review</button>
                    </div>
                </form>
            @else
                <p class="text-sm text-neutral-500">No evaluation content submitted yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>
