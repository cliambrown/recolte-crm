<?php

namespace App\Http\Controllers;

use App\Models\Org;
use App\Models\OrgType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        // $org->city = 'Montréal';
        $org->province = 'Québec';
        $org->country = 'Canada';
        
        $orgTypes = OrgType::all();
        
        $data = [
            'isEdit' => false,
            'org' => $org,
            'cityOptions' => get_all_cities(),
            'provinceOptions' => get_all_provinces(),
            'countryOptions' => get_all_countries(),
            'orgTypes' => $orgTypes,
        ];
        return view('orgs.create-edit')->with($data);
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
            'short_name' => 'nullable|string',
            'street_address' => 'nullable|string',
            'street_address_2' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => [
                'nullable',
                'string',
                Rule::in(get_all_countries()),
            ],
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
            'type_ids' => 'nullable|array',
            'type_ids.*' => 'integer|exists:org_types,id',
        ]);
        
        $org = new Org;
        $org->created_by_user_id = auth()->user()->id;
        $org->name = $request->name;
        $org->short_name = $request->short_name;
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
        
        $org->types()->sync($request->type_ids);
        
        return redirect()
            ->route('orgs.show', ['org' => $org->id])
            ->with('status', __('New org saved.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function show(Org $org)
    {
        $org->load('types','people');
        return view('orgs.show')->with(['org' => $org]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function edit(Org $org)
    {
        $orgTypes = OrgType::all();
        $data = [
            'isEdit' => true,
            'org' => $org,
            'cityOptions' => get_all_cities(),
            'provinceOptions' => get_all_provinces(),
            'countryOptions' => get_all_countries(),
            'orgTypes' => $orgTypes,
        ];
        return view('orgs.create-edit')->with($data);
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
            'short_name' => 'nullable|string',
            'street_address' => 'nullable|string',
            'street_address_2' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => [
                'nullable',
                'string',
                Rule::in(get_all_countries()),
            ],
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
            'type_ids' => 'nullable|array',
            'type_ids.*' => 'integer|exists:org_types,id',
        ]);
        
        $org->created_by_user_id = auth()->user()->id;
        $org->name = $request->name;
        $org->short_name = $request->short_name;
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
        
        $org->types()->sync($request->type_ids);
        
        return redirect()
            ->route('orgs.show', ['org' => $org->id])
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

    /**
     * Search orgs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function api_search(Request $request)
    {
        $orgs = Org::search($request->search)->get();
        return response()->json($orgs);
    }
}
