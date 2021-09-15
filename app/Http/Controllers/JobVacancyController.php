<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\Job;
use App\Models\JobVacancy;

class JobVacancyController extends Controller
{
    /**
     * Display a list of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return JobVacancy::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'job_id' => 'required',

        ]);

        $project = Job::find($request['job_id'])->project;

        Gate::authorize('isManager', $project);

        $data = $request->all();
        $data['ends_at'] = date('Y-m-d', strtotime("+30 day", strtotime(now())));

        return JobVacancy::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // should only be visible if active
        
        return JobVacancy::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate?

        $listing = JobVacancy::find($id);
        $project = $listing->job->project;

        Gate::authorize('canEditDeleteJobListing', $listing);

        $listing->update($request->all());

        return $listing;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $listing = JobVacancy::findOrFail($id);
        $project = $listing->job->project;

        Gate::authorize('canEditDeleteJobListing', $listing);

        JobVacancy::destroy($id);

        return response('Job Listing has been deleted', 200);
    }

    /**
     * Still working on this function
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function PENDINGsearch(Request $request)
    {
        return JobVacancy::where($request->all())->get();
    }

    public function search($q)
    {
        return JobVacancy::where('title', 'like', '%'.$q.'%')->get();
    }
}
