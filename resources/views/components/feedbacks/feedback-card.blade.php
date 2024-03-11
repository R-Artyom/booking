<div {{ $attributes->merge(['class' => 'bg-gray-50 flex flex-col justify-start items-start w-full space-y-4 md:space-y-6 xl:space-y-8']) }}>
    <div class="flex flex-col justify-start items-start px-4 py-4 md:px-6 xl:px-8 w-full">
        <div class="w-full flex justify-between py-2">
            <div class="flex items-center flex-wrap">
                {{-- Оценка в виде звёзд --}}
                @for($i = 1; $i < 6; $i++)
                    @if($i <= $feedback->rating)
                        <x-gmdi-star class="w-7 h-7 text-yellow-500"/>
                    @else
                        <x-gmdi-star class="w-7 h-7 text-gray-300"/>
                    @endif
                @endfor
                {{-- Дата создания отзыва --}}
                <span class="ml-2 text-base text-gray-600 flex items-center mr-2">
                    {{ getDateRu(strtotime($feedback->created_at)) }}
                </span>
                {{-- Признак активности --}}
                @if($feedback->is_active === 0)
                    <span class="rounded-md bg-red-700 text-sm text-white px-3 py-0.5 mr-2">На одобрении</span>
                @endif
            </div>
            @if($feedback->user_id === auth()->user()->id)
                <div class="flex flex-wrap">
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
