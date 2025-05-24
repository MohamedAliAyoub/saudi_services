<style>
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background-color: #fff;
        padding: 20px;
        margin-bottom: 20px;
    }

    .dark .card {
        background-color: #2d3748;
        color: #e2e8f0;
    }

    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .card-body {
        font-size: 1rem;
    }

    .card-footer {
        margin-top: 10px;
        text-align: right;
    }

    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .visit-image {
        max-height: 200px;
        max-width: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .image-Title {
        margin-top: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .dark .image-Title {
        background-color: #2d3748;
        color: #e2e8f0;
    }
</style>
<x-filament::page>
    <div class="filament-page">
        <h1 class="text-2xl image-Title font-bold mb-4">{{ __('message.Visit_Details') }}</h1>
        <div class="row card">
          <div style="display: flex;justify-content: space-around;">
              <p><strong>{{ __('message.date') }}:</strong> {{ $record->date->format('Y-m-d') ?? __('message.not_available') }}</p>
              <p><strong>{{ __('message.time') }}:</strong> {{ $record->time->format('H:i:s') ?? __('message.not_available') }}</p>
              <p><strong>{{ __('message.status') }}:</strong> {{ $record->status ?? __('message.not_available') }}</p>
              <p><strong>{{ __('message.comment') }}:</strong> {{ $record->comment ?? __('message.not_available') }}</p>
          </div>
           <div style="display: flex;justify-content: space-around;">
               <p><strong>{{ __('message.employee') }}:</strong> {{ $record->employee->name ?? __('message.not_available') }}</p>
               <p><strong>{{ __('message.store') }}:</strong> {{ $record->store->translated_name ?? __('message.not_available') }}</p>
               <p><strong>{{ __('message.client') }}:</strong> {{ $record->client->name ?? __('message.not_available') }}</p>
               <p><strong>{{ __('message.rate') }}:</strong> {{ $record->rate ?? __('message.not_available') }}</p>

           </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <h2 class="text-xl image-Title font-bold mt-4">{{ __('message.before_image') }}</h2>
                <div class="image-grid">
                    @foreach($record->getImageBeforeUrls() as $imageUrl)
                        <div class="card">
                            <div class="card-body">
                                <img src="{{ $imageUrl }}" alt="Before Visit Image" class="visit-image">
                            </div>
                            <div class="card-footer">
                                <a href="{{ $imageUrl }}" download>{{ __('message.Download') }}</a>
                                <a href="{{ $imageUrl }}" target="_blank">{{ __('message.view') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h2 class="text-xl image-Title font-bold mt-4">{{ __('message.after_image') }}</h2>
                <div class="image-grid">
                    @foreach($record->getImageAfterUrls() as $imageUrl)
                        <div class="card">
                            <div class="card-body">
                                <img src="{{ $imageUrl }}" alt="After Visit Image" class="visit-image">
                            </div>
                            <div class="card-footer">
                                <a href="{{ $imageUrl }}" download>{{ __('message.Download') }}</a>
                                <a href="{{ $imageUrl }}" target="_blank">{{ __('message.view') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h2 class="text-xl image-Title font-bold mt-4">{{ __('message.report_image') }}</h2>
                <div class="image-grid">
                    @foreach($record->getImageReportUrls() as $imageUrl)
                        <div class="card">
                            <div class="card-body">
                                <img src="{{ $imageUrl }}" alt="Report Visit Image" class="visit-image">
                            </div>
                            <div class="card-footer">
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
