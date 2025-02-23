<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserTypeEnum: string implements HasColor, HasIcon, HasLabel
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case EMPLOYEE = 'employee';

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMIN => __('message.Admin'),
            self::CLIENT => __('message.client'),
            self::EMPLOYEE => __('message.employee'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ADMIN => 'primary',
            self::CLIENT => 'secondary',
            self::EMPLOYEE => 'tertiary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ADMIN => 'heroicon-m-user-circle',
            self::CLIENT => 'heroicon-m-user',
            self::EMPLOYEE => 'heroicon-m-briefcase',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }

    public static function asSelectArray(): array
    {
        return [
            self::ADMIN->value => self::ADMIN->getLabel(),
            self::CLIENT->value => self::CLIENT->getLabel(),
            self::EMPLOYEE->value => self::EMPLOYEE->getLabel(),
        ];
    }
}
