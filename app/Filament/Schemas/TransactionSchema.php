<?php

namespace App\Filament\Schemas;

use App\Enums\TransactionTypeEnum;
use App\Models\Category;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;

class TransactionSchema
{
    /**
     * @throws \Exception
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            ]);
    }
}
