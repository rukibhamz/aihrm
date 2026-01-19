<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Custom Report Builder</h1>
        <p class="mt-1 text-sm text-neutral-500">Generate and export custom reports for your organization.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Configuration Panel -->
        <div class="lg:col-span-1 space-y-6">
            <form action="{{ route('admin.reports.generate') }}" method="POST" id="reportForm">
                @csrf
                
                <!-- 1. Select Entity -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold mb-4">1. Select Data Source</h3>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="entity" value="employees" class="form-radio h-5 w-5 text-black" checked onchange="updateFields()">
                            <span class="ml-3 font-medium">Employees</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="entity" value="leaves" class="form-radio h-5 w-5 text-black" onchange="updateFields()">
                            <span class="ml-3 font-medium">Leave Requests</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="entity" value="payrolls" class="form-radio h-5 w-5 text-black" onchange="updateFields()">
                            <span class="ml-3 font-medium">Payroll Records</span>
                        </label>
                    </div>
                </div>

                <!-- 2. Filters -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold mb-4">2. Apply Filters</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" name="start_date" class="form-input w-full rounded-md border-gray-300">
                                <input type="date" name="end_date" class="form-input w-full rounded-md border-gray-300">
                            </div>
                        </div>

                        <div id="deptFilter">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <select name="department_id" class="form-select w-full rounded-md border-gray-300">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="form-select w-full rounded-md border-gray-300">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button type="submit" name="action" value="preview" class="btn-primary flex-1 justify-center">Generate Preview</button>
                    <button type="submit" name="action" value="export_csv" class="btn-secondary flex-1 justify-center">Export CSV</button>
                </div>
            </form>
        </div>

        <!-- Field Selection & Preview Area -->
        <div class="lg:col-span-2">
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">3. Select Fields</h3>
                <div id="fieldsContainer" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Fields will be injected here by JS -->
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-black mr-2" onchange="toggleAllFields()">
                        Select All
                    </label>
                    <span class="text-xs text-gray-400">Select at least one field</span>
                </div>
            </div>

            <!-- Preview Placeholder -->
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Report Preview</h3>
                <p class="text-gray-500 mt-1">Select your criteria and click "Generate Preview" to see results here.</p>
            </div>
        </div>
    </div>

    <script>
        const fields = {
            employees: [
                { key: 'name', label: 'Employee Name' },
                { key: 'email', label: 'Email Address' },
                { key: 'department', label: 'Department' },
                { key: 'designation', label: 'Designation' },
                { key: 'status', label: 'Status' },
                { key: 'join_date', label: 'Join Date' },
                { key: 'phone', label: 'Phone Number' }
            ],
            leaves: [
                { key: 'employee_name', label: 'Employee Name' },
                { key: 'leave_type', label: 'Leave Type' },
                { key: 'start_date', label: 'Start Date' },
                { key: 'end_date', label: 'End Date' },
                { key: 'duration', label: 'Duration (Days)' },
                { key: 'status', label: 'Status' },
                { key: 'reason', label: 'Reason' }
            ],
            payrolls: [
                { key: 'employee_name', label: 'Employee Name' },
                { key: 'month', label: 'Month' },
                { key: 'year', label: 'Year' },
                { key: 'basic_salary', label: 'Basic Salary' },
                { key: 'net_salary', label: 'Net Salary' },
                { key: 'status', label: 'Status' },
                { key: 'payment_date', label: 'Payment Date' }
            ]
        };

        function updateFields() {
            const entity = document.querySelector('input[name="entity"]:checked').value;
            const container = document.getElementById('fieldsContainer');
            const deptFilter = document.getElementById('deptFilter');
            
            // Show/Hide Department Filter
            if (entity === 'employees') {
                deptFilter.style.display = 'block';
            } else {
                deptFilter.style.display = 'none';
            }

            // Generate Fields
            container.innerHTML = fields[entity].map(field => `
                <label class="flex items-center p-2 border rounded hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="fields[]" value="${field.key}" class="rounded border-gray-300 text-black mr-2" checked>
                    <span class="text-sm text-gray-700">${field.label}</span>
                </label>
            `).join('');
        }

        function toggleAllFields() {
            const checkboxes = document.querySelectorAll('input[name="fields[]"]');
            const selectAll = document.getElementById('selectAll');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        }

        // Initialize
        updateFields();
    </script>
</x-app-layout>
