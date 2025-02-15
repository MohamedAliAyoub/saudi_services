<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum VisitTypeStatus: string implements HasColor, HasIcon, HasLabel
{
    case DONE = 'done';
    case LATE = 'late';
    case PENDING = 'pending';

    public function getLabel(): string
    {
        return match ($this) {
            self::DONE => __("messages.Done_Visits"),
            self::LATE => __('messages.Late_Visits'),
            self::PENDING => __('messages.Pending_Visits'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DONE => 'success',
            self::LATE => 'warning',
            self::PENDING => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DONE => 'heroicon-m-check-circle',
            self::LATE => 'heroicon-m-clock',
            self::PENDING => 'heroicon-m-hourglass',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }
}
