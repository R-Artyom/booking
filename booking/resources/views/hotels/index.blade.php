<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        {{-- Блок "Фильтры отелей" --}}
        <x-hotels.hotel-filters class="mb-4" :index-data="$indexData"/>
        {{-- Кнопка "Добавить отель" --}}
        @if(request()->route()->getPrefix() === '/admin')
            <div class="flex mb-4 justify-end">
                <x-link-button-add href="{{ route('admin.hotels.create') }}">&#10010; Добавить отель</x-link-button-add>
            </div>
        @endif
        {{-- Блок "Отели" --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            @if($hotels->isNotEmpty())
                @foreach($hotels as $hotel)
                    <x-hotels.hotel-card :hotel="$hotel"></x-hotels.hotel-card>
                @endforeach
            @else
                <h1 class="text-lg md:text-xl font-semibold text-gray-800">Нет отелей с выбранными параметрами</h1>
            @endif
        </div>
        {{ $hotels->onEachSide(1)->withQueryString()->links() }}
    </div>
</x-app-layout>
