<?php declare(strict_types=1);

namespace App\Filament\Resources\Cards\Pages;

use App\Actions\Card\CreateCardAction;
use App\Filament\Resources\Cards\CardResource;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

final class ManageCards extends ManageRecords
{
    protected static string $resource = CardResource::class;

    /**
     * @throws \Exception
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('add')
                ->icon(Heroicon::PlusCircle)
                ->schema([
                    Grid::make()
                        ->columns(3)
                        ->schema([
                            TextInput::make('name')
                                ->required(),

                            ColorPicker::make('color')->required(),

                            Radio::make('type')
                                ->options(['Credit', 'Debit'])
                                ->required(),
                        ]),
                    TextInput::make('limit')
                        ->required()
                        ->numeric()
                        ->prefix('R$ ')
                        ->placeholder('1.000,00')
                        ->live(),
                ])
                ->action(fn(array $data) => CreateCardAction::handle(collect($data)))
                ->slideOver(),
        ];
    }
}
