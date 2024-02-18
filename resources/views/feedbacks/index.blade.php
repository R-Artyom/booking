@section('title', "Отзывы к отелю \"{$hotel->name}\"")

<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        {{-- Кнопка "Написать отзыв" --}}
        <div class="flex mb-4 justify-between">
            <div class="text-2xl text-center md:text-start font-bold">@yield('title') @if($feedbacks->isEmpty())отсутствуют@endif</div>
            {{-- Кнопка доступна только если у юзера нет отзывов на этот отель)--}}
            @if($feedbackAddLock === false)
                <x-link-button-add href="{{ route('feedbacks.create', ['hotel' => $hotel]) }}">&#10010; Написать отзыв</x-link-button-add>
            @endif
        </div>
        <div class="flex items-center mt-3 mb-3">
            <x-gmdi-pin-drop-o class="w-5 h-5 mr-1 text-blue-700"/>
            {{ $hotel->address }}
        </div>

        {{-- Список отзывов --}}
        @if(!$feedbacks->isEmpty())
            @foreach($feedbacks as $feedback)
                <div class="mt-10 flex flex-col xl:flex-row jusitfy-center items-stretch w-full xl:space-x-8 space-y-4 md:space-y-6 xl:space-y-0">
                    <x-users.user-card :user="$feedback->user"></x-users.user-card>
                    <x-feedbacks.feedback-card :feedback="$feedback"></x-feedbacks.feedback-card>
                </div>
            @endforeach
        @endif

        {{-- Пагинация --}}
        <div class="mt-4">
            {{ $feedbacks->onEachSide(1)->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
