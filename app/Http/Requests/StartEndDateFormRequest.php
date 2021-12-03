<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StartEndDateFormRequest extends FormRequest
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
     * Returns a set of validation rules for start/end dates
     *
     * @return Array
     */
    public function get_date_rules() {
        $maxYear = get_max_year();
        $minYear = get_min_year();
        return [
            'start_year' => 'required_with:start_month|nullable|int|min:'.$minYear.'|max:'.$maxYear,
            'start_month' => 'required_with:start_day|nullable|int|min:1|max:12',
            'start_day' => 'nullable|int|min:1|max:31',
            'end_date' => 'after:start_date',
            'end_year' => 'required_with:end_month|nullable|int|min:'.$minYear.'|max:'.$maxYear,
            'end_month' => 'required_with:end_day|nullable|int|min:1|max:12',
            'end_day' => 'nullable|int|min:1|max:31',
        ];
    }
    
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $startDate = Project::getStartDate($this);
        $endDate = Project::getEndDate($this);
        
        $this->merge([
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
        ]);
    }
    
    /**
     * Get the error messages for start/end date rules.
     *
     * @return array
     */
    public function get_date_messages() {
        return [
            'end_date.after' => __('End date must be later than start date.'),
        ];
    }
}
