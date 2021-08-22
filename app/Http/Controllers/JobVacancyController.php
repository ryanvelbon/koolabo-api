<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return JobVacancy::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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

        $vacancy = JobVacancy::find($id);
        $vacancy->update($request->all());
        return $vacancy;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // use middleware or just check user_id

        return JobVacancy::destroy($id);
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
