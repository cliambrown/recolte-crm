<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectOrgPostRequest;
use App\Models\Project;
use App\Models\ProjectOrg;
use Illuminate\Http\Request;

class ProjectOrgController extends Controller
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
        $projectOrg = new ProjectOrg;
        
        $data = [
            'isEdit' => false,
            'project' => $project,
            'projectOrg' => $projectOrg,
            'org' => null,
        ];

        return view('project_orgs.create-edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ProjectOrgPostRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectOrgPostRequest $request, Project $project)
    {
        $projectOrg = new ProjectOrg;
        $projectOrg->project_id = $project->id;
        $projectOrg->org_id = $request->org_id;
        $projectOrg->role = $request->role;
        $projectOrg->start_year = $request->start_year;
        $projectOrg->start_month = $request->start_month;
        $projectOrg->start_day = $request->start_day;
        $projectOrg->end_year = $request->end_year;
        $projectOrg->end_month = $request->end_month;
        $projectOrg->end_day = $request->end_day;
        $projectOrg->notes = $request->notes;
        $projectOrg->save();
        
        return redirect(route('projects.show', ['project' => $project->id]))
            ->with('status', __('Project org added.'));
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\ProjectOrg  $projectOrg
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(ProjectOrg $projectOrg)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectOrg  $projectOrg
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, ProjectOrg $projectOrg)
    {
        $data = [
            'isEdit' => true,
            'project' => $project,
            'projectOrg' => $projectOrg,
            'org' => $projectOrg->org,
        ];

        return view('project_orgs.create-edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ProjectOrgPostRequest  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectOrg  $projectOrg
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectOrgPostRequest $request, Project $project, ProjectOrg $projectOrg)
    {
        $projectOrg->role = $request->role;
        $projectOrg->start_year = $request->start_year;
        $projectOrg->start_month = $request->start_month;
        $projectOrg->start_day = $request->start_day;
        $projectOrg->end_year = $request->end_year;
        $projectOrg->end_month = $request->end_month;
        $projectOrg->end_day = $request->end_day;
        $projectOrg->notes = $request->notes;
        $projectOrg->save();
        
        return redirect(route('projects.show', ['project' => $project->id]))
            ->with('status', __('Project org updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectOrg  $projectOrg
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, ProjectOrg $projectOrg)
    {
        $projectOrg->delete();
        return redirect(route('projects.show', ['project' => $project->id]))
            ->with('status', __('Project person deleted.'));
    }
}
