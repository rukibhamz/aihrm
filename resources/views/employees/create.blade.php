<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Add Employee</h1>
            <p class="mt-1 text-sm text-neutral-500">Create a new employee profile</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg max-w-3xl mx-auto">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="card p-8 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Email *</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Phone</label>
                        <input type="text" name="phone" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Date of Birth</label>
                        <input type="date" name="dob" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="pt-6 border-t border-neutral-200">
                <h3 class="text-lg font-semibold mb-4">Employment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Department *</label>
                        <select name="department_id" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">Select Department</option>
                            @foreach(\App\Models\Department::all() as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Role *</label>
                        <select name="designation_id" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">Select Role</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Grade Level</label>
                        <select name="grade_level_id" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">Select Grade Level</option>
                            @foreach(\App\Models\GradeLevel::all() as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Direct Manager</label>
                        <select name="manager_id" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">No Manager (Top Level)</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->user_id }}">{{ $manager->user->name }} ({{ $manager->designation->title ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Join Date</label>
                        <input type="date" name="join_date" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Profile Photo</label>
                        <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="pt-6 border-t border-neutral-200">
                <h3 class="text-lg font-semibold mb-4">Address</h3>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Full Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"></textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary">Create Employee</button>
                <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
