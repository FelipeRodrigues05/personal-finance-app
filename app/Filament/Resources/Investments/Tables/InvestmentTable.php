<?php declare(strict_types = 1);

namespace App\Filament\Resources\Investments\Tables;

use App\Filament\Exports\InvestmentExporter;
use App\Models\Investment;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class InvestmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Investment::query()->fromUser())
            ->columns([
                TextColumn::make('user.name')
                    ->weight(FontWeight::Bold)
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable(),
                TextColumn::make('tax')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                ExportBulkAction::make()
                    ->exporter(InvestmentExporter::class),
            ]);
    }
}
