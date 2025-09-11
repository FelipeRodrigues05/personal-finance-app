<?php declare(strict_types = 1);

namespace App\Filament\Resources\Investments;

use App\Filament\Resources\Investments\Pages\ManageInvestments;
use App\Filament\Resources\Investments\Schemas\InvestmentSchema;
use App\Filament\Resources\Investments\Tables\InvestmentTable;
use App\Models\Investment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class InvestmentResource extends Resource
{
    protected static ?string $model = Investment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowTrendingUp;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|null|UnitEnum $navigationGroup = 'Financial';

    public static function form(Schema $schema): Schema
    {
        return InvestmentSchema::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvestmentTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInvestments::route('/'),
        ];
    }
}
