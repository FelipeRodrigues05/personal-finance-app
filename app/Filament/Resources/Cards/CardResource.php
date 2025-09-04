<?php declare(strict_types = 1);

namespace App\Filament\Resources\Cards;

use App\Filament\Resources\Cards\Pages\ManageCards;
use App\Filament\Resources\Cards\Schemas\CardSchema;
use App\Models\Card;
use BackedEnum;
use Exception;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

final class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $recordTitleAttribute = 'Cards';

    /**
     * @throws Exception
     */
    public static function form(Schema $schema): Schema
    {
        return CardSchema::configure($schema);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Cards')
            ->columns(self::getColumns())
            ->filters([])
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
            'index' => ManageCards::route('/'),
        ];
    }

    private static function getColumns(): array {
        return [
            ColorColumn::make('color')->label(__('Card Color'))
                ->copyable(),

            TextColumn::make('name')->label(__('Card Name'))
                ->searchable(),
            TextColumn::make('type')->label(__('Card Type'))
                ->badge(),

            TextColumn::make('limit')->label(__('Total Limit'))
                ->money('BRL', locale: 'pt-BR'),

            TextColumn::make('used')->label(__('Limit Used'))
                ->money('BRL', locale: 'pt-BR'),

            ViewColumn::make('usage')->label('Limit Usage')
                ->getStateUsing(fn (Card $record) => $record->limit > 0
                    ? round(($record->used / $record->limit) * 100)
                    : 0
                )
                ->view('tables.columns.progress-bar'),
        ];
    }
}
