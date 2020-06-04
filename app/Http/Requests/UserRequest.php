<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $current_path = url()->current();

        if($current_path === url('api/auth/login'))
        {
            $validation = $this->getLoginValidationList();

            return $validation;
        }
        elseif($current_path === url('api/auth/register'))
        {
            $validation = $this->getRegistrationValidationList();

            return $validation;
        }
        elseif($current_path === url('api/auth/updateUserProfile'))
        {
            $validation = $this->getUpdateProfileValidationList();

            return $validation;
        }
        elseif($current_path === url('api/storeFreeUser'))
        {
            $validation = $this->getFreeUserValidationList();

            return $validation;
        }

        return $validation;
    }

    public function getRegistrationValidationList()
    {
      return
      [
        'nickname'                   => 'required|min:3|max:50|string',

        'fullname'                   => 'required|min:3|max:50|string',

        'email'                      => 'email|required|max:50|unique:users',

        'password'                   => 'required|min:8',

        'password_confirmation'      => 'required|min:8|same:password',

        'borndate'                   => 'required|date',

        'sex'                        => 'required|in:Male,Female',

        'photo'                      => 'nullable|string',

        'course'                     => 'required|string|max:50',

        'state'                      => 'required|string|max:50',

        'city'                       => 'required|string|max:50',

        'verifyToken'                => 'required|string|max:8',

        'isVerify'                   => 'required|numeric|max:1',
      ];
    }

    public function getFreeUserValidationList()
    {
      return
      [

        'fullname'                   => 'required|min:3|max:50|string',

        'email'                      => 'email|required|max:50|unique:App\FreeUser',

        'borndate'                   => 'required|date',

        'sex'                        => 'required|in:Male,Female',

        'verifyToken'                => 'required|string|max:8',

        'isVerify'                   => 'required|numeric|max:1',

      ];
    }

    public function getUpdateProfileValidationList()
    {
      $userId = $this->input('userId');
      
      return
      [

        'nickname'                   => 'required|min:3|max:50|string',

        'fullname'                   => 'required|min:3|max:50|string',

        'email'                      => 'email|required|max:50|'.Rule::unique('users')->ignore($userId, 'userId'),

        'password'                   => 'nullable|min:8',

        'password_confirmation'      => 'nullable|min:8|same:password',

        'borndate'                   => 'required|date',

        'sex'                        => 'required|in:Male,Female',

        'photo'                      => 'nullable|string',

        'course'                     => 'required|string|max:50',

        'state'                      => 'required|string|max:50',

        'city'                       => 'required|string|max:50',

      ];
    }

    public function getLoginValidationList()
    {
      return
      [

        'email'                      => 'email|required|max:50',

        'password'                   => 'required|min:8',

      ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
