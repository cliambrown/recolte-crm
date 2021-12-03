<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectPostRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = trim(data_get($_GET, 'q', ''));
        
        if ($q) {
            $projects = Project::search($q)
                ->orderBy('name')
                ->paginate('20');
        } else {
            $projects = Project::orderBy('name')
                ->paginate('20');
        }
        
        $data = [
            'projects' => $projects,
            'q' => $q,
        ];
        
        return view('projects.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $project = new Project;
        
        $data = [
            'isEdit' => false,
            'project' => $project,
        ];
        return view('projects.create-edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectPostRequest $request)
    {
        $project = new Project;
        $project->name = $request->name;
        $project->short_name = $request->short_name;
        $project->description = $request->description;
        $project->start_year = $request->start_year;
        $project->start_month = $request->start_month;
        $project->start_day = $request->start_day;
        $project->end_year = $request->end_year;
        $project->end_month = $request->end_month;
        $project->end_day = $request->end_day;
        $project->notes = $request->notes;
        
        $project->save();
        
        return redirect()
            ->route('projects.show', ['project' => $project->id])
            ->with('status', __('New project saved.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('projects.show')->with(['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $data = [
            'isEdit' => true,
            'project' => $project,
        ];
        return view('projects.create-edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $project->name = $request->name;
        $project->short_name = $request->short_name;
        $project->description = $request->description;
        $project->start_year = $request->start_year;
        $project->start_month = $request->start_month;
        $project->start_day = $request->start_day;
        $project->end_year = $request->end_year;
        $project->end_month = $request->end_month;
        $project->end_day = $request->end_day;
        $project->notes = $request->notes;
        
        $project->save();
        
        return redirect()
            ->route('projects.show', ['project' => $project->id])
            ->with('status', __('Project updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()
            ->route('projects.index')
            ->with('status', __('Project deleted.'));
    }
}
