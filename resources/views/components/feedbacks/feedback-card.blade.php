<div {{ $attributes->merge(['class' => 'bg-gray-50 flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8']) }}>
    <div class="flex flex-col justify-start items-start px-4 py-4 md:px-6 xl:px-8 w-full">
        <div class="w-full flex justify-between py-2">
            <div class="flex items-center">
                {{-- Признак активности --}}
                @if($feedback->is_active === 0)
                    <span class="rounded-md bg-red-700 text-sm text-white px-3 py-0.5 mr-2">На одобрении</span>
                @endif
                {{-- Оценка --}}
                <span class="text-white rounded-md bg-green-600 px-2.5 mr-4">&#9733; {{ $feedback->rating }}</span>
                {{-- Дата создания отзыва --}}
                <span class="text-base font-medium text-gray-600 flex items-center mr-2">
                    <x-gmdi-access-time class="w-4 h-4 mr-0.5 mr-1"/>{{ $feedback->created_at->format('d-m-Y, H:i') }}
                </span>
            </div>
            @if($feedback->user_id === auth()->user()->id)
                <div class="flex">
                    {{-- Кнопка "Редактировать" --}}
                    <div>
                        <x-link-button class="ml-4" href="{{ route('feedbacks.edit', ['feedback' => $feedback]) }}">Редактировать</x-link-button>
                    </div>
                    {{-- Кнопка "Удалить" --}}
                    <div>
                        <form class="ml-4" method="POST" action="{{ route('feedbacks.destroy', ['feedback' => $feedback]) }}">
                            @csrf
                            @method('DELETE')
                            <x-the-button-delete class=" h-full w-full">Удалить</x-the-button-delete>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        <div>
            {{ $feedback->text }}
        </div>
    </div>
</div>
