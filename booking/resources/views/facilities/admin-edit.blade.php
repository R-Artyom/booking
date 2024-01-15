@section('title', 'Редактирование удобства')

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
            <form method="post" enctype="multipart/form-data" action="{{ route('admin.facilities.update', ['facility' => $facility]) }}">
                @csrf
                @method('PUT')

                {{-- Удобство --}}
                <p class="text-gray-700 text-base font-bold">
                    <strong>Название:</strong>
                </p>
                <div>
                    <label class="placeholder-box">
                        <input type="text" class="shadow appearance-none border rounded-l-sm w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" maxlength="100" name="name" value="{{ old('name') ?? $facility->name }}" required>
                        <span class="placeholder-text">Введите название удобства (не более 100 символов) <strong class="text-red-600">*</strong></span>
                    </label>
                    @error('name')
                        <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
                    @else
                        <div class="mb-4"></div>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-4">
                    {{-- Ссылка "Назад" --}}
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.facilities.index') }}">
                        &#8701; Вернуться к списку удобств
                    </a>

                    {{-- Кнопка "Сохранить" --}}
                    <x-button class="ml-4" formnovalidate>
                        Сохранить изменения
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
