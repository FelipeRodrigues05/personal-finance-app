<?php declare(strict_types = 1);

namespace App\Filament\Resources\Investments\Pages;

use App\Filament\Resources\Investments\Actions\CreateInvestment;
use App\Filament\Resources\Investments\InvestmentResource;
use App\Filament\Widgets\InvestmentOverviewWidget;
use Filament\Resources\Pages\ManageRecords;

final class ManageInvestments extends ManageRecords
{
    protected static string $resource = InvestmentResource::class;

    public function getHeading(): string
    {
        return __('Your investments');
    }

    public function getSubheading(): string
    {
        return __('Keep track of all your investments');
    }

    public function getHeaderWidgets(): array
    {
        return [
            InvestmentOverviewWidget::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateInvestment::make(),
        ];
    }

}
