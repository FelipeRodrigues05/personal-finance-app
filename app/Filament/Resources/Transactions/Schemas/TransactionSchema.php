<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final class TransactionSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Transaction Information')->icon(Heroicon::InformationCircle)
                    ->schema([
                        FileUpload::make('image_path')->label(__('Image'))
                            ->disk('s3')
                            ->visibility('public'),
                        TextInput::make('id')->readOnly(),

                        TextInput::make('value')->label(__('Transaction Value'))->prefix("R$ "),

                        DatePicker::make('transaction_date')->label(__('Transaction Date')),

                        Select::make('type')->label(__('Transaction Type'))
                            ->options([
                                'income'  => "Income",
                                'expense' => "Expense",
                            ])->native(false),

                        Select::make('category_id')->label(__('Category'))
                            ->relationship(name: "category", titleAttribute: 'name')
                            ->native(false),

                        Toggle::make('is_recurrent')->label(__('Is Recurrent')),

                        Select::make('card_id')->label(__('Card'))
                            ->relationship(name: "card", titleAttribute: 'name')
                            ->native(false),
                    ]),
                Section::make('Transaction Description')->icon(Heroicon::Bookmark)
                    ->schema([
                        MarkdownEditor::make('description')->label(__('Transaction Description')),
                    ]),
            ]);
    }
}
