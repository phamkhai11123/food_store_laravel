<?php

namespace App\Console\Commands;

use App\Models\IngredientImport;
use Illuminate\Console\Command;

class FixImportTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-import-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $imports = IngredientImport::with('details.ingredient')->get();

        foreach ($imports as $import) {
            $adjustedTotal = 0;

            foreach ($import->details as $detail) {
                $ingredient = $detail->ingredient;
                if (!$ingredient) continue;

                $qty = $detail->quantity;
                $unit = $ingredient->base_unit;

                if (in_array($unit, ['g', 'ml'])) {
                    $qty = $qty / 1000;
                }

                $adjustedTotal += $qty * $detail->unit_price;
            }

            $import->update(['total_cost' => $adjustedTotal]);
            $import->save();
        }

        $this->info('✅ Đã cập nhật lại toàn bộ tổng tiền nhập hàng!');

    }
}
