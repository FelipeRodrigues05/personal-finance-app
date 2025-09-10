<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Actions;

use App\Actions\Transaction\CreateTransactionAction;
use App\Models\Card;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

final class CreateTransaction
{
    public static function make()
    {
        return Action::make('add')
            ->icon(Heroicon::PlusCircle)
            ->steps(self::steps())
            ->action(fn (array $data) => CreateTransactionAction::handle(collect($data)))
            ->slideOver();
    }

    private static function steps(): array
    {
        return [
            Step::make('Transaction Info')
                ->schema([
                    Grid::make()
                        ->columns(2)
                        ->schema([
                            TextInput::make('value')
                                ->required()
                                ->numeric()
                                ->prefix('R$ ')
                                ->placeholder('1.000,00')
                                ->live(),

                            Select::make('category')
                                ->options(Category::query()->fromUser()->pluck('name', 'id'))
                                ->required()
                                ->native(false),
                        ]),
                    Grid::make()->columns(2)
                        ->schema([
                            DatePicker::make('transaction_date')
                                ->required()
                                ->native(false)
                                ->helperText('If is recurrent this will be the expiration date')
                                ->default(now()),

                            Radio::make('type')
                                ->options(['Expense', 'Income'])
                                ->required(),

                            Toggle::make('is_recurrent')
                                ->inline(false)
                                ->required(),
                        ]),

                    FileUpload::make('attachment')->disk('s3')->visibility('public')->directory('uploads')->required()->image(),
                ]),
            Step::make('Cards')
                ->schema([
                    Select::make('card')->options(Card::query()->fromUser()->pluck('name', 'id'))->native(false),
                ]),
            Step::make('Description')
                ->schema([
                    MarkdownEditor::make('description'),
                ]),
        ];
    }
}
