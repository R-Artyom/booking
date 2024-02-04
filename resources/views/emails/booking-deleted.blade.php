{{--Тело письма "Удаление бронирования"--}}
<h2>Здравствуйте, {{ $booking->user->name }}!</h2>
Служба бронирования отеля <a href="{{ route('hotels.show', ['hotel' => $booking->room->hotel]) }}" target="_blank" title="Откроется в новой вкладке">"{{ $booking->room->hotel->name }}"</a> уведомляет, что <u><b>Бронирование №{{ $booking->id }}</b></u> отменено!<br>
<hr>
<h2>Детали бронирования</h2>
<b>ФИО гостя:</b> {{ $booking->user->name }}<br>
<b>Период проживания:</b> {{ date('d.m.Y', strtotime($booking->started_at)) }} - {{ date('d.m.Y', strtotime($booking->finished_at)) }}<br>
<b>Номер:</b> {{ $booking->room->name }}<br>
<b>Площадь номера:</b> {{ $booking->room->floor_area }} м2<br>
<b>Стоимость номера за ночь:</b> {{ $booking->room->price }} руб.<br>
<b>Общая стоимость номера за весь период проживания:</b> {{ $booking->price }} руб.<br>
<hr>
<br>
С уважением,<br>
служба бронирования <a href="{{ route('hotels.show', ['hotel' => $booking->room->hotel]) }}" target="_blank" title="Откроется в новой вкладке">"{{ $booking->room->hotel->name }}"</a>.
