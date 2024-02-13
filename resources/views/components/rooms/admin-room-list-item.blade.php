<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row shadow-md bg-gray-50']) }}>
    <div class="h-full w-full md:w-1/3">
        <div class="h-64 w-full bg-cover bg-center bg-no-repeat" style="background-image: url({{ $room->poster_url }})">
        </div>
    </div>
    <div class="p-4 w-full md:w-2/3 flex flex-col justify-between">
        <div class="pb-2">
            <div class="text-xl font-bold">
                {{ $room->name }}
            </div>
            <div>
                <span>•</span> {{ $room->floor_area }} м
            </div>
            <div>
                @foreach($room->facilities as $facility)
                    <span>• {{ $facility->name }} </span>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="flex justify-end pt-2">
            <div class="flex flex-col">
                <span class="text-lg font-bold">{{ $room->total_price }} руб.</span>
                <span>за {{ $room->total_days }} {{ getPhraseForNumber($room->total_days, 'ночь') }}</span>
            </div>
            <div class="flex justify-end h-10">
                {{-- Кнопка "Редактировать" --}}
                <x-link-button class="ml-4" href="{{ route('admin.rooms.edit', ['room' => $room]) }}">Редактировать</x-link-button>
                {{-- Кнопка "Удалить" --}}
                <form class="ml-4" method="POST" action="{{ route('admin.rooms.destroy', ['room' => $room]) }}">
                    @csrf
                    @method('DELETE')
                    <x-the-button-delete class=" h-full w-full">Удалить</x-the-button-delete>
                </form>
            </div>
        </div>
    </div>
</div>
