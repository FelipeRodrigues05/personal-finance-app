<?php declare(strict_types = 1);

namespace App\Livewire;

use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Collection;

final class ExpenseCategoryChart extends ChartWidget
{
    use InteractsWithPageFilters;

    public function getHeading(): string
    {
        return __("Categories Expenses");
    }

    protected function getData(): array
    {
        $startDate = isset($this->pageFilters['startDate']) ? Carbon::parse($this->pageFilters['startDate']) : now()->startOfMonth();
        $endDate   = isset($this->pageFilters['endDate']) ? Carbon::parse($this->pageFilters['endDate']) : now();

        $categoryData = $this->fetchTransactions($startDate, $endDate);

        $totalSum = $categoryData->sum('total');

        if ($totalSum <= 0) {
            return [
                'datasets' => [
                    [
                        'data'            => [0],
                        'backgroundColor' => ['rgba(150, 150, 150, 0.8)'],
                        'borderColor'     => ['rgba(150, 150, 150, 1)'],
                    ],
                ],
                'labels' => ['No Data'],
            ];
        }

        $labels           = $categoryData->keys();
        $data             = $categoryData->pluck('total')->values();
        $backgroundColors = $categoryData->pluck('color')->map(fn ($color) => $color . '80')->values();
        $borderColors     = $categoryData->pluck('color')->values();

        return [
            'datasets' => [
                [
                    'label'           => 'Transactions Value',
                    'data'            => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor'     => $borderColors,
                    'borderWidth'     => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function fetchTransactions(Carbon $startDate, Carbon $endDate)
    {
        return Transaction::query()
            ->fromUser()
            ->with('category')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', TransactionTypeEnum::EXPENSE)
            ->get()
            ->groupBy('category.name')
            ->map(function (Collection $transactions) {
                $category = $transactions->first()->category;

                return [
                    'total' => $transactions->sum('value'),
                    'color' => $category ? $category->color ?? '#808080' : '#808080',
                ];
            });
    }
}
