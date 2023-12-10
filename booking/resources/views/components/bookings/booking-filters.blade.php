{{-- Данные для фильтров бронирований --}}
@props(['indexData'])

<div {{ $attributes->merge(['class' => 'bg-gray-50 px-4 py-4 md:px-6 xl:px-8 w-full']) }}>
    <form class="flex justify-between mb-3" method="GET" action="{{ url()->current() }}">
        <div class="flex">
            <div class="flex flex-col mr-3">
                <label for="sort" class="text-gray-700">Сортировка:</label>
                <select name="sort" id="sort" class="h-10 w-60" onchange="this.form.submit()">
                    <option value="id" @if($indexData['sort'] === 'id') selected @endif>По возрастанию номера бронирования</option>
                    <option value="idDesc" @if($indexData['sort'] === 'idDesc') selected @endif>По убыванию номера бронирования</option>
                    <option value="startedAt" @if($indexData['sort'] === 'startedAt') selected @endif>По возрастанию даты заезда</option>
                    <option value="startedAtDesc" @if($indexData['sort'] === 'startedAtDesc') selected @endif>По убыванию даты заезда</option>
                </select>
            </div>
            <div class="flex flex-col mr-3">
                <label for="filterByHotel" class="text-gray-700">Фильтрация по отелю:</label>
                <select name="filterByHotel" id="filterByHotel" class="h-10 w-60" onchange="this.form.submit()">
                    @if(!isset($indexData['filters']['hotel']['id']))
                        <option value="0" selected disabled>Выберите отель</option>
                    @else
                        <option value="0">Очистить фильтр &#10060;</option>
                        <option value="{{ $indexData['filters']['hotel']['id'] }}" @if($indexData['filters']['filterByHotel'] === $indexData['filters']['hotel']['id']) selected @endif>{{ $indexData['filters']['hotel']['name'] }}</option>
                    @endif
                    @if(isset($indexData['dictionaries']['hotel']))
                        @foreach($indexData['dictionaries']['hotel'] as $id => $name)
                            @if($indexData['filters']['filterByHotel'] !== $id)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            @if(request()->route()->getPrefix() === '/admin')
                <div class="flex flex-col mr-3">
                    <label for="filterByUser" class="text-gray-700">Фильтрация по пользователю:</label>
                    <select name="filterByUser" id="filterByUser" class="h-10 w-60" onchange="this.form.submit()">
                        @if(!isset($indexData['filters']['user']['id']))
                            <option value="0" selected disabled>Выберите пользователя</option>
                        @else
                            <option value="0">Очистить фильтр &#10060;</option>
                            <option value="{{ $indexData['filters']['user']['id'] }}" @if($indexData['filters']['filterByUser'] === $indexData['filters']['user']['id']) selected @endif>{{ $indexData['filters']['user']['name'] }}</option>
                        @endif
                        @if(isset($indexData['dictionaries']['user']))
                            @foreach($indexData['dictionaries']['user'] as $id => $name)
                                @if($indexData['filters']['filterByUser'] !== $id)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif
            <div class="flex items-end">
                <div class="flex items-center h-10 w-60">
                    <label for="Toggle1" class="inline-flex items-center space-x-1 cursor-pointer text-gray-800">
                                    <span class="relative">
                                        <input id="Toggle1" type="checkbox" class="hidden peer" name="showOld" value="yes" @if($indexData['showOld'] === 'yes') checked @endif onchange="this.form.submit()">
                                        <div class="w-10 h-6 rounded-full shadow-inner bg-gray-600 peer-checked:bg-blue-500"></div>
                                        <div class="absolute inset-y-0 left-0 w-4 h-4 m-1 rounded-full shadow peer-checked:right-0 peer-checked:left-auto bg-gray-100"></div>
                                    </span>
                        <span class="text-gray-700">Показать завершённые</span>
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>
