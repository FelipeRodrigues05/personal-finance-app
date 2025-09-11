<?php declare(strict_types = 1);

namespace App\Filament\Resources\Investments\Schemas;

use App\Enums\Recurrence;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

final class InvestmentSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->columns()->schema([
                    TextInput::make('name')
                        ->placeholder(__('CDI'))
                        ->required(),
                    TextInput::make('tax')
                        ->placeholder(__('105'))
                        ->suffix('%')
                        ->numeric()
                        ->required(),
                    TextInput::make('amount')
                        ->prefix('R$')
                        ->placeholder('100,00')
                        ->required()
                        ->numeric(),
                    Select::make('recurrence')
                        ->options(
                            collect(Recurrence::cases())
                                ->mapWithKeys(fn ($case) => [$case->value => mb_strtolower($case->name)])
                                ->toArray()
                        )->required(),
                ]),
                MarkdownEditor::make('description'),
            ]);
    }
}
