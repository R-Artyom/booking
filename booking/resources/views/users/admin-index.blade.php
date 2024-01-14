<x-app-layout>
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
        <div class="text-2xl text-center md:text-start font-bold mb-4">Пользователи</div>

        {{-- Таблица с пользователями --}}
        <div class="shadow overflow-hidden rounded border-b border-gray-200">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-2 px-2 uppercase font-semibold text-sm">№</th>
                        <th class="text-left py-2 px-2 uppercase font-semibold text-sm">ФИО</th>
                        <th class="text-left py-2 px-2 uppercase font-semibold text-sm">Email</th>
                        <th class="text-left py-2 px-2 uppercase font-semibold text-sm">Роль</th>
                        <th class="text-left py-2 px-2 uppercase font-semibold text-sm">Отели менеджера</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @for($i = 0; $i < count($users); $i++)
                        {{-- Четная строка - белая, нечетная - серая --}}
                        <tr @if($i % 2 !== 0) class="bg-gray-200" @endif >
                            <td class="text-left py-1 px-2">{{ $users[$i]->id }}</td>
                            <td class="text-left py-1 px-2">{{ $users[$i]->name }}
                                @if($users[$i]->id === Auth::user()->id)
                                    <span class="rounded-lg bg-red-700 text-sm text-white px-3 py-0.5 ml-2">Это Вы</span>
                                @endif
                            </td>
                            <td class="text-left py-1 px-2">{{ $users[$i]->email }}</td>

                            {{-- Роль пользователя --}}
                            <td class="text-left py-1 px-2">
                                <form method="post" action="{{ route('admin.users.update', ['user' => $users[$i]]) }}">
                                    @csrf
                                    @method('PUT')

                                    @if($users[$i]->id === Auth::user()->id)
                                        {{-- Текущий пользователь-админ не может поменять себе роль --}}
                                        <span class="">{{ implode(', ', $users[$i]->roles->pluck('description')->toArray()) }}</span>
                                    @else
                                        <select name="roleNames[]" class="py-1 pl-1 pr-10 py-1 pl-1 h-8 w-48 border-gray-400" onchange="this.form.submit()">
                                            <option value="guest" @if(isGuest($users[$i])) selected @endif>Гость</option>
                                            <option value="manager" @if(isManager($users[$i])) selected @endif>Менеджер</option>
                                            <option value="admin" @if(isAdmin($users[$i])) selected @endif>Администратор</option>
                                        </select>
                                    @endif
                                </form>
                            </td>

                            {{-- Отели менеджера --}}
                            <td class="text-left py-1 px-2">
                                <form method="post" action="{{ route('admin.users.update', ['user' => $users[$i]]) }}">
                                    @csrf
                                    @method('PUT')

                                    @if(isManager($users[$i]))
                                        <select name="userHotels" class="py-1 pl-1 pr-10 py-1 pl-1 h-8 w-48 border-gray-400" onchange="this.form.submit()">
                                            {{-- Если нет ни одного отеля, закреплённого за менеджером --}}
                                            @if($users[$i]->hotels->isEmpty()))
                                                <option value="0" selected disabled>Выберите отель</option>
                                                {{-- Список всех доступных отелей --}}
                                                @if(isset($hotels))
                                                    @foreach($hotels as $hotel)
                                                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                                    @endforeach
                                                @endif

                                            {{-- Если есть хотя бы один закреплённый за менеджером отель, то можно добавить или убавить через запятую --}}
                                            @else
                                                {{-- Количество закреплённых отелей --}}
                                                <option selected>Кол-во отелей: {{ $users[$i]->hotels->count() }}</option>

                                                {{-- Перечисление закреплённых за менеджером отелей --}}
                                                @foreach($users[$i]->hotels as $hotel)
                                                    {{-- Будет удаление отеля из списка--}}
                                                    <option value="{{ $hotel->id . ',' . $userHotelsString[$users[$i]->id] }}">{{ $hotel->name }} &#10060;</option>
                                                @endforeach

                                                {{-- Перечисление НЕзакреплённых за менеджером отелей, доступных к закреплению --}}
                                                @if(isset($hotels))
                                                    @foreach($hotels as $hotel)
                                                        @if(!in_array($hotel->id, $userHotelsArray[$users[$i]->id]))
                                                            {{-- Будет добавление отеля в список--}}
                                                            <option value="{{ $hotel->id . ',' . $userHotelsString[$users[$i]->id] }}">{{ $hotel->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Пагинация --}}
        <div class="mt-4">
            {{ $users->onEachSide(1)->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
