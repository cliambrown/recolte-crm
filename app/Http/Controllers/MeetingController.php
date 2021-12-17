<?php

namespace App\Http\Controllers;

use App\Enums\MeetingType;
use App\Http\Requests\MeetingPostRequest;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MeetingController extends Controller
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
            $meetings = Meeting::search($q)
                ->orderBy('occurred_on')
                ->orderBy('occurred_on_datetime')
                ->paginate('20');
        } else {
            $meetings = Meeting::orderBy('occurred_on')
                ->orderBy('occurred_on_datetime')
                ->paginate('20');
        }
        
        $data = [
            'meetings' => $meetings,
            'q' => $q,
        ];
        
        return view('meetings.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $meeting = new Meeting;
        $now = new Carbon;
        $meeting->occurred_on = $now;
        
        $data = [
            'isEdit' => false,
            'meeting' => $meeting,
            'types' => MeetingType::getInstances(),
        ];
        return view('meetings.create-edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\MeetingPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeetingPostRequest $request)
    {
        $meeting = new Meeting;
        $meeting->name = $request->name;
        $meeting->venue = $request->venue;
        $meeting->description = $request->description;
        
        $occurredOn = new Carbon($request->occurred_on, 'America/Toronto');
        $meeting->occurred_on = $occurredOn;
        if ($request->occurred_on_time) {
            $parts = explode(':', $request->occurred_on_time);
            $occurredOn->set('hour', intval($parts[0]));
            $occurredOn->set('minute', intval($parts[1]));
            $meeting->occurred_on_datetime = $occurredOn;
        }
        
        $meeting->type = $request->type;
        $meeting->save();
        
        return redirect()
            ->route('meetings.show', ['meeting' => $meeting->id])
            ->with('status', __('New meeting saved.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $meeting)
    {
        return view('meetings.show')->with(['meeting' => $meeting]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
    {
        $data = [
            'isEdit' => true,
            'meeting' => $meeting,
            'types' => MeetingType::getInstances(),
        ];
        return view('meetings.create-edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meeting $meeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        //
    }
}
