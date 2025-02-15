<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = '';

    protected static ?int $sort = 2;

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
                    'data' => [4344, 5676, 6798, 7890, 8987, 9388, 10343, 10524, 13664, 14345, 15753, 17332],
                    'fill' => 'start',
                ],
            ],
            'labels' => [
                __('message.Jan'), __('message.Feb'), __('message.Mar'), __('message.Apr'), __('message.May'), __('message.Jun'),
                __('message.Jul'), __('message.Aug'), __('message.Sep'), __('message.Oct'), __('message.Nov'), __('message.Dec')
            ],        ];
    }
}
