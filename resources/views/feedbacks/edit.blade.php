@section('title', "Редактирование отзыва к отелю \"{$feedback->hotel->name}\"")

<x-app-layout>
    <div class="min-h-[calc(100vh-65px)] flex flex-col sm:justify-center items-center bg-gray-100">
        @if(session('success'))
            <div class="sm:max-w-3xl text-center rounded-l-sm shadow bg-green-200 text-base text-green-700 px-12 py-3 mt-6 -mb-6">
                {{ session('success') }}
            </div>
        @endif
        <div class="sm:max-w-3xl w-full px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-l-sm my-12">
            <div class="text-lg text-gray-700 font-bold mb-3">
                @yield('title')
            </div>
            <hr class="mb-4">
            <form method="post" enctype="multipart/form-data" action="{{ route('feedbacks.update', ['feedback' => $feedback]) }}">
                @csrf
                @method('PUT')

                {{-- Оценка --}}
                <p class="text-gray-700 text-base font-bold">
                    <label for="facility"><strong>Оцените отель</strong></label>
                </p>
                <div>
                    @for($i = 1; $i < 6; $i++)
                        <label for="{{ 'feedback' . $i }}" class="inline-flex items-center">
                            <input
                                    type="radio"
                                    name="rating"
                                    value="{{ $i }}"
                                    id="{{ 'feedback' . $i }}"
                                    @if((old('rating') !== null ? (int) old('rating') : $feedback->rating) === $i) checked @endif
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2">
                            <span class="mr-4 text-sm text-gray-600">{{ $i }}</span>
                        </label>
                    @endfor

                    @error('rating')
                        <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                    @else
                        <div class="mb-4"></div>
                    @enderror
                </div>

                {{-- Отзыв --}}
                <p class="text-gray-700 text-base font-bold">
                    <strong>Расскажите об отеле</strong>
                </p>
                <div>
                    <label class="placeholder-box">
                        <textarea class="shadow appearance-none border rounded-l-sm w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="5" maxlength="5000" name="text" required>{{ old('text') ?? $feedback->text }}</textarea>
                        <span class="placeholder-text">Расскажите об отеле (не более 5000 символов) <strong class="text-red-600">*</strong></span>
                    </label>
                    @error('text')
                    <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                    @else
                        <div class="mb-4"></div>
                        @enderror
                </div>

                <div class="flex items-center justify-between mt-4">
                    {{-- Ссылка "Отмена" --}}
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('feedbacks.index', ['hotel' => $feedback->hotel]) }}">
                        &#8701; Назад
                    </a>

                    {{-- Кнопка "Отправить" --}}
                    <x-button class="ml-4" formnovalidate>
                        Отправить
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
