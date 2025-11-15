<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    /**
     * @desc Store new job application
     * @route POST /jobs/{job}/apply
     */
    public function store (request $request, Job $job): RedirectResponse
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'full_name' =>  'required|string',
            'contact_phone' =>  'string',
            'contact_email' =>  'required|string|email',
            'message'   =>  'string',
            'location'  =>  'string',
            'resume'    =>  'required|file|mimes:pdf|max:2048',
        ]);

        // Handle resume upload
        if ($request->hasfile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $validatedData['resume_path'] = $path;
        }

        // Store application @var
        $application = new Applicant($validatedData);
        $application->job_id = $job->id;
        $application->user_id = auth()->id();
        $application->save();

        return redirect()->back()->with('success', 'Your application has been submitted!');
    }
}
