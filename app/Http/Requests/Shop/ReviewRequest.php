<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kiểm tra xem đơn hàng đã giao hàng và thuộc về người dùng hiện tại
        $user = $this->user();
        $orderItemId = $this->route('orderItem');

        return $user->orders()
            ->where('status', 'completed')
            ->whereHas('orderItems', function ($query) use ($orderItemId) {
                $query->where('id', $orderItemId);
            })
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
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
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.integer' => 'Số sao đánh giá phải là số nguyên',
            'rating.min' => 'Số sao đánh giá phải lớn hơn hoặc bằng 1',
            'rating.max' => 'Số sao đánh giá phải nhỏ hơn hoặc bằng 5',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự',
        ];
    }
}
