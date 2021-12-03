<?php

namespace App\Http\Requests;

class ProjectOrgPostRequest extends StartEndDateFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'org_id' => 'required|int|exists:people,id',
            'role' => 'nullable|string',
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
            'org_id.required' => __('Please select an org.'),
            'org_id.int' => __('Invalid org data.'),
            'org_id.exists' => __('Org not found.'),
        ];
        return array_merge($messages, $this->get_date_messages());
    }
}
