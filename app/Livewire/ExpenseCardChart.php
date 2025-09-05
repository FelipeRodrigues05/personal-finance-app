<?php declare(strict_types = 1);

namespace App\Livewire;

use App\Models\Card;
use Filament\Widgets\ChartWidget;

final class ExpenseCardChart extends ChartWidget
{
    public function getHeading(): string
    {
        return __("Cards Expenses");
    }

    protected function getData(): array
    {
        $cards = Card::query()->fromUser()->get();

        return [
            'datasets' => [
                [
                    'label'           => __('Used Limit'),
                    'data'            => $cards->pluck('used'),
                    'backgroundColor' => $cards->pluck('color')->values(),
                ],
                [
                    'label'           => __('Total Limit'),
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
