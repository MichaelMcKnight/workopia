<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Mail\JobApplied;
use Illuminate\Support\Facades\Mail;

class ApplicantController extends Controller
{
    /**
     * @desc Store new job application
     * @route POST /jobs/{job}/apply
     */
    public function store (request $request, Job $job): RedirectResponse
    {
        // Check if user has already applied
        $existingApplication = Applicant::where('job_id', $job->id)->where('user_id', auth()->id())->exists();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied to this job.');
        }

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

        // Send email to owner
        Mail::to($job->user->email)->send(new JobApplied($application, $job));

        return redirect()->back()->with('success', 'Your application has been submitted!');
    }

    /**
     * @desc delete job application
     * @route DELETE /applicants/{applicant}
     */
    public function destroy ($id): RedirectResponse
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->delete();
        return redirect()->route('dashboard')->with('success', 'Applicant deleted successfully.');
    }
}
