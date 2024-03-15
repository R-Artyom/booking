{{--Тело письма "Создание бронирования"--}}
<h2>Здравствуйте, {{ $booking->user->name }}!</h2>
Служба бронирования отеля <a href="{{ route('hotels.show', ['hotel' => $booking->room->hotel]) }}" target="_blank" title="Откроется в новой вкладке">"{{ $booking->room->hotel->name }}"</a> подтверждает бронирование согласно Вашей заявке.<br>
<hr>
<h2>Детали бронирования</h2>
<u><b>Бронирование №{{ $booking->id }}</b></u><br>
<b>ФИО гостя:</b> {{ $booking->user->name }}<br>
<b>Период проживания:</b> {{ date('d.m.Y', strtotime($booking->started_at)) }} - {{ date('d.m.Y', strtotime($booking->finished_at)) }}<br>
<b>Номер:</b> {{ $booking->room->name }}<br>
<b>Площадь номера:</b> {{ $booking->room->floor_area }} м2<br>
<b>Описание номера:</b> {{ $booking->room->description }}<br>
<b>Тип номера:</b> {{ $booking->room->type }} <br>
<b>Стоимость номера за ночь:</b> {{ $booking->room->price }} руб.<br>
<b>Общая стоимость номера за весь период проживания:</b> {{ $booking->price }} руб.<br>
<b>В стоимость номера входит:</b> {{ implode(', ',$booking->room->facilities->pluck('name')->toArray()) }}<br>
<br>
{{--Ссылка на карточку бронирования--}}
<a href="{{ route('bookings.show', ['booking' => $booking]) }}" target="_blank" title="Откроется в новой вкладке">Посмотреть карточку бронирования</a><br>
<hr>
<br>
Благодарим за выбор нашего отеля!<br>
<br>
С уважением,<br>
служба бронирования <a href="{{ route('hotels.show', ['hotel' => $booking->room->hotel]) }}" target="_blank" title="Откроется в новой вкладке">"{{ $booking->room->hotel->name }}"</a>.
