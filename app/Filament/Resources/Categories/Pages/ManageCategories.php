<?php declare(strict_types = 1);

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

final class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make("add")
                ->icon(Heroicon::PlusCircle)
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Section::make("Title")->schema([
                                TextInput::make('name')->required()->live(),
                            ]),
                            Section::make('Color')->schema([
                                ColorPicker::make('color')->required(),
                            ]),
                        ]),
                ])
                ->action(fn (array $data) => $this->save($data)),
        ];
    }

    private function save(array $data)
    {
        $category = Category::query()->create([
            'name'    => data_get($data, 'name'),
            'color'   => data_get($data, 'color'),
            'user_id' => auth()->user()->id,
        ]);

        if ($category) {
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
