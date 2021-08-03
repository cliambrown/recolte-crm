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
        $org->city = 'Montréal';
        $org->province = 'Québec';
        $org->country = 'Canada';
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
            'street_address' => 'nullable|string',
            'street_address_2' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'website' => 'nullable|string|url',
            'phone' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (gettype($value) !== 'string') return true;
                    $value = trim($value);
                    if (!$value) return true;
                    $phoneObj = get_valid_phone_obj($value);
                    if ($phoneObj === null) {
                        $fail(__('Invalid phone number'));
                    }
                },
            ],
            'email' => 'nullable|string|email',
            'notes' => 'nullable|string',
        ]);
        
        $org = new Org;
        $org->created_by_user_id = auth()->user()->id;
        $org->name = $request->name;
        $org->street_address = $request->street_address;
        $org->street_address_2 = $request->street_address_2;
        $org->city = $request->city;
        $org->province = $request->province;
        $org->postal_code = $request->postal_code;
        $org->country = $request->country;
        $org->website = $request->website;
        $org->phone = $request->phone;
        $org->email = $request->email;
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
        return view('orgs.create-edit')->with(['isEdit' => true, 'org' => $org]);
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
        $request->validate([
            'name' => 'required|string',
            'street_address' => 'nullable|string',
            'street_address_2' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'website' => 'nullable|string|url',
            'phone' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (gettype($value) !== 'string') return true;
                    $value = trim($value);
                    if (!$value) return true;
                    $phoneObj = get_valid_phone_obj($value);
                    if ($phoneObj === null) {
                        $fail(__('Invalid phone number'));
                    }
                },
            ],
            'email' => 'nullable|string|email',
            'notes' => 'nullable|string',
        ]);
        
        $org = new Org;
        $org->created_by_user_id = auth()->user()->id;
        $org->name = $request->name;
        $org->street_address = $request->street_address;
        $org->street_address_2 = $request->street_address_2;
        $org->city = $request->city;
        $org->province = $request->province;
        $org->postal_code = $request->postal_code;
        $org->country = $request->country;
        $org->website = $request->website;
        $org->phone = $request->phone;
        $org->email = $request->email;
        $org->notes = $request->notes;
        $org->save();
        
        return redirect()
            ->route('orgs.edit', ['org' => $org->id])
            ->with('status', __('Org updated.'));
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
