<!-- resources/views/filament/client/resources/visit-resource/view.blade.php -->
<x-filament::page>

<style>
    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(33%, 1fr));
        gap: 16px;
    }

    .image-container {
        position: relative;
    }

    .image-grid img {
        width: 100%;
        height: auto;
        max-width: 300px;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .image-actions {
        position: absolute;
        bottom: 8px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.5);
        padding: 4px 8px;
        border-radius: 4px;
        display: flex;
        justify-content: center;
    }

    .image-actions a {
        color: #fff;
        margin: 0 8px;
        text-decoration: none;
    }

    /* RTL support */
    body[dir="rtl"] .image-actions {
        left: 50%;
        right: auto;
        transform: translateX(-50%);
    }

    body[dir="rtl"] .image-actions a {
        margin: 0 8px;
    }

    /* Responsive design */
    @media (max-width: 1200px) {
        .image-grid {
            grid-template-columns: repeat(auto-fill, minmax(50%, 1fr));
        }
    }

    @media (max-width: 768px) {
        .image-grid {
            grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
        }

        .image-grid img {
            max-width: 100%;
            max-height: 200px;
        }
    }

    @media (max-width: 480px) {
        .image-grid img {
            max-height: 150px;
        }
    }
</style>    <div class="filament-page">
        <h1 class="text-2xl font-bold mb-4">{{ __('message.Visit_Details') }}</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="image-grid">
                    @foreach($record->images as $image)
                        <div class="image-container">
                            <img src="{{ asset($image->full_path) }}" alt="Visit Image">
                            <div class="image-actions">
                                <a href="{{ asset($image->full_path) }}" download>{{ __('message.Download') }}</a>
                                <a href="{{ asset($image->full_path) }}" target="_blank">{{ __('message.VIEW') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
