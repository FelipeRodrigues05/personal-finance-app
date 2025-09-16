<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Group extends Page
{
    protected string $view = 'filament.pages.group';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
