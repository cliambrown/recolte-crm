<?php

namespace App\Http\Requests;

class ProjectPostRequest extends StartEndDateFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string',
            'short_name' => 'nullable|string',
            'description' => 'nullable|string',
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
        return $this->get_date_messages();
    }
}
