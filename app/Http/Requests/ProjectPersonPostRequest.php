<?php

namespace App\Http\Requests;

class ProjectPersonPostRequest extends StartEndDateFormRequest
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
            'person_id.required' => __('Please select a person.'),
            'person_id.int' => __('Invalid person data.'),
            'person_id.exists' => __('Person not found.'),
        ];
        return array_merge($messages, $this->get_date_messages());
    }
}
