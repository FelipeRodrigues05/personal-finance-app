<?php declare(strict_types = 1);

namespace App\Filament\Pages;

use App\Livewire\ExpenseCardChart;
use App\Livewire\ExpenseCategoryChart;
use App\Livewire\IncomeVsExpenseChart;
use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Schemas\Components\Actions;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

final class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersAction;
    use InteractsWithPageFilters;

    protected static string|null|BackedEnum $navigationIcon = Heroicon::Home;

    protected static ?string $recordTitleAttribute = 'Dashboard';

    /**
     * @throws Exception
     */
    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->schema([
                    DatePicker::make('startDate')->native(false),
                    DatePicker::make('endDate')->native(false),

                    Actions::make([
                        Action::make('clear')
                            ->label('Clear Filter')
                            ->color('gray')
                            ->action(function (array $data, callable $set) {
                                $set('startDate', null);
                                $set('endDate', now()->endOfMonth());
                                $this->dispatch('refresh');
                            }),
                    ])->alignRight(),
                ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            IncomeVsExpenseChart::class,
            ExpenseCardChart::class,
            ExpenseCategoryChart::class,
        ];
    }
}
