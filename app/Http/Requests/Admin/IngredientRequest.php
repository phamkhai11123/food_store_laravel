<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class IngredientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'string|max:255|unique:ingredients,sku',
            'name' => 'required|string|max:255',
            'base_unit' => 'required|string|max:50',
            'track_stock' => 'required|numeric',
            'suggested_unit_cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }
    public function messages()
    {
        return [
            'sku.unique' => 'Mã SKU đã tồn tại.',
            'name.required' => 'Vui lòng nhập tên nguyên liệu.',
            'base_unit.required' => 'Vui lòng nhập đơn vị cơ bản.',
            'track_stock.required' => 'Vui lòng chọn trạng thái theo dõi tồn kho.',
            'suggested_unit_cost.numeric' => 'Giá vốn phải là số.',
        ];
    }

}
