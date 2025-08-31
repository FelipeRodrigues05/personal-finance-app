<?php declare(strict_types = 1);

namespace App\Livewire;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

final class ExpenseCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Expense Category Chart';

    protected int|string|array $columnSpan = '1/2';

    protected function getData(): array
    {
        $startDate = isset($this->pageFilters['startDate']) ? Carbon::parse($this->pageFilters['startDate']) : now()->startOfMonth();
        $endDate   = isset($this->pageFilters['endDate']) ? Carbon::parse($this->pageFilters['endDate']) : now();

        $categoryData = Transaction::query()
            ->fromUser() // Assuming this is a scope
            ->with('category') // Eager load the category relationship
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get()
            ->groupBy('category.name')
            ->map(function (Collection $transactions, $categoryName) {
                $category = $transactions->first()->category;

                return [
                    'total' => $transactions->sum('value'),
                    'color' => $category ? $category->color ?? '#808080' : '#808080', // Default gray if no category
                ];
            });

        $totalSum = $categoryData->sum('total');

        if ($totalSum <= 0) {
            return [
                'datasets' => [
                    [
                        'label'           => 'Transaction Amounts',
                        'data'            => [1], // Placeholder data to force render
                        'backgroundColor' => ['rgba(150, 150, 150, 0.8)'], // Gray for no data
                        'borderColor'     => ['rgba(150, 150, 150, 1)'],
                        'borderWidth'     => 1,
                    ],
                ],
                'labels' => ['No Data'],
            ];
        }

        $labels           = $categoryData->keys();
        $data             = $categoryData->pluck('total')->values();
        $backgroundColors = $categoryData->pluck('color')->map(fn ($color) => $color . '80')
            ->values();
        $borderColors = $categoryData->pluck('color')->values();

        return [
            'datasets' => [
                [
                    'label'           => 'Transaction Amounts',
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
}
