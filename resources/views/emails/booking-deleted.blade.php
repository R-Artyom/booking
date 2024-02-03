{{--Тело письма "Удаление бронирования"--}}
Служба размещения отеля <a href="{{ route('hotels.show', ['hotel' => $booking->room->hotel]) }}" target="_blank" title="Откроется в новой вкладке">"{{ $booking->room->hotel->name }}"</a> уведомляет, что нижеследующее бронирование ОТМЕНЕНО:<br>
<b>ФИО гостя:</b> {{ $booking->user->name }}<br>
<b>Период проживания:</b> {{ date('d.m.Y', strtotime($booking->started_at)) }} - {{ date('d.m.Y', strtotime($booking->finished_at)) }}<br>
<b>Номер:</b> {{ $booking->room->name }}<br>
<b>Площадь номера:</b> {{ $booking->room->floor_area }} м2<br>
<b>Стоимость номера:</b> {{ $booking->room->price }} руб. за ночь<br>
<b>Общая стоимость номера за весь период проживания:</b> {{ $booking->price }} руб.<br>
<br>
"Отель "{{ $booking->room->hotel->name }}"<br>
