<?php declare(strict_types = 1);

namespace App\Filament\Resources\Cards\Pages;

use App\Actions\Card\CreateCardAction;
use App\Filament\Resources\Cards\CardResource;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;

final class ManageCards extends ManageRecords
{
    protected static string $resource = CardResource::class;

    /**
     * @throws Exception
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('add')
                ->icon(Heroicon::PlusCircle)
                ->schema(self::getForm())
                ->action(fn (array $data) => CreateCardAction::handle(collect($data)))
                ->slideOver(),
        ];
    }

    private static function getForm(): array {
        return [
            Grid::make()
                ->columns(3)
                ->schema([
                    TextInput::make('name')->label(__('Card Name'))
                        ->required(),

                    ColorPicker::make('color')->label(__('Card Color'))
                        ->required(),

                    Radio::make('type')->label(__('Card Type'))
                        ->options(['Credit', 'Debit'])
                        ->required(),
                ]),

            TextInput::make('limit')->label(__('Total Limit'))
                ->required()
                ->numeric()
                ->prefix('R$ ')
                ->placeholder('1.000,00')
                ->live(),
        ];
    }
}
