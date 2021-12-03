<?php

namespace App\Http\Requests;

class OrgRelationshipPostRequest extends StartEndDateFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'parent_org_id' => 'required|int|different:child_org_id|exists:orgs,id',
            'child_org_id' => 'required|int|different:parent_org_id|exists:orgs,id',
            'child_description' => 'nullable|string',
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
            'parent_org_id.required' => __('Please select a parent org.'),
            'parent_org_id.int' => __('Invalid parent org data.'),
            'parent_org_id.different' => __('Parent org must be different from child org.'),
            'parent_org_id.exists' => __('Parent org not found.'),
            'child_org_id.required' => __('Please select a child org.'),
            'child_org_id.int' => __('Invalid child org data.'),
            'child_org_id.different' => __('Child org must be different from child org.'),
            'child_org_id.exists' => __('Child org not found.'),
        ];
        return array_merge($messages, $this->get_date_messages());
    }
}
