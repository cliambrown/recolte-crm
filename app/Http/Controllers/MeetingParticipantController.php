<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingParticipantController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function api_store(Request $request)
    {
        $request->validate([
            'meeting_id' => 'required|integer|exists:meetings,id',
            'person_id' => 'required|integer|exists:people,id',
        ]);
        
        $participant = MeetingParticipant::firstOrCreate([
            'meeting_id' => $request->meeting_id,
            'person_id' => $request->person_id,
        ]);
        
        if (!$participant->wasRecentlyCreated) {
            return response()->json([
                'success' =>false,
                'message' => __('This person is already a participant of this meeting.'),
            ]);
        }
        
        $person = Person::where('id', $request->person_id)
            ->with('current_position.org')
            ->first();
        
        return response()->json([
            'success' =>true,
            'participant' => $participant,
            'person' => $person,
        ]);
    }
    
    public function api_remove_org(Request $request) {
        $request->validate([
            'participant_id' => 'required|integer',
            'org_id' => 'required|integer',
        ]);
        
        DB::table('meeting_participant_org')
            ->where('meeting_participant_id', $request->participant_id)
            ->where('org_id', $request->org_id)
            ->delete();
        
        return response()->json([
            'success' =>true,
        ]);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\MeetingParticipant  $meetingParticipant
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(MeetingParticipant $meetingParticipant)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  \App\Models\MeetingParticipant  $meetingParticipant
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit(MeetingParticipant $meetingParticipant)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\MeetingParticipant  $meetingParticipant
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, MeetingParticipant $meetingParticipant)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\MeetingParticipant  $meetingParticipant
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(MeetingParticipant $meetingParticipant)
    // {
    //     //
    // }
}
