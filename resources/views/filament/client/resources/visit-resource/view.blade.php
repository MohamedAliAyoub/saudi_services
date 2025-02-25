<style>
    .image-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px; /* Add more padding between images */
        margin-top: 20px; /* Add padding between image grid and data */
    }

    .image-container {
        flex: 1 1 calc(33.333% - 20px); /* Adjust size of images to fit three per row */
        box-sizing: border-box;
    }

    .image-frame {
        padding: 10px;
        /*border: 2px solid #ccc; !* Frame border *!*/
        border-radius: 10px;
        background-color: #fff; /* Frame background color */
    }

    .visit-image {
        width: 100%;
        height: auto;
        border-radius: 5px;
        max-width: 300px; /* Set maximum width */
        max-height: 300px; /* Set maximum height */
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
                <p><strong>{{ __('message.date') }}:</strong> {{ $record->date ?? __('message.not_available') }}</p>
                <p><strong>{{ __('message.time') }}:</strong> {{ $record->time ?? __('message.not_available') }}</p>
                <p><strong>{{ __('message.status') }}:</strong> {{ $record->status ?? __('message.not_available') }}</p>
                <p><strong>{{ __('message.comment') }}:</strong> {{ $record->comment ?? __('message.not_available') }}</p>
                <p><strong>{{ __('message.employee') }}:</strong> {{ $record->employee->name ?? __('message.not_available') }}</p>
                <p><strong>{{ __('message.store') }}:</strong> {{ $record->store->name ?? __('message.not_available') }}</p>
                <p><strong>{{ __('message.client') }}:</strong> {{ $record->client->name ?? __('message.not_available') }}</p>
                <p><strong>{{ __('message.rate') }}:</strong> {{ $record->rate ?? __('message.not_available') }}</p>

                <h2 class="text-xl font-bold mt-4">{{ __('message.before_images') }}</h2>
                <div class="image-grid">
                    @foreach($record->getImageBeforeUrls() as $imageUrl)
                        <div class="image-container">
                            <div class="image-frame">
                                <img src="{{ $imageUrl }}" alt="Before Visit Image" class="visit-image">
                            </div>
                            <div class="image-actions">
                                <a href="{{ $imageUrl }}" download>{{ __('message.Download') }}</a>
                                <a href="{{ $imageUrl }}" target="_blank">{{ __('message.view') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h2 class="text-xl font-bold mt-4">{{ __('message.after_images') }}</h2>
                <div class="image-grid">
                    @foreach($record->getImageAfterUrls() as $imageUrl)
                        <div class="image-container">
                            <div class="image-frame">
                                <img src="{{ $imageUrl }}" alt="After Visit Image" class="visit-image">
                            </div>
                            <div class="image-actions">
                                <a href="{{ $imageUrl }}" download>{{ __('message.Download') }}</a>
                                <a href="{{ $imageUrl }}" target="_blank">{{ __('message.view') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h2 class="text-xl font-bold mt-4">{{ __('message.report_image') }}</h2>
                <div class="image-grid">
                    @foreach($record->getImageReportUrls() as $imageUrl)
                        <div class="image-container">
                            <div class="image-frame">
                                <img src="{{ $imageUrl }}" alt="Report Visit Image" class="visit-image">
                            </div>
                            <div class="image-actions">
                                <a href="{{ $imageUrl }}" download>{{ __('message.Download') }}</a>
                                <a href="{{ $imageUrl }}" target="_blank">{{ __('message.view') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
