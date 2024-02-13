<div class="bg-white rounded shadow-md flex card text-grey-darkest">
    <img class="w-1/2 h-full rounded-l-sm" src="{{ $hotel->poster_url }}" alt="Hotel Image">
    <div class="w-full flex flex-col justify-between p-3">
        <div>
            <a class="block text-grey-darkest font-bold"
               href="{{ route('hotels.show', ['hotel' => $hotel]) }}">{{ $hotel->name }}
            </a>
            <div class="text-xs font-bold mb-2">
                @if(isset($hotel->rating))
                    <span class="text-white rounded-md bg-green-600 px-2.5">{{ $hotel->rating }}</span>
                    <span class="text-gray-400">{{ $hotel->feedback_quantity }} {{ getPhraseForNumber($hotel->feedback_quantity, 'оценка') }} </span>
                @else
                    <span class="text-white rounded-md bg-gray-400 px-2.5">-.-</span>
                    <span class="text-gray-400">Оценок нет</span>
                @endif
            </div>
            <div class="flex items-center text-xs">
                <x-gmdi-pin-drop-o class="w-4 h-4 mr-0.5 text-blue-700"/>
                {{ $hotel->address }}
            </div>
        </div>
        <div class="pt-2">
            <span class="text-2xl text-grey-darkest">₽{{ $hotel->price }}</span>
            <span class="text-lg"> за ночь</span>
        </div>
        @if($hotel->facilities->isNotEmpty())
            <div class="flex items-center py-2">
                @foreach($hotel->facilities->take(2) as $facility)
                    <div class="pr-2 text-xs">
                        <span>•</span> {{ $facility->name }}
                    </div>
                @endforeach
            </div>
        @endif
        <div class="flex justify-end">
            @if(isAdminPanel())
                <x-link-button href="{{ route('admin.hotels.show', ['hotel' => $hotel, 'start_date' => request()->get('start_date'), 'end_date' => request()->get('end_date')]) }}">Подробнее</x-link-button>
            @else
                <x-link-button href="{{ route('hotels.show', ['hotel' => $hotel, 'start_date' => request()->get('start_date'), 'end_date' => request()->get('end_date')]) }}">Подробнее</x-link-button>
            @endif
        </div>
    </div>
</div>
