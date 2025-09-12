<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Luôn cho phép người dùng đã đăng nhập tạo đơn hàng
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|max:255',
            'phone_number' => 'required|string|size:10|regex:/^0[0-9]{9}$/',
            'payment_method' => 'required|in:cod,bank,momo',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'phone_number.required' => 'Vui lòng nhập số điện thoại',
            'phone_number.size' => 'Số điện thoại phải có 10 số',
            'phone_number.regex' => 'Số điện thoại không hợp lệ',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
            'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự',
        ];
    }
}
