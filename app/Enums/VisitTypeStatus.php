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
            self::DONE => __("message.DONE_VISITS"),
            self::LATE => __('message.LATE_VISITS'),
            self::PENDING => __('message.PENDING_VISITS'),
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

    public static function asSelectArray(): array
    {
        return [
            self::DONE->value => self::DONE->getLabel(),
            self::LATE->value => self::LATE->getLabel(),
            self::PENDING->value => self::PENDING->getLabel(),
        ];
    }


}
