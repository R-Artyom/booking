@section('title', "Управление отзывами")

<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">

        {{-- Список всех отзывов --}}
        @if(!$feedbacks->isEmpty())
            @foreach($feedbacks as $feedback)
                {{-- Название отеля --}}
                <a class="text-2xl text-center md:text-start font-bold" href="{{ route('admin.hotels.show', ['hotel' => $feedback->hotel]) }}">
                    {{ $feedback->hotel->name }}
                </a>
                {{-- Адрес отеля --}}
                <div class="flex items-center mb-2">
                    <x-gmdi-pin-drop-o class="w-5 h-5 mr-1 text-blue-700"/>
                    {{ $feedback->hotel->address }}
                </div>
                {{-- Отзыв --}}
                <div class="flex flex-col xl:flex-row w-full xl:space-x-8 space-y-4 xl:space-y-0 mb-8">
                    <x-users.user-card :user="$feedback->user"></x-users.user-card>
                    <x-feedbacks.feedback-card :feedback="$feedback"></x-feedbacks.feedback-card>
                </div>
            @endforeach
        @else
            <h1 class="text-lg md:text-xl font-semibold text-gray-800">Нет отзывов</h1>
        @endif

        {{-- Пагинация --}}
        <div class="mt-4">
            {{ $feedbacks->onEachSide(1)->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
