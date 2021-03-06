<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionPostRequest;
use App\Models\Org;
use App\Models\Person;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $position = new Position;
        $position->is_current = get_request_boolean(old('is_current', true));
        
        $redirectUrl = null;
        
        $org = null;
        $orgID = intval(data_get($_GET, 'org'));
        if (!!$orgID) {
            $org = Org::find($orgID);
            if ($org) $redirectUrl = route('orgs.show', ['org' => $org->id]);
        }

        $person = null;
        $personID = intval(data_get($_GET, 'person'));
        if (!!$personID) {
            $person = Person::find($personID);
            if ($person) $redirectUrl = route('people.show', ['person' => $person->id]);
        }

        $data = [
            'isEdit' => false,
            'position' => $position,
            'org' => $org,
            'person' => $person,
            'redirectUrl' => $redirectUrl,
        ];

        return view('positions.create-edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Position  $position
     * @param  string  $redirectUrl
     * @return \Illuminate\Http\Response
     */
    private static function doCheckCurrentPosition(Position $position, string $redirectUrl) {
        $person = $position->person;
        if (!$person) return false;
        $person->load('positions.org');
        $positions = $person->positions;
        if ($positions->isEmpty()) return false;
        $currentPosition = $person->getCurrentPosition();
        $problem = null;
        if ($currentPosition) {
            if ($currentPosition->end_date->isFuture()) return false;
            $problem = 'current_expired';
        } else {
            $unFinishedPosition = $positions->first(function ($thisPost, $key) {
                return $thisPost->end_date->isFuture();
            });
            if (!$unFinishedPosition) return false;
            $problem = 'unfinished_position';
        }
        
        $data = [
            'person' => $person->id,
            'problem' => $problem,
            'url' => $redirectUrl,
        ];
        return redirect()->route('positions.confirm_current', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PositionPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionPostRequest $request)
    {
        $isCurrent = get_request_boolean($request->is_current);
        
        $position = new Position;
        $position->person_id = $request->person_id;
        $position->org_id = $request->org_id;
        $position->is_current = $isCurrent;
        $position->title = $request->title;
        $position->email = $request->email;
        $position->phone = $request->phone;
        $position->start_year = $request->start_year;
        $position->start_month = $request->start_month;
        $position->start_day = $request->start_day;
        $position->end_year = $request->end_year;
        $position->end_month = $request->end_month;
        $position->end_day = $request->end_day;
        $position->notes = $request->notes;
        $position->save();
        
        if ($isCurrent) {
            Position::where('person_id', $request->person_id)
                ->where('id', '!=', $position->id)
                ->update(['is_current' => null]);
        }
        
        $redirectUrl = route('people.show', ['person' => $request->person_id]);
        $requestRedirectUrl = $request->redirect_url;
        if (is_this_domain($requestRedirectUrl)) $redirectUrl = $requestRedirectUrl;
        
        $response = $this->doCheckCurrentPosition($position, $redirectUrl);
        if ($response !== false) {
            return $response->with('status', __('Position created.'));
        }
        
        return redirect($redirectUrl)
            ->with('status', __('Position created.'));
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
        $position->is_current = get_request_boolean(old('is_current', $position->is_current));
        
        $redirectUrl = null;
        $orgID = intval(data_get($_GET, 'org'));
        $personID = intval(data_get($_GET, 'person'));
        if (!!$orgID && $orgID === $position->org_id) {
            $redirectUrl = route('orgs.show', ['org' => $orgID]);
        } elseif (!!$personID && $personID === $position->person_id) {
            $redirectUrl = route('people.show', ['person' => $personID]);
        }
        
        $data = [
            'isEdit' => true,
            'position' => $position,
            'org' => $position->org,
            'person' => $position->person,
            'redirectUrl' => $redirectUrl,
        ];

        return view('positions.create-edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PositionPostRequest  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(PositionPostRequest $request, Position $position)
    {
        $isCurrent = get_request_boolean($request->is_current);
        
        $position->person_id = $request->person_id;
        $position->org_id = $request->org_id;
        $position->title = $request->title;
        $position->is_current = $isCurrent;
        $position->email = $request->email;
        $position->phone = $request->phone;
        $position->start_year = $request->start_year;
        $position->start_month = $request->start_month;
        $position->start_day = $request->start_day;
        $position->end_year = $request->end_year;
        $position->end_month = $request->end_month;
        $position->end_day = $request->end_day;
        $position->notes = $request->notes;
        $position->save();
        
        if ($isCurrent) {
            Position::where('person_id', $request->person_id)
                ->where('id', '!=', $position->id)
                ->update(['is_current' => null]);
        }
        
        $redirectUrl = route('people.show', ['person' => $request->person_id]);
        $requestRedirectUrl = $request->redirect_url;
        if (is_this_domain($requestRedirectUrl)) $redirectUrl = $requestRedirectUrl;
        
        $response = $this->doCheckCurrentPosition($position, $redirectUrl);
        if ($response !== false) {
            return $response->with('status', __('Position updated.'));
        }
        
        return redirect($redirectUrl)
            ->with('status', __('Position updated.'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function confirm_current(Person $person)
    {
        $person->load('positions.org');
        $currentPosition = $person->getCurrentPosition();
        $data = [
            'person' => $person,
            'problem' => data_get($_GET, 'problem'),
            'redirectUrl' => data_get($_GET, 'url'),
            'currentPosition' => $currentPosition,
        ];
        return view('positions.confirm-current')->with($data);
    }
    
    /**
     * Update the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function update_current(Request $request, Person $person)
    {
        $request->validate([
            'current_position_id' => 'nullable|int',
            'redirect_url' => 'nullable|url',
        ]);
        
        $person->positions()
            ->where('positions.id', '!=', $request->current_position_id)
            ->update(['is_current' => null]);
        
        $person->positions()
            ->where('positions.id', $request->current_position_id)
            ->update(['is_current' => 1]);
            
        $redirectUrl = route('people.show', ['person' => $person->id]);
        $requestRedirectUrl = $request->redirect_url;
        if (is_this_domain($requestRedirectUrl)) $redirectUrl = $requestRedirectUrl;
        
        return redirect($redirectUrl)
            ->with('status', __('Current position updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        $person = $position->person;
        $position->delete();
        
        $redirectUrl = route('people.show', ['person' => $person->id]);
        $requestRedirectUrl = data_get($_POST, 'redirect_url', '');
        if (is_this_domain($requestRedirectUrl)) $redirectUrl = $requestRedirectUrl;
        
        return redirect($redirectUrl)
            ->with('status', __('Position removed.'));
    }
}
