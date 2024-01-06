<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">

        {{-- Отель --}}
        <div class="text-2xl text-center md:text-start font-bold mb-4">Отель</div>
        <div class="flex flex-wrap bg-gray-50 shadow-md mb-12">
            <div class="w-full flex justify-start md:w-1/3 mb-8 md:mb-0">
                <img class="h-full rounded-l-sm" src="{{ $hotel->poster_url }}" alt="Room Image">
            </div>
            <div class="w-full md:w-2/3 px-4">
                <div class="text-2xl font-bold mb-2">{{ $hotel->name }}</div>
                <div class="flex items-center mb-3">
                    <x-gmdi-pin-drop-o class="w-5 h-5 mr-1 text-blue-700"/>
                    {{ $hotel->address }}
                </div>
                <div class="mb-4">{{ $hotel->description }}</div>
                <div>
                    @foreach($hotel->facilities as $facility)
                        <span>• {{ $facility->name }} </span>
                    @endforeach
                </div>
                <hr class="mb-4">
                <div class="flex justify-end h-10">
                    <x-link-button href="{{ route('admin.hotels.edit', ['hotel' => $hotel]) }}">Редактировать</x-link-button>
                    <form class="ml-4" method="POST" action="{{ route('admin.hotels.destroy', ['hotel' => $hotel]) }}">
                        @csrf
                        @method('DELETE')
                        <x-the-button-delete class=" h-full w-full">Удалить</x-the-button-delete>
                    </form>
                </div>
            </div>
        </div>

        {{-- Номера отеля --}}
        <div class="flex justify-between mb-4">
            <div class="text-2xl text-center md:text-start font-bold mb-2">Номера отеля @if($rooms->isEmpty())отсутствуют@endif</div>
            {{-- Кнопка "Добавить номер" --}}
            @if(request()->route()->getPrefix() === '/admin')
                <x-link-button-add href="{{ route('admin.rooms.create', ['hotel' => $hotel]) }}">&#10010; Добавить номер</x-link-button-add>
            @endif
        </div>
        <div class="flex flex-col">
            @if(!$rooms->isEmpty())
                <div class="flex flex-col w-full">
                    @foreach($rooms as $room)
                        <x-rooms.admin-room-list-item :room="$room" class="mb-4"/>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
