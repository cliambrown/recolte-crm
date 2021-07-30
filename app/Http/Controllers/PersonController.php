<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::orderBy('family_name')
            ->orderBy('given_name')
            ->paginate('20');
        return view('people.index')->with(['people' => $people]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $person = new Person;
        return view('people.create-edit')->with(['isEdit' => false, 'person' => $person]);
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
            'given_name' => 'required_without:family_name|nullable|string',
            'family_name' => 'required_without:given_name|nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $person = new Person;
        $person->created_by_user_id = auth()->user()->id;
        $person->given_name = $request->given_name;
        $person->family_name = $request->family_name;
        $person->notes = $request->notes;
        $person->save();
        
        return redirect()
            ->route('people.edit', ['person' => $person->id])
            ->with('status', __('New person saved.'));
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Person  $person
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Person $person)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        return view('people.create-edit')->with(['isEdit' => false, 'person' => $person]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        $request->validate([
            'given_name' => 'required_without:family_name|nullable|string',
            'family_name' => 'required_without:given_name|nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $person->given_name = $request->given_name;
        $person->family_name = $request->family_name;
        $person->notes = $request->notes;
        $person->save();
        
        return redirect()
            ->route('people.edit', ['person' => $person->id])
            ->with('status', __('Person updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        //
    }
}
