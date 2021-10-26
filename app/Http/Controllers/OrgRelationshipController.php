<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrgRelationshipPostRequest;
use App\Models\Org;
use App\Models\OrgRelationship;
use Illuminate\Http\Request;

class OrgRelationshipController extends Controller
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
        $relationship = new OrgRelationship();
        
        $redirectUrl = null;
        
        $parentOrg = null;
        $parentOrgID = data_get($_GET, 'parent');
        if (!!$parentOrgID) {
            $parentOrg = Org::find($parentOrgID);
            if ($parentOrg) $redirectUrl = route('orgs.show', ['org' => $parentOrg->id]);
        }
        
        $childOrg = null;
        $childOrgID = data_get($_GET, 'child');
        if (!!$childOrgID) {
            $childOrg = Org::find($childOrgID);
            if ($childOrg) $redirectUrl = route('orgs.show', ['org' => $childOrg->id]);
        }

        $data = [
            'isEdit' => false,
            'relationship' => $relationship,
            'parentOrg' => $parentOrg,
            'childOrg' => $childOrg,
            'redirectUrl' => $redirectUrl,
        ];

        return view('org_relationships.create-edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrgRelationshipPostRequest $request)
    {
        $relationship = new OrgRelationship;
        $relationship->parent_org_id = $request->parent_org_id;
        $relationship->child_org_id = $request->child_org_id;
        $relationship->child_description = $request->child_description;
        $relationship->start_year = $request->start_year;
        $relationship->start_month = $request->start_month;
        $relationship->start_day = $request->start_day;
        $relationship->end_year = $request->end_year;
        $relationship->end_month = $request->end_month;
        $relationship->end_day = $request->end_day;
        $relationship->notes = $request->notes;
        $relationship->save();
        
        $redirectUrl = route('orgs.show', ['org' => $request->parent_org_id]);
        $requestRedirectUrl = $request->redirect_url;
        if (is_this_domain($requestRedirectUrl)) $redirectUrl = $requestRedirectUrl;
        
        return redirect($redirectUrl)
            ->with('status', __('Position created.'));
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\OrgRelationship  $orgRelationship
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(OrgRelationship $orgRelationship)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrgRelationship  $orgRelationship
     * @return \Illuminate\Http\Response
     */
    public function edit(OrgRelationship $orgRelationship)
    {
        $redirectUrl = null;
        $parentOrgID = data_get($_GET, 'parent');
        $childOrgID = data_get($_GET, 'child');
        if (!!$parentOrgID && $parentOrgID === $orgRelationship->org_id) {
            $redirectUrl = route('orgs.show', ['org' => $parentOrgID]);
        } elseif (!!$childOrgID && $childOrgID === $orgRelationship->person_id) {
            $redirectUrl = route('orgs.show', ['org' => $childOrgID]);
        }
        
        $data = [
            'isEdit' => true,
            'position' => $orgRelationship,
            'parentOrg' => $orgRelationship->parent_org,
            'childOrg' => $orgRelationship->child_org,
            'redirectUrl' => $redirectUrl,
        ];

        return view('positions.create-edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrgRelationship  $orgRelationship
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrgRelationship $orgRelationship)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrgRelationship  $orgRelationship
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrgRelationship $orgRelationship)
    {
        //
    }
}
