<?php declare(strict_types = 1);

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class CategoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Category::query()->fromUser())
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('name')
                    ->searchable(),
                ColorColumn::make('color')
                    ->searchable()
                    ->copyable(),
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
            ]);
    }
}
