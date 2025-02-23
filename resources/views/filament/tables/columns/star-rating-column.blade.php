<span>
    @if (!$getState())
        <span class="text-gray-400">No rating</span>
    @else
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $getState())
                &#9733;
            @else
                &#9734;
            @endif
        @endfor
    @endif
</span>
