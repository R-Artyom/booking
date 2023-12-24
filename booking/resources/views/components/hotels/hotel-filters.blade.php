{{-- Данные для фильтров и сортировок отелей --}}
@props(['indexData'])

<div {{ $attributes->merge(['class' => 'bg-gray-50 px-4 py-4 md:px-6 xl:px-8 w-full shadow-md']) }}>
    <form class="flex justify-between flex-wrap mb-3 text-sm" method="GET" action="{{ url()->current() }}">

        {{-- Сортировка --}}
        <div class="flex flex-col">
            <label for="sort" class="text-gray-700">Сортировка:</label>
            <select name="sort" id="sort" class="py-1 pl-1 pr-10 py-1 pl-1 pr-10 h-8 w-48 border-gray-400 text-sm" onchange="this.form.submit()">
                <option value="name" @if($indexData['sort'] === 'name') selected @endif>По возрастанию названия отеля</option>
                <option value="nameDesc" @if($indexData['sort'] === 'nameDesc') selected @endif>По убыванию названия отеля</option>
                <option value="address" @if($indexData['sort'] === 'address') selected @endif>По возрастанию адреса отеля</option>
                <option value="addressDesc" @if($indexData['sort'] === 'addressDesc') selected @endif>По убыванию адреса отеля</option>
            </select>
        </div>

        {{-- Фильтрация по отелям --}}
        <div class="flex flex-col">
            <label for="filterByHotelId" class="text-gray-700">Отель:</label>
            <select name="filterByHotelId" id="filterByHotelId" class="py-1 pl-1 pr-10 h-8 w-48 border-gray-400 text-sm" onchange="this.form.submit()">
                @if(!isset($indexData['filters']['hotel']['id']))
                    <option value="0" selected disabled>Выберите отель</option>
                @else
                    <option value="0">Очистить фильтр &#10060;</option>
                    <option value="{{ $indexData['filters']['hotel']['id'] }}" @if($indexData['filters']['filterByHotelId'] === $indexData['filters']['hotel']['id']) selected @endif>{{ $indexData['filters']['hotel']['name'] }}</option>
                @endif
                @if(isset($indexData['dictionaries']['hotel']))
                    @foreach($indexData['dictionaries']['hotel'] as $id => $name)
                        @if($indexData['filters']['filterByHotelId'] !== $id)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Фильтрация по удобствам отеля --}}
        <div class="flex flex-col">
            <label for="filterByFacilityId" class="text-gray-700">Удобства отеля:</label>
            <select name="filterByFacilityId" id="filterByFacilityId" class="py-1 pl-1 pr-10 h-8 w-48 border-gray-400 text-sm" onchange="this.form.submit()">
                @if(!isset($indexData['filters']['facility']['id']))
                    <option value="0" selected disabled>Выберите удобство</option>
                @else
                    <option value="0">Очистить фильтр &#10060;</option>
                    <option value="{{ $indexData['filters']['facility']['id'] }}" @if($indexData['filters']['filterByFacilityId'] === $indexData['filters']['facility']['id']) selected @endif>{{ $indexData['filters']['facility']['name'] }}</option>
                @endif
                @if(isset($indexData['dictionaries']['facility']))
                    @foreach($indexData['dictionaries']['facility'] as $id => $name)
                        @if($indexData['filters']['filterByFacilityId'] !== $id)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Фильтрация по удобствам номеров --}}
        <div class="flex flex-col">
            <label for="filterByRoomFacilityId" class="text-gray-700">Удобства номера:</label>
            <select name="filterByRoomFacilityId" id="filterByRoomFacilityId" class="py-1 pl-1 pr-10 h-8 w-48 border-gray-400 text-sm" onchange="this.form.submit()">
                @if(!isset($indexData['filters']['roomFacility']['id']))
                    <option value="0" selected disabled>Выберите удобство</option>
                @else
                    <option value="0">Очистить фильтр &#10060;</option>
                    <option value="{{ $indexData['filters']['roomFacility']['id'] }}" @if($indexData['filters']['filterByRoomFacilityId'] === $indexData['filters']['roomFacility']['id']) selected @endif>{{ $indexData['filters']['roomFacility']['name'] }}</option>
                @endif
                @if(isset($indexData['dictionaries']['roomFacility']))
                    @foreach($indexData['dictionaries']['roomFacility'] as $id => $name)
                        @if($indexData['filters']['filterByRoomFacilityId'] !== $id)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>

        {{-- Фильтрация по цене --}}
        <div class="flex flex-col">
            <label for="filterByMinPrice" class="text-gray-700">Цена, ₽:</label>
            <div>
                <input name="filterByMinPrice" id="filterByMinPrice" type="text" placeholder="От {{ $indexData['minPrice'] ?? '' }}" class="py-1 px-1 h-8 w-28 border-gray-400 text-sm" value="{{ $indexData['filterByMinPrice'] ?? '' }}" onchange="this.form.submit()">
                <input name="filterByMaxPrice" id="filterByMaxPrice" type="text" placeholder="До {{ $indexData['maxPrice'] ?? '' }}" class="py-1 px-1 h-8 w-28 border-gray-400 text-sm" value="{{ $indexData['filterByMaxPrice'] ?? '' }}" onchange="this.form.submit()">
            </div>
        </div>

        {{-- Фильтрация по датам заезда и выезда --}}
        <div class="flex flex-col">
            <label for="start_date" class="text-gray-700">Даты заезда и выезда:</label>
            <div>
                <input name="start_date" type="date" min="{{ date('Y-m-d') }}" value="{{ $indexData['startDate'] ?? ''  }}" class="py-1 px-1 h-8 w-32 border-gray-400 text-sm" onchange="this.form.submit()">
                <input name="end_date" type="date" min="{{ date('Y-m-d') }}" value="{{ $indexData['endDate'] ?? ''  }}" class="py-1 px-1 h-8 w-32 border-gray-400 text-sm" onchange="this.form.submit()">
            </div>
        </div>
    </form>
</div>
