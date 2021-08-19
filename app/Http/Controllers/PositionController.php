<?php

namespace App\Http\Controllers;

use App\Models\Org;
use App\Models\Person;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $org = null;
        $orgID = data_get($_GET, 'org');
        if ($orgID) {
            $org = Org::find($orgID);
        }
        if (!$org) $org = new Org;

        $person = null;
        $personID = data_get($_GET, 'person');
        if ($personID) {
            $person = Person::find($personID);
        }
        if (!$person) $person = new Person();

        $data = [
            'isEdit' => false,
            'org' => $org,
            'person' => $person,
        ];

        return view('positions.create-edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Position  $position
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Position $position)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //
    }
}
