<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = '';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return false;
    }
    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => __('message.VISITS'),
                    'data' => [2433, 3454, 4566, 3300, 5545, 5765, 6787, 8767, 7565, 8576, 9686, 8996],
                    'fill' => 'start',
                ],
            ],
            'labels' => [
                __('message.Jan'), __('message.Feb'), __('message.Mar'), __('message.Apr'), __('message.May'), __('message.Jun'),
                __('message.Jul'), __('message.Aug'), __('message.Sep'), __('message.Oct'), __('message.Nov'), __('message.Dec')
            ],        ];
    }
}
