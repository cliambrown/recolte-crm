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
        $orgID = data_get($_GET, 'org');
        if ($orgID) {
            $org = Org::find($orgID);
            if ($org) $redirectUrl = route('orgs.show', ['org' => $org->id]);
        }

        $person = null;
        $personID = data_get($_GET, 'person');
        if ($personID) {
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
     * @param  \Illuminate\Http\PositionPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionPostRequest $request)
    {
        $position = new Position;
        $position->person_id = $request->person_id;
        $position->org_id = $request->org_id;
        $position->is_current = get_request_boolean($request->is_current);
        $position->title = $request->title;
        $position->email = $request->email;
        $position->start_year = $request->start_year;
        $position->start_month = $request->start_month;
        $position->start_day = $request->start_day;
        $position->end_year = $request->end_year;
        $position->end_month = $request->end_month;
        $position->end_day = $request->end_day;
        $position->notes = $request->notes;
        $position->save();
        
        $redirectUrl = route('people.show', ['person' => $request->person_id]);
        $requestRedirectUrl = $request->redirect_url;
        if (is_this_domain($requestRedirectUrl)) $redirectUrl = $requestRedirectUrl;
        
        // Prompt if is_current but end_date is past
        // or if not is_current but end_date is null
        if ($position->is_current) {
            Position::where('person_id', $position->person_id)
                ->update(['is_current' => null]);
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
        $orgID = data_get($_GET, 'org');
        $personID = data_get($_GET, 'person');
        if ($orgID && $orgID === $position->org_id) {
            $redirectUrl = route('orgs.show', ['org' => $orgID]);
        } elseif ($personID && $personID === $position->person_id) {
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
        $position->person_id = $request->person_id;
        $position->org_id = $request->org_id;
        $position->title = $request->title;
        $position->email = $request->email;
        $position->start_year = $request->start_year;
        $position->start_month = $request->start_month;
        $position->start_day = $request->start_day;
        $position->end_year = $request->end_year;
        $position->end_month = $request->end_month;
        $position->end_day = $request->end_day;
        $position->notes = $request->notes;
        $position->save();
        
        $redirectUrl = route('people.show', ['person' => $request->person_id]);
        $requestRedirectUrl = $request->redirect_url;
        if (is_this_domain($requestRedirectUrl)) $redirectUrl = $requestRedirectUrl;
        
        $isCurrent = get_request_boolean($request->is_current);
        if ($isCurrent) {
            $endDate = Carbon::parse($request->validated()['end_date']);
            if ($request->end_year && $endDate->isPast()) {
                $redirectData = [
                    'position' => $position->id,
                    'isCurrent' => 'true',
                ];
                return redirect()->route('positions.confirm_current', $redirectData)
                    ->with('status', __('Position updated.'));
            } else {
                Position::where('person_id', $position->person_id)
                    ->update(['is_current' => null]);
            }
        } else {
            $endDate = Carbon::parse($request->validated()['end_date']);
            if ($endDate->isFuture()) {
                $redirectData = [
                    'position' => $position->id,
                    'isCurrent' => 'false',
                ];
                return redirect()->route('positions.confirm_current', $redirectData)
                    ->with('status', __('Position updated.'));
            }
        }
        
        return redirect($redirectUrl)
            ->with('status', __('Position updated.'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @param  Boolean  $isCurrent
     * @return \Illuminate\Http\Response
     */
    public function confirm_current(Position $position, $isCurrent)
    {
        $isCurrent = get_request_boolean($isCurrent);
        $data = [
            'position' => $position,
            'isCurrent' => $isCurrent,
        ];
        return view('positions.confirm-current')->with($data);
    }
    
    /**
     * Update the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update_current(Request $request, Position $position)
    {
        $request->validate([
            'is_current' => 'nullable|int',
        ]);
        
        $isCurrent = get_request_boolean($request->is_current);
        $position->is_current = $isCurrent;
        if ($isCurrent) {
            Position::where('person_id', $position->person_id)
                    ->update(['is_current' => null]);
        }
        return redirect()->route('people.show', ['person' => $position->person_id])
            ->with('status', __('Position updated.'));
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
