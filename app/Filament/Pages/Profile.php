<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    protected string $view = 'filament.pages.profile';

    public function getMaxContentWidth(): Width
    {
        return Width::SevenExtraLarge;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Flex::make([
                Section::make()->schema([
                    FileUpload::make('attachment')->avatar()->imageEditor()->disk('s3')->visibility('public'),
                ]),
                Section::make()->schema([
                    TextInput::make('name')->placeholder(auth()->user()->name),
                    TextInput::make('email')->email()->placeholder(auth()->user()->email),
                ])
            ])
        ])->statePath('data');
    }

    public function submit() {

    }
}
