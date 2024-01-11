<?php

namespace app\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user != null && $user->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'user'=> ['required'],
                'age'=> ['required'],
                'max_time' => ['integer', 'max:86400', 'min:120'],
            ];
        } else {
            return [
                'user'=> ['sometimes', 'required'],
                'age'=> ['sometimes', 'required'],
                'max_time' => ['integer', 'max:86400', 'min:120'],
            ];
        }
    }

    /**
     * Prepare model for validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->maxTime) {
            $this->merge([
                'max_time' => $this->maxTime,
            ]);
        }
    }
}
