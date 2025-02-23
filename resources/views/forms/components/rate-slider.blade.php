<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }" class="flex justify-center items-center">
        <div class="star-rating">
            @for ($i = 1; $i <= 5; $i++)
                <input type="radio" id="star{{ $i }}" name="rate" x-model="state" value="{{ $i }}">
                <label for="star{{ $i }}" :class="{ 'text-yellow-500': state >= {{ $i }}, 'text-gray-400': state < {{ $i }} }">&#9733;</label>
            @endfor
        </div>
    </div>
</x-dynamic-component>

<style>
.star-rating {
    direction: rtl;
    display: inline-block;
}
.star-rating input[type="radio"] {
    display: none;
}
.star-rating label {
    font-size: 2em;
    cursor: pointer;
}
.text-gray-400 {
    color: #d1d5db;
}
.text-yellow-500 {
    color: #f59e0b;
}
</style>
