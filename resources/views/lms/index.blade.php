<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Learning Center</h1>
        <p class="mt-1 text-sm text-neutral-500">Expand your skills with our training modules</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
        <a href="{{ route('lms.show', $course) }}" class="card group hover:shadow-md transition-all duration-200 overflow-hidden block">
            <div class="aspect-video bg-neutral-100 relative">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-neutral-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-lg text-neutral-900 group-hover:text-black">{{ $course->title }}</h3>
                    @if($course->userCompletion && $course->userCompletion->status === 'completed')
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded">Completed</span>
                    @endif
                </div>
                <p class="text-sm text-neutral-500 line-clamp-2 mb-4">{{ $course->description }}</p>
                
                @if($course->userCompletion && $course->userCompletion->status === 'completed')
                    <a href="{{ route('lms.certificate', $course) }}" class="btn-secondary w-full text-center py-2 flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                        Download Certificate
                    </a>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12 text-neutral-500">
            No courses available at the moment.
        </div>
        @endforelse
    </div>
</x-app-layout>
