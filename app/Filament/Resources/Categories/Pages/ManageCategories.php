<?php declare(strict_types = 1);

namespace App\Filament\Resources\Categories\Pages;

use App\Actions\Category\CreateCategoryAction;
use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

final class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    public function getHeading(): string
    {
        return __('Your Categories');
    }

    public function getSubheading(): string
    {
        return __('Keep track of all your categories');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make("add")
                ->icon(Heroicon::PlusCircle)
                ->schema(self::getForm())
                ->action(fn (array $data) => CreateCategoryAction::handle(collect($data))),
        ];
    }

    private static function getForm(): array
    {
        return [
            Grid::make()
                ->schema([
                    Section::make("Title")->schema([
                        TextInput::make('name')->label(__('Category Name'))
                            ->required()
                            ->live(),
                    ]),
                    Section::make('Color')->schema([
                        ColorPicker::make('color')->label(__('Category Color'))
                            ->required(),
                    ]),
                ]),
        ];
    }
}
