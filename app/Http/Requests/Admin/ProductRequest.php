<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ];

        // Nếu là cập nhật, bỏ qua unique cho slug của sản phẩm hiện tại
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['slug'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($this->product->id),
            ];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'slug.required' => 'Vui lòng nhập đường dẫn SEO',
            'slug.unique' => 'Đường dẫn SEO đã tồn tại',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'image.image' => 'File tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ];
    }
}
