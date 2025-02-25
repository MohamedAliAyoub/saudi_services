<style>
    .image-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px; /* Add more padding between images */
    }

    .image-container {
        flex: 1 1 calc(33.333% - 20px); /* Adjust size of images to fit three per row */
        box-sizing: border-box;
    }

    .visit-image {
        width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .image-actions {
        margin-top: 10px;
        text-align: center;
    }
</style>
<x-filament::page>
    <div class="filament-page">
        <h1 class="text-2xl font-bold mb-4">{{ __('message.Visit_Details') }}</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="image-grid">
                    @foreach($record->getImageUrls() as $imageUrl)
                        <div class="image-container">
                            <img src="{{ $imageUrl }}" alt="Visit Image" class="visit-image">
                            <div class="image-actions">
                                <a href="{{ $imageUrl }}" download>{{ __('message.Download') }}</a>
                                <a href="{{ $imageUrl }}" target="_blank">{{ __('message.VIEW') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
