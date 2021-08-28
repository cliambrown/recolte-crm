<?php

namespace App\Http\Requests;

use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;

class PositionPostRequest extends FormRequest
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
            'person_id' => 'required|int|exists:people,id',
            'org_id' => 'required|int|exists:orgs,id',
            'is_current' => 'nullable|int',
            'title' => 'nullable|string',
            'email' => 'nullable|email',
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
        
        $startDate = Position::getStartDate($this);
        $endDate = Position::getEndDate($this);
        
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
            'person_id.required' => __('Please select a person.'),
            'person_id.int' => __('Invalid person data.'),
            'person_id.exists' => __('Person not found.'),
            'org_id.required' => __('Please select an org.'),
            'org_id.int' => __('Invalid org data.'),
            'org_id.exists' => __('Org not found.'),
            'end_date.after' => __('End date must be later than start date.'),
        ];
    }
}
