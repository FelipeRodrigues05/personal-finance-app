<?php

namespace App\Livewire;

use App\Models\Card;
use Filament\Widgets\ChartWidget;

class ExpenseCardChart extends ChartWidget
{
    protected ?string $heading = 'Expense Card Chart';

    protected function getData(): array
    {
        $cards = Card::query()->fromUser()->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Used Limit',
                    'data'            => $cards->pluck('used'),
                    'backgroundColor' => $cards->pluck('color')->values(),
                ],
                [
                    'label'           => 'Total Limit',
                    'data'            => $cards->pluck('limit'),
                    'backgroundColor' => $cards->pluck('color')->values(),
                ],
            ],
            'labels' => $cards->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
