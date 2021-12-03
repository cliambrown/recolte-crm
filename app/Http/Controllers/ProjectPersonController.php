<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectPersonPostRequest;
use App\Models\Project;
use App\Models\ProjectPerson;
use Illuminate\Http\Request;

class ProjectPersonController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     //
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $projectPerson = new ProjectPerson;
        
        $data = [
            'isEdit' => false,
            'project' => $project,
            'projectPerson' => $projectPerson,
            'person' => null,
        ];

        return view('project_people.create-edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProjectPersonPostRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectPersonPostRequest $request, Project $project)
    {
        $projectPerson = new ProjectPerson;
        $projectPerson->project_id = $project->id;
        $projectPerson->person_id = $request->person_id;
        $projectPerson->role = $request->role;
        $projectPerson->start_year = $request->start_year;
        $projectPerson->start_month = $request->start_month;
        $projectPerson->start_day = $request->start_day;
        $projectPerson->end_year = $request->end_year;
        $projectPerson->end_month = $request->end_month;
        $projectPerson->end_day = $request->end_day;
        $projectPerson->notes = $request->notes;
        $projectPerson->save();
        
        return redirect(route('projects.show', ['project' => $project->id]))
            ->with('status', __('Project person added.'));
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\ProjectPerson  $projectPerson
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(ProjectPerson $projectPerson)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectPerson  $projectPerson
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, ProjectPerson $projectPerson)
    {
        $data = [
            'isEdit' => true,
            'project' => $project,
            'projectPerson' => $projectPerson,
            'person' => $projectPerson->person,
        ];

        return view('project_people.create-edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProjectPersonPostRequest  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectPerson  $projectPerson
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectPersonPostRequest $request, Project $project, ProjectPerson $projectPerson)
    {
        $projectPerson->role = $request->role;
        $projectPerson->start_year = $request->start_year;
        $projectPerson->start_month = $request->start_month;
        $projectPerson->start_day = $request->start_day;
        $projectPerson->end_year = $request->end_year;
        $projectPerson->end_month = $request->end_month;
        $projectPerson->end_day = $request->end_day;
        $projectPerson->notes = $request->notes;
        $projectPerson->save();
        
        return redirect(route('projects.show', ['project' => $project->id]))
            ->with('status', __('Project person updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectPerson  $projectPerson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, ProjectPerson $projectPerson)
    {
        $projectPerson->delete();
        return redirect(route('projects.show', ['project' => $project->id]))
            ->with('status', __('Project person deleted.'));
    }
}
