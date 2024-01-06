<x-app-layout>
    <div class="min-h-[calc(100vh-65px)] flex flex-col sm:justify-center items-center bg-gray-100">
        @if(session('success'))
            <div class="sm:max-w-3xl text-center rounded-l-sm shadow bg-green-200 text-base text-green-700 px-12 py-3 mt-6 -mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="sm:max-w-3xl w-full px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-l-sm my-12">
            <div class="text-lg text-gray-700 font-bold mb-3">
                Редактирование отеля
            </div>
            <hr class="mb-4">
            <form method="post" enctype="multipart/form-data" action="{{ route('admin.hotels.update', ['hotel' => $hotel]) }}">
                @csrf
                @method('PUT')

                <p class="text-gray-700 text-base font-bold">
                    Фото:
                </p>
                <div>
                    <label class="placeholder-box">
                        <div class="flex justify-center mb-4">
                            <img class="thumbnail-sm rounded-l-sm" id="preview" src="{{ $hotel->poster_url }}" alt="Изображение отеля">
                        </div>
                        <div>
                            <input class="shadow appearance-none border rounded-l-sm w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="file" name="image" id="image" accept="{{ config('image.allowed_mime_types') }}">
                        </div>
                    </label>
                    @error('image')
                        <div class="error text-sm text-red-600 mb-2">{{ $message }}</div>
                        <div class="no-error mb-4" hidden></div>
                    @else
                        <div class="no-error mb-4"></div>
                    @enderror
                </div>

                <p class="text-gray-700 text-base font-bold">
                    <strong>Название:</strong>
                </p>
                <div>
                    <label class="placeholder-box">
                        <input type="text" class="shadow appearance-none border rounded-l-sm w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" maxlength="100" name="name" value="{{ old('name') ?? $hotel->name }}" required>
                        <span class="placeholder-text">Введите название отеля (не более 100 символов) <strong class="text-red-600">*</strong></span>
                    </label>
                    @error('name')
                        <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                    @else
                        <div class="mb-4"></div>
                    @enderror
                </div>

                <p class="text-gray-700 text-base font-bold">
                    <strong>Адрес:</strong>
                </p>
                <div>
                    <label class="placeholder-box">
                        <textarea class="shadow appearance-none border rounded-l-sm w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" maxlength="500" name="address" required>{{ old('address') ?? $hotel->address }}</textarea>
                        <span class="placeholder-text">Введите адрес отеля (не более 500 символов) <strong class="text-red-600">*</strong></span>
                    </label>
                    @error('address')
                    <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                    @else
                        <div class="mb-4"></div>
                    @enderror
                </div>

                <p class="text-gray-700 text-base font-bold">
                    <strong>Описание:</strong>
                </p>
                <div>
                    <label class="placeholder-box">
                        <textarea class="shadow appearance-none border rounded-l-sm w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="10" name="description" required>{{ old('description') ?? $hotel->description }}</textarea>
                        <span class="placeholder-text">Введите описание отеля <strong class="text-red-600">*</strong></span>
                    </label>
                    @error('description')
                    <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                    @else
                        <div class="mb-4"></div>
                    @enderror
                </div>

                {{-- Удобства --}}
                <p class="text-gray-700 text-base font-bold">
                    <label for="facility"><strong>Удобства:</strong></label>
                </p>
                <div>
                    @foreach($facilities as $facility)
                        <label for="{{ 'facility' . $facility->id }}" class="inline-flex items-center">
                            <input
                                type="checkbox"
                                id="{{ 'facility' . $facility->id }}"
                                name="checkedFacilities[{{ $facility->id }}]"
                                value="{{ $facility->id }}"
                                @if(isset($checkedFacilities[$facility->id]) || isset(old('checkedFacilities')[$facility->id]))
                                    checked
                                @endif
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2">
                            <span class="mr-4 text-sm text-gray-600">{{ $facility->name }}</span>
                        </label>
                        @error('checkedFacilities.' . $facility->id)
                            <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                        @enderror
                    @endforeach
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.hotels.show', ['hotel' => $hotel]) }}">
                        &#8701; Вернуться к отелю
                    </a>
                    <x-button class="ml-4" formnovalidate>
                        Сохранить изменения
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
