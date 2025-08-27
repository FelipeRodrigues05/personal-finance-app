<?php declare(strict_types = 1);

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\TransactionTypeEnum;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

final class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    protected ?string $heading = 'Transactions';

    protected ?string $subheading = 'A list of all Transactions';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add')
                ->icon(Heroicon::PlusCircle)
                ->steps([
                    Step::make('Transaction Info')
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

                            DateTimePicker::make('when')->required(),

                            Radio::make('type')
                                ->options(['Credit', 'Debit'])
                                ->required(),
                        ])
                        ->columns(2),
                    Step::make('Description')
                        ->schema([
                            MarkdownEditor::make('description'),
                        ]),
                ])
                ->action(fn (array $data) => $this->save($data))
                ->slideOver(),
        ];
    }

    private function save(array $data)
    {
        $transaction = Transaction::query()->create([
            'value'       => data_get($data, 'value'),
            'category_id' => Category::query()->findOrFail(data_get($data, 'category'))->id,
            'user_id'     => auth()->user()->id,
            'description' => data_get($data, 'description'),
            'type'        => data_get($data, 'type') === '1' ? TransactionTypeEnum::DEBIT : TransactionTypeEnum::CREDIT,
            'when'        => data_get($data, 'when'),
        ]);

        if ($transaction) {
            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('An error has occured')
                ->danger()
                ->send();
        }

    }
}
