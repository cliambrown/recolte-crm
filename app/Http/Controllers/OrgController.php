<?php

namespace App\Http\Controllers;

use App\Models\Org;
use Illuminate\Http\Request;

class OrgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orgs = Org::orderBy('name')
            ->paginate('20');
        return view('orgs.index')->with(['orgs' => $orgs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $org = new Org;
        return view('orgs.create-edit')->with(['isEdit' => false, 'org' => $org]);
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
            'name' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $org = new Org;
        $org->created_by_user_id = auth()->user()->id;
        $org->name = $request->name;
        $org->notes = $request->notes;
        $org->save();
        
        return redirect()
            ->route('orgs.edit', ['org' => $org->id])
            ->with('status', __('New org saved.'));
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Org  $org
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Org $org)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function edit(Org $org)
    {
        return view('orgs.create-edit')->with(['isEdit' => false, 'org' => $org]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Org $org)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function destroy(Org $org)
    {
        //
    }
}
