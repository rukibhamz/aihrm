<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Edit Job Posting</h1>
        <p class="mt-1 text-sm text-neutral-500">Update job details and status</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="card p-8 space-y-6 shadow-sm border border-neutral-100">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Job Title *</label>
                <input type="text" name="title" required value="{{ old('title', $job->title) }}" 
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                    placeholder="e.g., Senior Software Engineer">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Department</label>
                    <input type="text" name="department" value="{{ old('department', $job->department) }}"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                        placeholder="e.g., Engineering">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $job->location) }}"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                        placeholder="e.g., Lagos, Nigeria">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Job Description *</label>
                <textarea name="description" required rows="6"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                    placeholder="Describe the role, responsibilities...">{{ old('description', $job->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Requirements *</label>
                <textarea name="requirements" required rows="6"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                    placeholder="List required skills, experience, education...">{{ old('requirements', $job->requirements) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Status *</label>
                <select name="status" required
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    <option value="draft" {{ $job->status === 'draft' ? 'selected' : '' }}>Draft (not visible to candidates)</option>
                    <option value="open" {{ $job->status === 'open' ? 'selected' : '' }}>Open (accepting applications)</option>
                    <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed (not accepting applications)</option>
                </select>
            </div>

            <!-- Custom Application Questions Builder -->
            <div class="border-t border-neutral-200 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700">Custom Application Questions</label>
                        <p class="text-xs text-neutral-500 mt-1">Add extra questions to the application form for this role</p>
                    </div>
                    <button type="button" onclick="addQuestion()" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1 px-3 py-1.5 border border-indigo-200 rounded-lg hover:bg-indigo-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add Question
                    </button>
                </div>
                <div id="questionsContainer" class="space-y-3"></div>
                <input type="hidden" name="custom_questions" id="customQuestionsInput">
            </div>

            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary">Update Job Posting</button>
                <a href="{{ route('jobs.show', $job) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

<script>
    let questions = @json($job->custom_questions ?? []);

    function renderQuestions() {
        const container = document.getElementById('questionsContainer');
        container.innerHTML = '';
        questions.forEach((q, i) => {
            const div = document.createElement('div');
            div.className = 'p-4 bg-neutral-50 border border-neutral-200 rounded-lg space-y-3';
            div.innerHTML = `
                <div class="flex justify-between items-start">
                    <span class="text-xs font-bold uppercase tracking-wider text-neutral-400">Question ${i + 1}</span>
                    <button type="button" onclick="removeQuestion(${i})" class="text-neutral-400 hover:text-red-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <input type="text" value="${q.label || ''}" onchange="updateQuestion(${i}, 'label', this.value)"
                            class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-black focus:border-black"
                            placeholder="Question text, e.g. 'Are you willing to relocate?'">
                    </div>
                    <div>
                        <select onchange="updateQuestion(${i}, 'type', this.value)"
                            class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-black focus:border-black">
                            <option value="text" ${q.type === 'text' ? 'selected' : ''}>Short Text</option>
                            <option value="textarea" ${q.type === 'textarea' ? 'selected' : ''}>Long Text</option>
                            <option value="select" ${q.type === 'select' ? 'selected' : ''}>Dropdown</option>
                            <option value="checkbox" ${q.type === 'checkbox' ? 'selected' : ''}>Checkbox (Yes/No)</option>
                        </select>
                    </div>
                </div>
                ${q.type === 'select' ? `
                <div>
                    <input type="text" value="${(q.options || []).join(', ')}" onchange="updateQuestion(${i}, 'options', this.value.split(',').map(s => s.trim()).filter(s => s))"
                        class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-black focus:border-black"
                        placeholder="Options (comma separated, e.g. Yes, No, Maybe)">
                </div>` : ''}
                <label class="flex items-center gap-2 text-sm text-neutral-600">
                    <input type="checkbox" ${q.required ? 'checked' : ''} onchange="updateQuestion(${i}, 'required', this.checked)"
                        class="rounded border-neutral-300 text-black focus:ring-black">
                    Required field
                </label>
            `;
            container.appendChild(div);
        });
        document.getElementById('customQuestionsInput').value = JSON.stringify(questions);
    }

    function addQuestion() {
        questions.push({ label: '', type: 'text', required: false });
        renderQuestions();
    }

    function removeQuestion(index) {
        questions.splice(index, 1);
        renderQuestions();
    }

    function updateQuestion(index, field, value) {
        questions[index][field] = value;
        renderQuestions();
    }

    // Initialize
    renderQuestions();
</script>
</x-app-layout>
