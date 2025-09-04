<?php

namespace App\Filament\Resources\Transactions;

use App\Actions\Transaction\UpdateTransactionAction;
use App\Enums\TransactionTypeEnum;
use App\Filament\Exports\TransactionExporter;
use App\Filament\Imports\TransactionImporter;
use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Filament\Resources\Transactions\Pages\ViewTransaction;
use App\Filament\Resources\Transactions\Schemas\TransactionSchema;
use App\Models\Card;
use App\Models\Category;
use App\Models\Transaction;
use BackedEnum;
use Exception;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $recordTitleAttribute = 'Transaction';

    public static function form(Schema $schema): Schema
    {
        return TransactionSchema::configure($schema);
    }
    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->recordTitleAttribute('Transaction')
            ->columns(self::getColumns())
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')->native(false),
            ])
            ->recordActions([
                EditAction::make()->action(fn (array $data) => UpdateTransactionAction::handle(collect($data))),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                ExportBulkAction::make()
                    ->exporter(TransactionExporter::class)
            ])->headerActions([
//                ImportAction::make()
//                    ->importer(TransactionImporter::class),
//                ExportAction::make()
//                    ->exporter(TransactionExporter::class),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTransactions::route('/'),
            'view' => ViewTransaction::route('/{record}'),
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
     * @throws Exception
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
                })->searchable()->placeholder('-'),

            TextColumn::make('card.name')->searchable()->fontFamily(FontFamily::Mono)->weight(FontWeight::Medium)->icon(Heroicon::CreditCard)->placeholder('-'),

            TextColumn::make('category.name')->badge(),

            TextColumn::make('transaction_date')->dateTime('M j, Y')->sortable()->searchable(),

            IconColumn::make('is_recurrent')->boolean()

        ];
    }
}
