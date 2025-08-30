<?php

namespace App\Filament\Resources\Transactions;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $recordTitleAttribute = 'Transaction';

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->recordTitleAttribute('Transaction')
            ->columns(self::getColumns())
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'EXPENSE' => 'Expense',
                        'INCOME' => 'Income',
                    ]),
                SelectFilter::make('category')
                    ->relationship('category', 'name')->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTransactions::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of transactions';
    }

    /**
     * @throws \Exception
     */
    private static function getColumns(): array
    {
        return [
            TextColumn::make('user.name')->weight(FontWeight::Bold),

            TextColumn::make('value')->money('BRL', locale: 'pt-BR')->sortable()
                ->color(fn($record): string => match ($record->type) {
                    TransactionTypeEnum::EXPENSE => 'danger',
                    TransactionTypeEnum::INCOME => 'success',
                }),

            TextColumn::make('description')
                ->limit(50)
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();

                    if (mb_strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }

                    return $state;
                })->searchable(),

            TextColumn::make('category.name')->badge(),

            TextColumn::make('transaction_date')->dateTime('M j, Y H:i:s')->sortable(),

        ];
    }
}
