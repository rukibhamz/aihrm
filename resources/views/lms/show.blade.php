<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar: Lessons List -->
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="card p-6 sticky top-8">
                    <h3 class="text-sm font-bold text-neutral-500 uppercase tracking-wider mb-4">Course Lessons</h3>
                    <div class="space-y-1">
                        @foreach($course->lessons as $l)
                        <a href="{{ route('lms.lesson', [$course, $l]) }}" 
                           class="flex items-center gap-3 p-3 rounded-md text-sm transition
                           {{ isset($lesson) && $lesson->id === $l->id ? 'bg-black text-white' : 'text-neutral-600 hover:bg-neutral-50 hover:text-black' }}">
                            <div class="w-6 h-6 flex-shrink-0 rounded-full border flex items-center justify-center text-[10px] font-bold
                                {{ isset($lesson) && $lesson->id === $l->id ? 'border-white/30 bg-white/10' : 'border-neutral-200' }}">
                                {{ $loop->iteration }}
                            </div>
                            <span class="truncate">{{ $l->title }}</span>
                        </a>
                        @endforeach
                        
                        @if($course->lessons->count() === 0)
                        <p class="text-xs text-neutral-400 italic">No lessons available yet.</p>
                        @endif
                    </div>

                    <div class="mt-8 pt-6 border-t border-neutral-100">
                        <form action="{{ route('lms.complete', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full btn-secondary text-sm">Mark Course as Completed</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1">
                <div class="mb-6">
                    <a href="{{ route('lms.index') }}" class="text-xs text-neutral-500 hover:text-black flex items-center gap-1 mb-4 uppercase font-bold tracking-widest">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back to Courses
                    </a>
                    <h1 class="text-3xl font-black tracking-tight text-neutral-900 mb-2">{{ $course->title }}</h1>
                    @if(isset($lesson))
                    <div class="flex items-center gap-2 text-neutral-500">
                        <span class="text-sm">Lesson {{ $course->lessons->where('order', '<=', $lesson->order)->count() }} of {{ $course->lessons->count() }}</span>
                        <span class="text-neutral-300">•</span>
                        <h2 class="text-lg font-bold text-black">{{ $lesson->title }}</h2>
                    </div>
                    @endif
                </div>

                <div class="card overflow-hidden mb-8">
                    @php
                        $activeItem = $lesson ?? $course;
                        $videoUrl = $activeItem->video_url;
                    @endphp

                    @if($videoUrl)
                    <div class="aspect-video bg-black flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg font-bold">Video Lesson</p>
                            <p class="text-sm text-neutral-400 max-w-xs mx-auto truncate">{{ $videoUrl }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="p-8 lg:p-12">
                        <div class="prose prose-neutral max-w-none">
                            @if(isset($lesson))
                                {!! nl2br(e($lesson->content)) !!}
                            @else
                                <h3 class="text-xl font-bold mb-4">Course Overview</h3>
                                {!! nl2br(e($course->description)) !!}
                                
                                <div class="mt-8 bg-neutral-50 rounded-xl p-8 border border-neutral-100">
                                    <h4 class="font-bold mb-2">Ready to start?</h4>
                                    <p class="text-sm text-neutral-600 mb-6">Select the first lesson from the sidebar to begin your learning journey.</p>
                                    @if($course->lessons->first())
                                    <a href="{{ route('lms.lesson', [$course, $course->lessons->first()]) }}" class="btn-primary inline-flex">Start Lesson 1</a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if(isset($lesson))
                        <div class="mt-12 pt-8 border-t border-neutral-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            @php
                                $prevLesson = $course->lessons->where('order', '<', $lesson->order)->last();
                                $nextLesson = $course->lessons->where('order', '>', $lesson->order)->first();
                            @endphp

                            @if($prevLesson)
                            <a href="{{ route('lms.lesson', [$course, $prevLesson]) }}" class="flex items-center gap-2 text-sm font-bold hover:text-neutral-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                PREVIOUS
                            </a>
                            @else
                            <div></div>
                            @endif

                            @if($nextLesson)
                            <a href="{{ route('lms.lesson', [$course, $nextLesson]) }}" class="flex items-center gap-2 text-sm font-bold hover:text-neutral-600">
                                NEXT
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            @else
                            <form action="{{ route('lms.complete', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm font-black text-green-600 hover:text-green-700">FINISH COURSE</button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
