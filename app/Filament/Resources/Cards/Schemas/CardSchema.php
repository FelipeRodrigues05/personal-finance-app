<?php

namespace App\Filament\Resources\Cards\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CardSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                ColorPicker::make('color'),
                TextInput::make('limit')->prefix('R$')->numeric(),
                TextInput::make('used')->prefix('R$')->numeric(),
                Radio::make('type')->options([
                    'Credit'    => "Credit",
                    'Debit'     => "Debit"
                ])->default('type'),
            ]);
    }
}
