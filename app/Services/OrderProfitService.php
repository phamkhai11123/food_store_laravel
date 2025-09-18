<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderProfitService
{
    public function calculateIngredientCost(Order $order)
    {
       $totalCost = 0;

        foreach ($order->items as $item) {
            $product = $item->product;
            $product->loadMissing('recipeItems.ingredient');

            foreach ($product->recipeItems as $recipe) {
                $ingredient = $recipe->ingredient;
                $usedQty = $recipe->quantity_per_portion_base * $item->quantity;

                // Quy đổi về đơn vị chuẩn
                $conversionRate = match ($ingredient->base_unit) {
                    'g', 'ml' => 0.001,
                    'pc' => 1,
                    default => 1, // fallback nếu đơn vị không xác định
                };

                $convertedQty = $usedQty * $conversionRate;
                $costPerUnit = $ingredient->suggested_unit_cost ?? 0;

                $totalCost += $convertedQty * $costPerUnit;
            }
        }


        return $totalCost;
    }

    public function calculateProfit(Order $order)
    {
        $ingredientCost = $this->calculateIngredientCost($order);
        $shippingFee = 0;
        Log::info('Chi phí nguyên liệu:', ['cost' => $ingredientCost]);
        
        return $order->total - $ingredientCost - $shippingFee;
    }
}
