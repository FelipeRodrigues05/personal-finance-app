<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\Actions\CreateTransaction;
use App\Filament\Resources\Transactions\TransactionResource;
use Exception;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

final class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;
    public function getHeading(): string
    {
        return __('Your transactions');
    }

    public function getSubheading(): string
    {
        return __('Keep track of all your income and expenses');
    }

    public function getTabs(): array
    {
        return [
            'all'          => Tab::make('All Transactions')->icon(Heroicon::ListBullet),
            'income'       => Tab::make('Income')->icon(Heroicon::ArrowTrendingUp)->modifyQueryUsing(fn ($query) => $query->where('type', TransactionTypeEnum::INCOME)),
            'expense'      => Tab::make('Expense')->icon(Heroicon::ArrowTrendingDown)->modifyQueryUsing(fn ($query) => $query->where('type', TransactionTypeEnum::EXPENSE)),
            'is_recurrent' => Tab::make('Recurrents')->icon(Heroicon::Clock)->modifyQueryUsing(fn ($query) => $query->where('is_recurrent', true)),
        ];
    }

    /**
     * @throws Exception
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateTransaction::make(),
        ];
    }
}
