<?php

namespace App\Http\Requests;

use App\Models\OrgRelationship;
use Illuminate\Foundation\Http\FormRequest;

class OrgRelationshipPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $maxYear = get_max_year();
        $minYear = get_min_year();
        
        return [
            'parent_org_id' => 'required|int|different:child_org_id|exists:orgs,id',
            'child_org_id' => 'required|int|different:parent_org_id|exists:orgs,id',
            'child_description' => 'nullable|string',
            'start_year' => 'required_with:start_month|nullable|int|min:'.$minYear.'|max:'.$maxYear,
            'start_month' => 'required_with:start_day|nullable|int|min:1|max:12',
            'start_day' => 'nullable|int|min:1|max:31',
            'end_date' => 'after:start_date',
            'end_year' => 'required_with:end_month|nullable|int|min:'.$minYear.'|max:'.$maxYear,
            'end_month' => 'required_with:end_day|nullable|int|min:1|max:12',
            'end_day' => 'nullable|int|min:1|max:31',
            'notes' => 'nullable|string',
        ];
    }
    
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        
        $startDate = OrgRelationship::getStartDate($this);
        $endDate = OrgRelationship::getEndDate($this);
        
        $this->merge([
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
        ]);
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'parent_org_id.required' => __('Please select a parent org.'),
            'parent_org_id.int' => __('Invalid parent org data.'),
            'parent_org_id.different' => __('Parent org must be different from child org.'),
            'parent_org_id.exists' => __('Parent org not found.'),
            'child_org_id.required' => __('Please select a child org.'),
            'child_org_id.int' => __('Invalid child org data.'),
            'child_org_id.different' => __('Child org must be different from child org.'),
            'child_org_id.exists' => __('Child org not found.'),
            'end_date.after' => __('End date must be later than start date.'),
        ];
    }
}
