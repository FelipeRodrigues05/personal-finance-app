<?php declare(strict_types = 1);

namespace App\Filament\Resources\Cards\Tables;

use App\Models\Card;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class CardTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Card::query())
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('name')
                    ->searchable(),
                ColorColumn::make('color')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('type')
                    ->searchable()
                    ->badge(),
                TextColumn::make('limit')->label(__('Total Limit'))
                    ->money('BRL', locale: 'pt-BR'),
                TextColumn::make('used')->label(__('Limit Used'))
                    ->money('BRL', locale: 'pt-BR'),
                ViewColumn::make('usage')->label('Limit Usage')
                    ->getStateUsing(
                        fn (Card $record) => $record->limit > 0
                            ? round(($record->used / $record->limit) * 100)
                            : 0
                    )
                    ->view('tables.columns.progress-bar'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
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
