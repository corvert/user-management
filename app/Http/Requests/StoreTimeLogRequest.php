<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreTimeLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create logs');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'log_date' => [
                'required',
                'date',
                Rule::unique('time_logs', 'log_date')->where('user_id', $userId),
            ],
            'arrival_time'   => ['nullable', 'date_format:H:i'],
            'departure_time' => ['nullable', 'date_format:H:i', 'after:arrival_time'],
        ];
    }
}
