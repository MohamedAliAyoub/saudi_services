<?php

namespace App\Enums;

enum ImagesTypeEnum: int
{
    case BEFORE = 0;
    case AFTER = 1;
    case REPORTS = 2;

    public function label(): string
    {
        return match ($this) {
            self::BEFORE => __('message.before_image'),
            self::AFTER => __('message.after_image'),
            self::REPORTS => __('message.report_image'),
        };
    }

    public static function asSelectArray(): array
    {
        return [
            self::BEFORE->value => self::BEFORE->label(),
            self::AFTER->value => self::AFTER->label(),
            self::REPORTS->value => self::REPORTS->label(),
        ];
    }
}
