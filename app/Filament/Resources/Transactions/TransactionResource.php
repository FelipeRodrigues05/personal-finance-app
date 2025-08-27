<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->recordTitleAttribute('Transaction')
            ->columns(self::getColumns())
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'CREDIT' => 'Credit',
                        'DEBIT'  => 'Debit',
                    ]),
                SelectFilter::make('category')
                    ->relationship('category', 'name')->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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

    private static function getColumns(): array
    {
        return [
            TextColumn::make('user.name')->weight(FontWeight::Bold),

            TextColumn::make('value')->money('BRL', locale: 'pt-BR')->sortable()
                ->color(fn ($record): string => match ($record->type) {
                    TransactionTypeEnum::CREDIT => 'danger',
                    TransactionTypeEnum::DEBIT  => 'success',
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

            TextColumn::make('when')->dateTime('M j, Y H:i:s')->sortable(),

        ];
    }
}
