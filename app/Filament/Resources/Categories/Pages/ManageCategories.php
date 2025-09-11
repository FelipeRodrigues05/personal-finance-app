<?php declare(strict_types = 1);

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\Actions\CreateCategory;
use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\ManageRecords;

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
            CreateCategory::make(),
        ];
    }
}
