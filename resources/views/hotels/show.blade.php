@section('title', "Отель \"{$hotel->name}\"")

@php
    $startDate = request()->get('start_date', \Carbon\Carbon::now()->format('Y-m-d'));
    $endDate = request()->get('end_date', \Carbon\Carbon::now()->addDay()->format('Y-m-d'));
@endphp

<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">

        {{-- Отель --}}
        <div class="flex flex-wrap bg-gray-50 shadow-md mb-12">
            <div class="w-full flex justify-start md:w-1/3 mb-8 md:mb-0">
                <img class="h-full rounded-l-sm" src="{{ $hotel->poster_url }}" alt="Room Image">
            </div>
            <div class="w-full md:w-2/3 p-4">
                <div class="text-2xl font-bold">{{ $hotel->name }}</div>
                <div class="font-bold">
                    @if(isset($hotel->rating))
                        <span class="text-white rounded-md bg-green-600 px-2.5">{{ $hotel->rating ?? 'Оценок нет'}}</span>
                        <a class="text-gray-400 no-underline hover:text-gray-500 hover:underline" href="{{ route('feedbacks.index', ['hotel' => $hotel]) }}">
                            {{ $hotel->feedback_quantity }} {{ getPhraseForNumber($hotel->feedback_quantity, 'оценка') }}
                        </a>
                    @else
                        <span class="text-white rounded-md bg-gray-400 px-2.5">-.-</span>
                        <a class="text-gray-400 no-underline hover:text-gray-500 hover:underline" href="{{ route('feedbacks.index', ['hotel' => $hotel]) }}">
                            Оценок нет
                        </a>
                    @endif
                </div>
                <div class="flex items-center mt-3 mb-3">
                    <x-gmdi-pin-drop-o class="w-5 h-5 mr-1 text-blue-700"/>
                    {{ $hotel->address }}
                </div>
                <div class="mb-4">{{ $hotel->description }}</div>
                <div>
                    @foreach($hotel->facilities as $facility)
                        <span>• {{ $facility->name }} </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Номера отеля --}}
        <div class="flex flex-col">
            <div class="text-2xl text-center md:text-start font-bold">Забронировать комнату</div>

            <!-- Validation Errors -->
            <x-form-validation-errors class="mb-4" :errors="$errors"/>

            <form method="get" action="{{ url()->current() }}">
                <div class="flex my-6">
                    <div class="flex items-center mr-5">
                        <div class="relative">
                            <input name="start_date" min="{{ date('Y-m-d') }}" value="{{ $startDate }}"
                                   placeholder="Дата заезда" type="date"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                        </div>
                        <span class="mx-4 text-gray-500">по</span>
                        <div class="relative">
                            <input name="end_date" type="date" min="{{ date('Y-m-d') }}" value="{{ $endDate }}"
                                   placeholder="Дата выезда"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                        </div>
                    </div>
                    <div>
                        <x-the-button type="submit" class=" h-full w-full">Загрузить номера</x-the-button>
                    </div>
                </div>
            </form>
            @if($startDate && $endDate)
                <div class="flex flex-col w-full">
                    @foreach($rooms as $room)
                        <x-rooms.room-list-item :room="$room" class="mb-4"/>
                    @endforeach
                </div>
            @else
                <div></div>
            @endif
        </div>
    </div>
</x-app-layout>
