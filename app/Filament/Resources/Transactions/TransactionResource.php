<?php

namespace App\Filament\Resources\Transactions;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\Pages\ManageTransactions;
use App\Filament\Resources\Transactions\Pages\ViewTransaction;
use App\Filament\Schemas\TransactionSchema;
use App\Models\Category;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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
                SelectFilter::make('category')
                    ->relationship('category', 'name')->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->schema([
                    Section::make('Transaction Receipt')
                        ->schema([
                            FileUpload::make('image_path')
                                ->disk('s3')
                                ->visibility('public')
                                ->previewable()
                                ->live()
                        ]),
                    Section::make('Transaction Information')
                        ->schema([
                            TextInput::make('value'),
                            DatePicker::make('transaction_date'),
                            Select::make('type')->options([
                                "income" => "Income",
                                "expense" => "Expense",
                            ])->native(false),
                            Select::make('category')
                                ->options(Category::query()->fromUser()->pluck('name'))
                                ->native(false),
                        ]),
                    Section::make('Transaction Description')
                        ->schema([
                            MarkdownEditor::make('description'),
                        ])
                ]),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
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

            TextColumn::make('transaction_date')->dateTime('M j, Y')->sortable(),

        ];
    }
}
