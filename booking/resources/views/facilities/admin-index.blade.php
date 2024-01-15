@section('title', 'Удобства')

<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        {{-- Кнопка "Добавить удобство" --}}
        <div class="flex mb-4 justify-between">
            <div class="text-2xl text-center md:text-start font-bold">@yield('title')</div>
            <x-link-button-add href="{{ route('admin.facilities.create') }}">&#10010; Добавить удобство</x-link-button-add>
        </div>

        {{-- Таблица с удобствами --}}
        <div class="shadow overflow-hidden rounded border-b border-gray-200">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-2 px-4 uppercase font-semibold text-sm">№</th>
                        <th class="text-left py-2 px-4 uppercase font-semibold text-sm">Название</th>
                        <th class="text-left py-2 px-4 uppercase font-semibold text-sm">Дата создания</th>
                        <th class="text-left py-2 px-4 uppercase font-semibold text-sm"></th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @for($i = 0; $i < count($facilities); $i++)
                        {{-- Четная строка - белая, н/ч - серая --}}
                        <tr @if($i % 2 !== 0) class="bg-gray-200" @endif >
                            <td class="text-left py-1 px-4">{{ $facilities[$i]->id }}</td>
                            <td class="text-left py-1 px-4">{{ $facilities[$i]->name }}</td>
                            <td class="text-left py-1 px-4">{{ $facilities[$i]->created_at }}</td>
                            <td class="text-left py-1 px-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.facilities.edit', ['facility' => $facilities[$i]]) }}">
                                    &#9998; Редактировать
                                </a>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Пагинация --}}
        <div class="mt-4">
            {{ $facilities->onEachSide(1)->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
