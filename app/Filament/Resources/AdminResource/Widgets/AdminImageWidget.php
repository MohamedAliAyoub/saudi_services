<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\Widget;

class AdminImageWidget extends Widget
{
    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.widgets.admin-image';
}
