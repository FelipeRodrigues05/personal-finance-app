<?php declare(strict_types = 1);

namespace App\Livewire;

use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class IncomeVsExpenseChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Income Vs Expense';

    protected ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        $startDate = isset($this->pageFilters['startDate']) ? Carbon::parse($this->pageFilters['startDate']) : now()->startOfMonth();
        $endDate   = isset($this->pageFilters['endDate']) ? Carbon::parse($this->pageFilters['endDate']) : now();

        $income = Trend::query(Transaction::query()->fromUser()->where('type', TransactionTypeEnum::INCOME))
            ->dateColumn('transaction_date')
            ->between($startDate, $endDate)
            ->perMonth()
            ->sum('value');

        $expense = Trend::query(Transaction::query()->fromUser()->where('type', TransactionTypeEnum::EXPENSE))
            ->dateColumn('transaction_date')
            ->between($startDate, $endDate)
            ->perMonth()
            ->sum('value');

        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data'  => $income->map(fn (TrendValue $trend) => $trend->aggregate),
                ],
                [
                    'label'           => 'Expenses',
                    'data'            => $expense->map(fn (TrendValue $trend) => $trend->aggregate),
                    'backgroundColor' => '#820202',
                    'borderColor'     => '#ff0000',
                ],
            ],
            'labels' => $income->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('M/Y')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
