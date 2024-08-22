<?php

namespace App\Http\Requests;

use App\Services\ApiResponseService;
use Dotenv\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    protected $stopOnFirstFailure =true;
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
                'email'=>'required|string|email|mix:30|unique:users,email',
                'password'=>'required|string|min:8'
        ];
    }
    protected function prepareForValidation(Validator $validator) // التحكم بشكل الخطأ
    {
        $errors= $validator->errors()->all();
        throw new \HttpRequestException(ApiResponseService::error('valedation errors',422,$errors));
    }
}
