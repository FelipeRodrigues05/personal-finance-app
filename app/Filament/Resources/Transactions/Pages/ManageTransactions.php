<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Category;
use App\Models\Transaction;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;

final class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    protected ?string $heading = 'Transactions';

    protected ?string $subheading = 'A list of all Transactions';

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Transactions')->icon(Heroicon::ListBullet),
            'income' => Tab::make('Income')->icon(Heroicon::ArrowTrendingUp)->modifyQueryUsing(fn ($query) => $query->where('type', TransactionTypeEnum::INCOME)),
            'expense' => Tab::make('Expense')->icon(Heroicon::ArrowTrendingDown)->modifyQueryUsing(fn ($query) => $query->where('type', TransactionTypeEnum::EXPENSE))
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
                                    DatePicker::make('transaction_date')->required()->native(false)->default(now()),

                                    Radio::make('type')
                                        ->options(['Expense', 'Income'])
                                        ->required(),
                                ]),

                            FileUpload::make('attachment')->disk('s3')->visibility('public')->directory('uploads')->required(),
                        ]),
                    Step::make('Description')
                        ->schema([
                            MarkdownEditor::make('description'),
                        ]),
                ])
                ->action(fn (array $data) => $this->save($data))
                ->slideOver(),
        ];
    }

    private function save(array $data): void
    {
        $fileURL = Storage::disk('s3')->url(data_get($data, 'attachment'));

        $transaction = Transaction::query()->create([
            'value'            => data_get($data, 'value'),
            'category_id'      => Category::query()->findOrFail(data_get($data, 'category'))->id,
            'user_id'          => auth()->user()->id,
            'description'      => data_get($data, 'description'),
            'type'             => data_get($data, 'type') === '1' ? TransactionTypeEnum::INCOME : TransactionTypeEnum::EXPENSE,
            'transaction_date' => data_get($data, 'transaction_date'),
            'image_path'       => $fileURL,
        ]);

        if ($transaction) {
            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('An error has occurred')
                ->danger()
                ->send();
        }

    }
}
