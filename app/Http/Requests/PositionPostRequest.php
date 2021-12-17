<?php

namespace App\Http\Requests;

class PositionPostRequest extends StartEndDateFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'person_id' => 'required|int|exists:people,id',
            'org_id' => 'required|int|exists:orgs,id',
            'is_current' => 'nullable|int',
            'title' => 'nullable|string',
            'email' => 'nullable|email',
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
            'notes' => 'nullable|string',
        ];
        
        return array_merge($rules, $this->get_date_rules());
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'person_id.required' => __('Please select a person.'),
            'person_id.int' => __('Invalid person data.'),
            'person_id.exists' => __('Person not found.'),
            'org_id.required' => __('Please select an org.'),
            'org_id.int' => __('Invalid org data.'),
            'org_id.exists' => __('Org not found.'),
        ];
        return array_merge($messages, $this->get_date_messages());
    }
}
