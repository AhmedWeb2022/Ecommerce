<?php

namespace App\Response;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

use function response;

abstract class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    abstract public function rules();



    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        // if( !empty($errors) ) {
        //     $transformedErrors = [];
        //     foreach ($errors as $field => $message) {
        //         $transformedErrors[] = [
        //             $field => $message[0]
        //         ];
        //     }

        throw new HttpResponseException(
            response()->json(
                [
                    'status' => false,
                    'message' => $validator->errors()->first()
                ],
                JsonResponse::HTTP_BAD_REQUEST
            )
        );
    }

    public function messages()
    {
        return [
            'orders.required' => 'The orders field is required.',
            'orders.array' => 'The orders field must be an array.',
            'orders.product_id.required' => 'The product_id field is required.',
            'orders.product_id.exists' => 'The selected product_id is invalid.',
            'orders.quantity.required' => 'The quantity field is required.',
            'orders.quantity.numeric' => 'The quantity field must be a number.',
            'orders.price.required' => 'The price field is required.',
            'orders.price.numeric' => 'The price field must be a number.',
            'id.required' => 'The id field is required.',
            'id.exists' => 'The selected id is invalid.',
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name is too long',
            'email.required' => 'Email is required',
            'email.string' => 'Email must be a string',
            'email.email' => 'Email is invalid',
            'email.max' => 'Email is too long',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password is too short',
            'order_id.required' => 'The order_id field is required.',
            'order_id.exists' => 'The selected order_id is invalid.',
            'product_id.required' => 'The product_id field is required.',
            'product_id.exists' => 'The selected product_id is invalid.',
            'ammount.required' => 'The ammount field is required.',
            'ammount.numeric' => 'The ammount field must be a number.',

        ];
    }
}
