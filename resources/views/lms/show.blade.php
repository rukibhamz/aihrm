<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('lms.index') }}" class="text-sm text-neutral-500 hover:text-black flex items-center gap-1 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Courses
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">{{ $course->title }}</h1>
        </div>

        <div class="card overflow-hidden mb-8">
            <div class="aspect-video bg-black flex items-center justify-center">
                <!-- Placeholder for Video Player -->
                <div class="text-white text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-lg">Video Content Placeholder</p>
                    <p class="text-sm text-neutral-400">{{ $course->video_url }}</p>
                </div>
            </div>
            <div class="p-8">
                <h3 class="text-lg font-semibold mb-4">Description</h3>
                <div class="prose prose-neutral max-w-none">
                    {{ $course->description }}
                </div>

                <div class="mt-8 pt-8 border-t border-neutral-100 flex justify-end">
                    <form action="{{ route('lms.complete', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary">Mark as Completed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
