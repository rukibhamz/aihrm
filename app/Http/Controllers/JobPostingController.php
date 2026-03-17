<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::query();
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $jobs = $query->with('creator')->latest()->paginate(15);
        return view('admin.jobs.index', compact('jobs'));
    }

    public function publicIndex(Request $request)
    {
        $query = JobPosting::query()->where('status', 'open');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('requirements', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->filled('location')) {
            $query->where('location', $request->input('location'));
        }

        if ($request->filled('type')) {
            $query->where('job_type', $request->input('type'));
        }

        if ($request->filled('salary')) {
            $salaries = $request->input('salary'); // Array of values like '50k-80k', '80k-120k', '180k+'
            $query->where(function($q) use ($salaries) {
                foreach ($salaries as $range) {
                    if ($range === '50k-80k') {
                        $q->orWhereBetween('min_salary', [50000, 80000]);
                    } elseif ($range === '80k-120k') {
                        $q->orWhereBetween('min_salary', [80000, 120000]);
                    } elseif ($range === '120k-180k') {
                        $q->orWhereBetween('min_salary', [120000, 180000]);
                    } elseif ($range === '180k+') {
                        $q->orWhere('min_salary', '>=', 180000);
                    }
                }
            });
        }

        $jobs = $query->with('creator')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = JobPosting::where('status', 'open')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        $locations = JobPosting::where('status', 'open')
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location');

        $departmentsByCount = JobPosting::where('status', 'open')
            ->select('department', \DB::raw('count(*) as total'))
            ->groupBy('department')
            ->orderBy('total', 'desc')
            ->get();
        
        return view('jobs.careers', compact('jobs', 'departments', 'locations', 'departmentsByCount'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'reporting_to' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed,draft',
        ]);

        $validated['created_by'] = Auth::id();

        JobPosting::create($validated);

        return redirect()->route('jobs.index')->with('success', 'Job posting created successfully.');
    }

    public function show(JobPosting $job)
    {
        $job->load(['applications.resumeAnalysis']);
        
        // Sort applications by AI score
        $applications = $job->applications()
            ->orderByDesc('ai_score')
            ->get();

        $similarJobs = JobPosting::where('department', $job->department)
            ->where('id', '!=', $job->id)
            ->where('status', 'open')
            ->latest()
            ->take(2)
            ->get();
        
        return view('jobs.show', compact('job', 'applications', 'similarJobs'));
    }

    public function edit(JobPosting $job)
    {
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed,draft',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.show', $job)->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $job)
    {
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job posting deleted successfully.');
    }

    public function applicants(Request $request, JobPosting $job)
    {
        $query = $job->applications()->with('resumeAnalysis');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('candidate_name', 'like', "%{$search}%")
                  ->orWhere('candidate_email', 'like', "%{$search}%")
                  ->orWhere('current_city', 'like', "%{$search}%")
                  ->orWhere('current_job_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            $sort = $request->sort;
            $direction = $request->input('direction', 'desc');
            
            if ($sort === 'ai_score') {
                $query->orderBy('ai_score', $direction);
            } elseif ($sort === 'experience') {
                $query->orderBy('years_of_experience', $direction);
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $applications = $query->paginate(20)->withQueryString();

        return view('admin.jobs.applicants', compact('job', 'applications'));
    }
}
