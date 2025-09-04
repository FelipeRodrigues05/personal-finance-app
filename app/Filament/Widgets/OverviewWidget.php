<?php declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

final class OverviewWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = isset($this->pageFilters['startDate']) ? Carbon::parse($this->pageFilters['startDate']) : now()->startOfMonth();
        $endDate   = isset($this->pageFilters['endDate']) ? Carbon::parse($this->pageFilters['endDate']) : now();

        return [
            Stat::make('Spent', Number::currency($this->calculateExpenses($startDate, $endDate), 'BRL',
                'pt-BR'))
                ->description('Total Spent'),

            Stat::make('Income', Number::currency($this->calculateIncome($startDate, $endDate), 'BRL', 'pt-BR'))
                ->description('Total Income'),

            Stat::make('Net Balance', Number::currency($this->calculateIncome($startDate, $endDate) - $this->calculateExpenses($startDate, $endDate), 'BRL', 'pt-BR'))
                ->description('Total of earnings (gain - expense)'),

            Stat::make('Total Transactions', $this->getTotalTransactions($startDate, $endDate)),

            Stat::make('Number of Expenses', $this->getTotalExpenses($startDate, $endDate)),

            Stat::make('Number of Incomes', $this->getTotalIncomes($startDate, $endDate)),
        ];
    }

    private function calculateExpenses(Carbon $startDate, Carbon $endDate): false|float
    {
        return (float) Transaction::query()->fromUser()->where('type', TransactionTypeEnum::EXPENSE)->whereBetween('transaction_date', [$startDate, $endDate])->sum('value');
    }

    private function calculateIncome(Carbon $startDate, Carbon $endDate): false|float
    {
        return (float) Transaction::query()->fromUser()->where('type', TransactionTypeEnum::INCOME)->whereBetween('transaction_date', [$startDate, $endDate])->sum('value');
    }

    private function getTotalTransactions(Carbon $startDate, Carbon $endDate): float
    {
        return Transaction::query()->fromUser()->whereBetween('transaction_date', [$startDate, $endDate])->count();
    }

    private function getTotalExpenses(Carbon $startDate, Carbon $endDate): float
    {
        return Transaction::query()->fromUser()->where('type', TransactionTypeEnum::EXPENSE)->whereBetween('transaction_date', [$startDate, $endDate])->count();
    }

    private function getTotalIncomes(Carbon $startDate, Carbon $endDate): float
    {
        return Transaction::query()->fromUser()->where('type', TransactionTypeEnum::INCOME)->whereBetween('transaction_date', [$startDate, $endDate])->count();
    }
}
