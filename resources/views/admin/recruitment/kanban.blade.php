<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Recruitment Board') }}
        </h2>
    </x-slot>

    <div class="h-full flex flex-col">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Recruitment Board</h2>
            <p class="text-sm text-gray-500">Manage candidates through the hiring pipeline</p>
        </div>
        <div class="flex gap-3">
             <form method="GET" action="{{ route('admin.applications.kanban') }}">
                <select name="job_posting_id" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                    <option value="">All Jobs</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ $jobId == $job->id ? 'selected' : '' }}>
                            {{ $job->title }}
                        </option>
                    @endforeach
                </select>
             </form>
            <a href="{{ route('admin.jobs.create') }}" class="btn-primary">Post Job</a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden">
        <div class="flex h-full gap-6 min-w-max pb-4">
            @php
                $columns = [
                    'applied' => ['label' => 'Applied', 'color' => 'bg-gray-100 border-gray-200'],
                    'screening' => ['label' => 'Screening', 'color' => 'bg-blue-50 border-blue-200'],
                    'interview' => ['label' => 'Interview', 'color' => 'bg-yellow-50 border-yellow-200'],
                    'offer' => ['label' => 'Offer Sent', 'color' => 'bg-purple-50 border-purple-200'],
                    'hired' => ['label' => 'Hired', 'color' => 'bg-green-50 border-green-200'],
                    'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-50 border-red-200'],
                ];
            @endphp

            @foreach($columns as $key => $col)
            <div class="w-80 flex flex-col {{ $col['color'] }} rounded-xl border h-full max-h-[calc(100vh-200px)]">
                <!-- Header -->
                <div class="p-4 border-b border-gray-200/50 flex justify-between items-center bg-white/50 rounded-t-xl backdrop-blur-sm">
                    <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">
                        {{ $col['label'] }}
                        <span class="ml-2 bg-white text-gray-600 px-2 py-0.5 rounded-full text-xs border border-gray-200 shadow-sm">
                            {{ $board[$key]->count() }}
                        </span>
                    </h3>
                </div>

                <!-- Cards Container -->
                <div class="p-3 flex-1 overflow-y-auto space-y-3" 
                     ondragover="allowDrop(event)" 
                     ondrop="drop(event, '{{ $key }}')">
                    
                    @foreach($board[$key] as $app)
                    <div id="card-{{ $app->id }}" 
                         draggable="true" 
                         ondragstart="drag(event, '{{ $app->id }}')"
                         class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition cursor-move group relative">
                        
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-gray-900">{{ $app->candidate_name }}</h4>
                            @if($app->ai_score)
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-green-100 text-green-700 border border-green-200" title="AI Match Score">
                                {{ $app->ai_score }}%
                            </span>
                            @endif
                        </div>
                        
                        <p class="text-xs text-gray-500 mb-3 line-clamp-1">{{ $app->jobPosting->title }}</p>
                        
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-2 border-t border-gray-50 text-xs text-gray-400">
                            <span>{{ $app->created_at->format('M d') }}</span>
                            @if($app->resume_path)
                            <a href="{{ Storage::url($app->resume_path) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1" onclick="event.stopPropagation();">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Resume
                            </a>
                            @endif
                        </div>
                        
                        <!-- Quick View Trigger (Optional) -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                            <!-- Could add edit/view icon here -->
                        </div>
                    </div>
                    @endforeach
                    
                    @if($board[$key]->isEmpty())
                        <div class="text-center py-8 opacity-40">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 mx-2">
                                <p class="text-xs font-medium text-gray-500">Empty</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function allowDrop(ev) {
        ev.preventDefault();
        ev.currentTarget.classList.add('bg-gray-100/50');
    }

    function drag(ev, id) {
        ev.dataTransfer.setData("text/plain", id);
        ev.target.style.opacity = '0.5';
    }

    function drop(ev, newStatus) {
        ev.preventDefault();
        ev.currentTarget.classList.remove('bg-gray-100/50');
        var id = ev.dataTransfer.getData("text/plain");
        var card = document.getElementById("card-" + id);
        
        // Optimistic UI Update
        ev.currentTarget.appendChild(card);
        card.style.opacity = '1';

        // AJAX Call
        fetch(`/admin/applications/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if(!data.success) {
                alert('Failed to update status');
                location.reload();
            } else {
                // Optional: Show toast
                console.log('Status updated to ' + newStatus);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong');
            location.reload();
        });
    }
    
    // Reset opacity if drag ends without drop
    document.addEventListener("dragend", function(event) {
        event.target.style.opacity = "1";
    });
</script>
</x-app-layout>

