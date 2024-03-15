@if(isAdminPanel())
    @section('title', 'Бронирования')
@else
    @section('title', 'Мои бронирования')
@endif

<x-app-layout>
    <div class="pt-4 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Блок "Фильтры бронирований" --}}
            <x-bookings.booking-filters class="mb-4" :index-data="$indexData"/>
            {{-- Блок "Бронирования" --}}
            <div class="overflow-hidden">
                @if($bookings->isNotEmpty())
                    @foreach($bookings as $booking)
                        <x-bookings.booking-card class="mb-4" :booking="$booking" :show-link="true"/>
                    @endforeach
                @else
                    <h1 class="text-lg md:text-xl font-semibold text-gray-800">Нет бронирований</h1>
                @endif
            </div>
            {{-- Блок "Пагинация" --}}
            {{ $bookings->onEachSide(1)->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
