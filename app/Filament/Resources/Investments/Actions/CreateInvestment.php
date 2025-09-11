<?php declare(strict_types = 1);

namespace App\Filament\Resources\Investments\Actions;

use App\Actions\Investment\CreateInvestmentAction;
use App\Enums\Recurrence;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

final class CreateInvestment
{
    public static function make()
    {
        return Action::make('add')
            ->icon(Heroicon::PlusCircle)
            ->schema([
                Section::make('Investment Info')->icon(Heroicon::InformationCircle)
                    ->schema([
                        Grid::make()->columns(4)->schema([
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
                    ]),
            ])
            ->action(fn (array $data) => CreateInvestmentAction::handle(collect($data)));
    }
}
