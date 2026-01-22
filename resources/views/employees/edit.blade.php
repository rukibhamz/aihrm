<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Edit Employee</h1>
        <p class="mt-1 text-sm text-neutral-500">Update employee information and settings</p>
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
        <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data" class="card p-8 space-y-8 shadow-sm border border-neutral-100">
            @csrf
            @method('PUT')
            
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-bold text-neutral-900 mb-6 pb-2 border-b border-neutral-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" required value="{{ old('first_name', explode(' ', $employee->user->name)[0]) }}" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" required value="{{ old('last_name', explode(' ', $employee->user->name)[1] ?? '') }}" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Email Address *</label>
                        <input type="email" name="email" required value="{{ old('email', $employee->user->email) }}" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $employee->phone) }}" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Address</label>
                        <textarea name="address" rows="3" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">{{ old('address', $employee->address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $employee->dob ? $employee->dob->format('Y-m-d') : '') }}" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div>
                <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-neutral-200">Employment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Department *</label>
                        <select name="department_id" required 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Role *</label>
                        <input type="text" name="designation" required value="{{ old('designation', $employee->designation->title ?? '') }}" 
                            placeholder="e.g. Sales Manager"
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">System Role *</label>
                        <select name="role" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $employee->user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Direct Manager</label>
                        <select name="manager_id" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">No Manager (Top Level)</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->user_id }}" {{ old('manager_id', $employee->manager_id) == $manager->user_id ? 'selected' : '' }}>
                                    {{ $manager->user->name }} ({{ $manager->designation->title ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Joining Date</label>
                        <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date ? $employee->join_date->format('Y-m-d') : '') }}" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                        <select name="status" required 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div>
                <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-neutral-200">Documents</h3>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Profile Photo</label>
                    <div class="flex items-center gap-4">
                        @if($employee->user->profile_photo_url)
                            <img src="{{ $employee->user->profile_photo_url }}" alt="Current Photo" class="w-12 h-12 rounded-full object-cover border border-neutral-200">
                        @endif
                        <input type="file" name="photo" accept="image/*" 
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">Leave blank to keep current photo</p>
                </div>
            </div>

            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary">Update Employee</button>
                <a href="{{ route('employees.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
