<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer' => 'array|required_array_keys:name,address,phone,email',
            'customer.name' => 'required',
            'customer.address' => 'required',
            'customer.phone' => 'required|unique:customers,phone',
            'customer.email' => 'required|email|unique:customers,email',

            'supplier' => 'array|required_array_keys:name,address,phone,email',
            'supplier.name' => 'required',
            'supplier.address' => 'required',
            'supplier.phone' => 'required|unique:customers,phone',
            'supplier.email' => 'required|email|unique:customers,email',

            'products' => 'array',
            'products.*' => 'array|required_array_keys:name,description,price,quantity',
            'products.*.name' => 'required|unique:products,name',
            'products.*.image' => 'nullable|image|max:2048',
            'products.*.description' => 'nullable',
            'products.*.price' => 'required|integer|min:0',
            'products.*.quantity' => 'required|integer|min:0',

            'order_details' => 'array',
            'order_details.*' => 'array|required_array_keys:quantity',
            'order_details.*.quantity' => 'required|integer|min:0|lte:products.*.quantity',
        ];
        
    }

    public function attributes() {
        return [
            'customer.name' => 'customer name', 
            'customer.address' => 'customer address', 
            'customer.phone' => 'customer phone', 
            'customer.email' => 'customer email', 

            'supplier.name' => 'supplier name', 
            'supplier.address' => 'supplier address', 
            'supplier.phone' => 'supplier phone', 
            'supplier.email' => 'supplier email', 

            'products.*.name' => 'product name',
            'products.*.image' => 'product image',
            'products.*.description' => 'product description',
            'products.*.price' => 'product price',
            'products.*.quantity' => 'product quantity',

            'order_details.*.quantity' => 'quantity buy'
        ];
    }
}