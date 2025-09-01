<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Pages;

use App\Actions\Transaction\CreateTransactionAction;
use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Card;
use App\Models\Category;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

final class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    protected ?string $heading = 'Transactions';

    protected ?string $subheading = 'A list of all Transactions';

    public function getTabs(): array
    {
        return [
            'all'     => Tab::make('All Transactions')->icon(Heroicon::ListBullet),
            'income'  => Tab::make('Income')->icon(Heroicon::ArrowTrendingUp)->modifyQueryUsing(fn ($query) => $query->where('type', TransactionTypeEnum::INCOME)),
            'expense' => Tab::make('Expense')->icon(Heroicon::ArrowTrendingDown)->modifyQueryUsing(fn ($query) => $query->where('type', TransactionTypeEnum::EXPENSE)),
            'is_recurrent' => Tab::make('Recurrents')->icon(Heroicon::Clock)->modifyQueryUsing(fn ($query) => $query->where('is_recurrent', true)),
        ];
    }

    /**
     * @throws Exception
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('add')
                ->icon(Heroicon::PlusCircle)
                ->steps([
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

                            FileUpload::make('attachment')->disk('s3')->visibility('public')->directory('uploads')->required(),
                        ]),
                    Step::make('Cards')
                        ->schema([
                            Select::make('card')->options(Card::query()->fromUser()->pluck('name', 'id'))->native(false),
                        ]),
                    Step::make('Description')
                        ->schema([
                            MarkdownEditor::make('description'),
                        ]),
                ])
                ->action(fn (array $data) => CreateTransactionAction::handle(collect($data)))
                ->slideOver(),
        ];
    }
}
