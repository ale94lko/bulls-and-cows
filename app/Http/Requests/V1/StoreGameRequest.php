<?php

namespace app\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user'=> ['required'],
            'age'=> ['required'],
            'max_time'=> ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'max_time' => $this->maxTime,
            'secret_number' => $this->secretNumber,
        ]);
    }
}
