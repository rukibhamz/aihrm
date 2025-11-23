<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for {{ $job->title }} - AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-neutral-50 min-h-screen py-12">
    <div class="max-w-2xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-4">
                <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">AI</span>
                </div>
                <span class="font-bold text-2xl tracking-tight">AIHRM</span>
            </div>
            <h1 class="text-3xl font-bold mb-2">{{ $job->title }}</h1>
            <p class="text-neutral-600">{{ $job->department }} • {{ $job->location }}</p>
        </div>

        <!-- Application Form -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
            <h2 class="text-xl font-bold mb-6">Submit Your Application</h2>
            
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('applications.store', $job) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Full Name *</label>
                    <input type="text" name="candidate_name" required value="{{ old('candidate_name') }}"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Email Address *</label>
                    <input type="email" name="candidate_email" required value="{{ old('candidate_email') }}"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Phone Number</label>
                    <input type="tel" name="candidate_phone" value="{{ old('candidate_phone') }}"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Resume/CV * (PDF, DOC, DOCX - Max 5MB)</label>
                    <input type="file" name="resume" required accept=".pdf,.doc,.docx"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    <p class="mt-2 text-xs text-neutral-500">
                        🤖 Your resume will be analyzed by our AI to match your skills with job requirements
                    </p>
                </div>

                <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-neutral-800 transition">
                    Submit Application
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-neutral-500 mt-6">&copy; {{ date('Y') }} AIHRM. All rights reserved.</p>
    </div>
</body>
</html>
