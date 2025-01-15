<?php

namespace App\Http\Requests\Api\Order;

use App\Response\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends ApiRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'orders' => 'required|array',
            'orders.*.product_id' => 'required|exists:products,id',
            'orders.*.quantity' => 'required|numeric',
            'orders.*.price' => 'required|numeric',
        ];
    }
}
