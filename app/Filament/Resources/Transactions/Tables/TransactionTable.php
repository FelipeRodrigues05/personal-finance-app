<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Tables;

use App\Actions\Transaction\UpdateTransactionAction;
use App\Enums\TransactionTypeEnum;
use App\Filament\Exports\TransactionExporter;
use App\Models\Transaction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class TransactionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Transaction::query()->fromUser())
            ->columns(self::getColumns())
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')->native(false),
            ])
            ->recordActions([
                EditAction::make()->action(fn (array $data) => UpdateTransactionAction::handle(collect($data))),
                DeleteAction::make(),
            ])
            ->recordUrl(fn (Transaction $record) => route('filament.admin.resources.transactions.view', ['record' => $record]))
            ->toolbarActions([
                ExportBulkAction::make()
                    ->exporter(TransactionExporter::class),
            ]);
    }

    private static function getColumns(): array
    {
        return [
            TextColumn::make('user.name')->weight(FontWeight::Bold),

            TextColumn::make('value')->money('BRL', locale: 'pt-BR')->sortable()
                ->color(fn (Transaction $record): string => match ($record->type) {
                    TransactionTypeEnum::EXPENSE => 'danger',
                    TransactionTypeEnum::INCOME  => 'success',
                    default                      => 'default'
                }),

            TextColumn::make('description')
                ->limit(50)
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();

                    if (mb_strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }

                    return $state;
                })->searchable()->placeholder('-'),

            TextColumn::make('card.name')->searchable()->fontFamily(FontFamily::Mono)->weight(FontWeight::Medium)->icon(Heroicon::CreditCard)->placeholder('-'),

            TextColumn::make('category.name')->badge(),

            TextColumn::make('transaction_date')->dateTime('M j, Y')->sortable()->searchable(),

            IconColumn::make('is_recurrent')->boolean(),

        ];
    }
}
