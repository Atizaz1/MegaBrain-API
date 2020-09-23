<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserPurchaseRequest extends FormRequest
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

        if($current_path === url('api/verifyPurchase'))
        {
            $validation = $this->getUserPurchaseValidationList();

            return $validation;
        }

        return $validation;
    }

    public function getUserPurchaseValidationList()
    {
      return
      [

        'purchase_ip'                      => 'required',

        'purchase_equipment'               => 'required|regex:/^(?:[A-Za-z]+)(?:[A-Za-z0-9 ]*)$/',

        'email'                            => 'required|email',

        'code'                             => 'required|alpha_num',

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
