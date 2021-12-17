<?php

namespace App\Http\Requests;

use App\Enums\MeetingType;
use Illuminate\Foundation\Http\FormRequest;
use BenSampo\Enum\Rules\EnumValue;

class MeetingPostRequest extends FormRequest
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
        return [
            'name' => 'required|string',
            'venue' => 'nullable|string',
            'description' => 'nullable|string',
            'occurred_on' => 'date_format:Y-m-d',
            'occurred_on_time' => 'nullable|date_format:H:i',
            'type' => ['required', new EnumValue(MeetingType::class)],
        ];
    }
    
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'type' => intval($this->type),
        ]);
    }
    
    /**
     * Get the error messages for start/end date rules.
     *
     * @return array
     */
    public function get_date_messages() {
        return [
            'type.after' => __('End date must be later than start date.'),
        ];
    }
}
