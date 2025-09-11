<?php declare(strict_types = 1);

namespace App\Livewire;

use App\Models\Card;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;

final class CardStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $cards = $this->getUserCards();

        return [
            Stat::make(__("Total Cards"), $cards->count()),
            Stat::make(__("Total Limit"), Number::currency($cards->sum('limit'), 'BRL', 'pt_BR')),
            Stat::make(__("Total Used"), Number::currency($cards->sum('used'), 'BRL', 'pt_BR')),
        ];
    }

    private function getUserCards(): Collection
    {
        return Card::query()->fromUser()->get();
    }
}
