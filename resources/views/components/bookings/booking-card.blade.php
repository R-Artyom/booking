<div {{ $attributes->merge(['class' => 'flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8']) }}>
    <div class="flex flex-col justify-start items-start bg-gray-50 px-4 py-4 md:px-6 xl:px-8 w-full">
        <div class="flex justify-between w-full py-2 border-b border-gray-200">
            <div class="w-full">
                <p class="text-xl font-semibold leading-6 xl:leading-5 text-gray-800">Бронирование
                    #{{ $booking->id }}</p>
                <p class="text-base font-medium leading-6 text-gray-600 ">{{ $booking->created_at->format('d-m-y H:i') }}</p>
            </div>
            @if($showLink ?? false)
            <div class="flex">
                @if(isAdminPanel())
                    <x-link-button href="{{ route('admin.bookings.show', ['booking' => $booking]) }}">Подробнее</x-link-button>
                @else
                    <x-link-button href="{{ route('bookings.show', ['booking' => $booking]) }}">Подробнее</x-link-button>
                @endif
            </div>
            @endif
            @if($booking->status_id === config('status.Создан'))
                <form class="ml-4" method="POST" action="{{ route('bookings.destroy', ['booking' => $booking]) }}">
                    @csrf
                    @method('DELETE')
                    <x-the-button-delete class=" h-full w-full">Отменить</x-the-button-delete>
                </form>
            @endif
        </div>
        <div class="mt-4 md:mt-6 flex flex-col md:flex-row justify-start items-start md:space-x-6 w-full">
            <div class="pb-4 w-full md:w-2/5">
                <img class="w-full block" src="{{ $booking->room->poster_url }}" alt="image"/>
            </div>
            <div
                class="md:flex-row flex-col flex justify-between items-start w-full md:w-3/5 pb-8 space-y-4 md:space-y-0">
                <div class="w-full flex flex-col justify-start items-start space-y-8">
                    <h3 class="text-xl font-semibold leading-6 text-gray-800">{{ $booking->room->name }}</h3>
                    <div class="flex justify-start items-start flex-col space-y-2">
                        <p class="text-sm leading-none text-gray-800"><span>Даты: </span>
                            {{ \Carbon\Carbon::parse($booking->started_at)->format('d.m.Y') }}
                            по
                            {{ \Carbon\Carbon::parse($booking->finished_at)->format('d.m.Y') }}</p>
                        <p class="text-sm leading-none text-gray-800"><span>Кол-во ночей: </span> {{ $booking->days }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-end space-x-8 items-end w-full">
                    <p class="text-base xl:text-lg font-semibold leading-6 text-gray-800">
                        Стоимость: {{ $booking->price }} руб</p>
                </div>
            </div>
        </div>
    </div>
</div>
