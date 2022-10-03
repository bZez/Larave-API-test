<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCdrRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->isJson();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'max:36'],
            'evse_uid' => ['required', 'string', 'max:36'],
            'start_datetime' => ['required', 'date', 'date_format:Y-m-d\TH:i:s\Z'],
            'end_datetime' => ['required', 'date', 'date_format:Y-m-d\TH:i:s\Z'],
            'total_energy' => ['required', 'numeric', 'min:0'],
            'total_cost' => ['required', 'numeric', 'min:0'],
        ];
    }
}
