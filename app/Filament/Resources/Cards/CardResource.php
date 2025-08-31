<?php declare(strict_types = 1);

namespace App\Filament\Resources\Cards;

use App\Filament\Resources\Cards\Pages\ManageCards;
use App\Models\Card;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $recordTitleAttribute = 'Cards';

    /**
     * @throws \Exception
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                ColorPicker::make('color'),
                TextInput::make('limit')->prefix('R$')->numeric(),
                TextInput::make('used')->prefix('R$')->numeric(),
                Radio::make('type')->options(['Credit', 'Debit'])->default('type')
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Cards')
            ->columns([
                ColorColumn::make('color')->copyable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')->badge(),
                TextColumn::make('limit')->money('BRL', locale: 'pt-BR'),
                TextColumn::make('used')->money('BRL', locale: 'pt-BR')
            ])
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
}
