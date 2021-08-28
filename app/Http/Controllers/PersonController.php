<?php

namespace App\Http\Controllers;

use App\Models\Org;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $data = [
            'isEdit' => false,
            'person' => $person,
            'cityOptions' => get_all_cities(),
            'provinceOptions' => get_all_provinces(),
            'countryOptions' => get_all_countries(),
        ];
        return view('people.create-edit')->with($data);
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
        ]);
        
        $person = new Person;
        $person->created_by_user_id = auth()->user()->id;
        $person->given_name = $request->given_name;
        $person->family_name = $request->family_name;
        $person->notes = $request->notes;
        $person->street_address = $request->street_address;
        $person->street_address_2 = $request->street_address_2;
        $person->city = $request->city;
        $person->province = $request->province;
        $person->postal_code = $request->postal_code;
        $person->country = $request->country;
        $person->website = $request->website;
        $person->phone = $request->phone;
        $person->email = $request->email;
        $person->save();
        
        return redirect()
            ->route('people.show', ['person' => $person->id])
            ->with('status', __('New person saved.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $person->load('positions.org');
        // $person->org_names;
        return view('people.show')->with(['person' => $person]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        $data = [
            'isEdit' => true,
            'person' => $person,
            'cityOptions' => get_all_cities(),
            'provinceOptions' => get_all_provinces(),
            'countryOptions' => get_all_countries(),
        ];
        return view('people.create-edit')->with($data);
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
        ]);
        
        $person->given_name = $request->given_name;
        $person->family_name = $request->family_name;
        $person->notes = $request->notes;
        $person->street_address = $request->street_address;
        $person->street_address_2 = $request->street_address_2;
        $person->city = $request->city;
        $person->province = $request->province;
        $person->postal_code = $request->postal_code;
        $person->country = $request->country;
        $person->website = $request->website;
        $person->phone = $request->phone;
        $person->email = $request->email;
        $person->save();
        
        return redirect()
            ->route('people.show', ['person' => $person->id])
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

    /**
     * Search people.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function api_search(Request $request)
    {
        $people = Person::search($request->search)->get();
        return response()->json($people);
    }
}
