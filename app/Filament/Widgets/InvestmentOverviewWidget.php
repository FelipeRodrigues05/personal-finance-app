<?php declare(strict_types = 1);

namespace App\Filament\Widgets;

use App\Models\Investment;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Number;

final class InvestmentOverviewWidget extends StatsOverviewWidget
{
    protected int | array | null $columns = 2;

    protected function getStats(): array
    {
        $investments = Investment::query()->fromUser()->get();

        return [
            StatsOverviewWidget\Stat::make(__('Total of Investments'), $investments->count()),
            StatsOverviewWidget\Stat::make(__('Total Invested'), Number::currency($investments->sum('amount'), 'BRL', 'pt_BR')),
        ];
    }
}
