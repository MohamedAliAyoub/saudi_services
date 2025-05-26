<?php

namespace App\Filament\Client\Widgets;

use App\Models\SliderImage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Widget;

class SliderWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    // Set to full width
    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.client.resources.client-providor-resource.widgets.slider-widget';

    public function getImages()
    {
        return SliderImage::where('active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($image) {
                $media = $image->getFirstMedia('slider_image');
                return [
                    'image' => $media ? $media->getUrl() : null,
                    'title' => $image->title,
                    'description' => $image->description,
                ];
            })
            ->filter(fn($item) => $item['image'] !== null);
    }
}
