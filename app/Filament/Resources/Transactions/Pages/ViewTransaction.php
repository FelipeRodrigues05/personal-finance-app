<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Category;
use Exception;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

final class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    /**
     * @throws Exception
     */
    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Voucher")
                    ->icon(Heroicon::Photo)
                    ->schema([
                        ImageEntry::make('image_path')->hiddenLabel()
                            ->extraImgAttributes([
                                'alt'   => 'Voucher',
                                'style' => 'width: 100%; height: 100%; object-fit: cover;',
                            ]),
                    ]),

                Grid::make()->columns(1)->schema([
                    Section::make("Transaction Details")->afterHeader([
                        TextEntry::make("transaction_date")->date()->hiddenLabel(),
                    ])
                        ->icon(Heroicon::InformationCircle)
                        ->schema([
                            Grid::make()->columns(4)
                                ->schema([
                                    TextEntry::make('value')
                                        ->icon(Heroicon::CurrencyDollar)
                                        ->money('BRL', locale: 'pt-BR')
                                        ->color('primary')
                                        ->size(TextSize::Large)
                                        ->weight(FontWeight::Bold),
                                    TextEntry::make('category.name')
                                        ->label('Category')
                                        ->money('BRL', locale: 'pt-BR'),
                                    TextEntry::make('type')->color($this->record->type == TransactionTypeEnum::EXPENSE ? 'danger' : 'success'),
                                    IconEntry::make('is_recurrent')->boolean(),
                                ]),

                            TextEntry::make('description')->default('No description'),
                        ])->collapsible(),

                    Section::make('Card Details')
                        ->icon(Heroicon::CreditCard)
                        ->visible((bool)$this->record->used_card)
                        ->afterHeader([
                            TextEntry::make('card.type')->badge()->hiddenLabel(),
                        ])
                        ->schema([
                            TextEntry::make('card.name')->size(TextSize::Medium)->weight(FontWeight::Medium),
                            Grid::make()->columns()->schema([
                                TextEntry::make('card.limit')->money('BRL', locale: 'pt-BR')
                                    ->weight(FontWeight::Bold)
                                    ->color('info'),
                                TextEntry::make('card.used')->money('BRL', locale: 'pt-BR')->color('info'),
                            ]),
                        ]),
                ]),
            ]);
    }

    /**
     * @throws Exception
     */
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->schema([
                Section::make('Transaction Receipt')
                    ->schema([
                        FileUpload::make('image_path')
                            ->disk('s3')
                            ->default('image_path')
                            ->visibility('public')
                            ->previewable()
                            ->live(),
                    ]),
                Section::make('Transaction Information')
                    ->schema([
                        TextInput::make('value'),
                        DatePicker::make('transaction_date'),
                        Select::make('type')->options([
                            "income"  => "Income",
                            "expense" => "Expense",
                        ])->native(false),
                        Select::make('category')
                            ->options(Category::query()->fromUser()->pluck('name'))
                            ->native(false),
                    ]),
                Section::make('Transaction Description')
                    ->schema([
                        MarkdownEditor::make('description'),
                    ]),
            ]),
        ];
    }
}
