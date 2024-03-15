<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelUser;
use App\Models\RoleUser;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    // Очищать базу данных после каждого теста
    use RefreshDatabase;

// ****************************************************************************
//                                store
// ****************************************************************************
    /**
     * Создание бронирования аутентифицированным пользователем (можно)
     * @test
     */
    public function store_can_by_auth()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера(комнаты) отеля
        $room = Room::factory()->create();
        // Данные запроса
        $requestData = [
            // Дата начала заезда
            'started_at' => '2024-03-12',
            // Дата окончания заезда
            'finished_at' => '2024-03-13',
            // Id номера отеля
            'room_id' => $room->id,
        ];
        // Аутентификация пользователя
        $this->actingAs($user);
        // Создать бронирование с данными запроса
        $response = $this->post(route('bookings.store'), $requestData);

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Количество записей в таблице - 1
        $this->assertDatabaseCount('bookings', 1);
        // Таблица содержит запись с переданными данными
        $this->assertDatabaseHas('bookings', $requestData);
    }

    /**
     * Создание бронирования неаутентифицированным пользователем (нельзя)
     * @test
     */
    public function store_can_not_by_not_auth()
    {
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера(комнаты) отеля
        $room = Room::factory()->create();
        // Данные запроса
        $requestData = [
            // Дата начала заезда
            'started_at' => '2024-03-12',
            // Дата окончания заезда
            'finished_at' => '2024-03-13',
            // Id номера отеля
            'room_id' => $room->id,
        ];
        // Создать бронирование с данными запроса
        $response = $this->post(route('bookings.store'), $requestData);

        // * Проверка:
        // Ответ является перенаправлением на страницу авторизации
        $response->assertRedirect(route('login'));
    }

    /**
     * Валидация всех атрибутов, обязательных к заполнению
     * @test
     */
    public function store_validate_all_required_attributes()
    {
        // Создание пользователя
        $user = User::factory()->create();
        // Пустые данные запроса
        $requestData = [
            // Дата начала заезда
            'started_at' => '',
            // Дата окончания заезда
            'finished_at' => '',
            // Id номера отеля
            'room_id' => '',
        ];
        // Аутентификация пользователя
        $this->actingAs($user);
        // Создать бронирование с данными запроса
        $response = $this->post(route('bookings.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['started_at', 'finished_at', 'room_id']);
    }

    /**
     * Валидация всех атрибутов с типом 'integer'
     * @test
     */
    public function store_validate_all_integer_attributes()
    {
        // Создание пользователя
        $user = User::factory()->create();
        // Данные запроса
        $requestData = [
            // Дата начала заезда
            'started_at' => '2024-03-12',
            // Дата окончания заезда
            'finished_at' => '2024-03-13',
            // Id номера отеля
            'room_id' => 'string',
        ];
        // Аутентификация пользователя
        $this->actingAs($user);
        // Создать бронирование с данными запроса
        $response = $this->post(route('bookings.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['room_id']);
    }

    /**
     * Валидация всех атрибутов с типом 'date'
     * @test
     */
    public function store_validate_all_date_attributes()
    {
        // Создание пользователя
        $user = User::factory()->create();
        // Данные запроса
        $requestData = [
            // Дата начала заезда в виде строки, а не даты
            'started_at' => 'string',
            // Дата окончания заезда в виде строки, а не даты
            'finished_at' => 'string',
            // Id номера отеля
            'room_id' => 1,
        ];
        // Аутентификация пользователя
        $this->actingAs($user);
        // Создать бронирование с данными запроса
        $response = $this->post(route('bookings.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['started_at', 'finished_at']);
    }

    /**
     * Валидация - Дата выезда должна быть позже даты заезда
     * @test
     */
    public function store_validate_finished_at_must_be_after_started_at()
    {
        // Создание пользователя
        $user = User::factory()->create();
        // Данные запроса
        $requestData = [
            // Дата начала заезда
            'started_at' => '2024-03-13',
            // Дата окончания заезда
            'finished_at' => '2024-03-12',
            // Id номера отеля
            'room_id' => 1,
        ];
        // Аутентификация пользователя
        $this->actingAs($user);
        // Создать бронирование с данными запроса
        $response = $this->post(route('bookings.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['finished_at']);
    }

// ****************************************************************************
//                                index
// ****************************************************************************
    /**
     * Просмотр таблицы бронирований НЕаутентифицированным пользователем (нельзя)
     * @test
     */
    public function index_can_not_by_not_auth()
    {
        // Создание отелей
        $hotel = Hotel::factory(10)->create();
        // Создание номеров отелей
        $room = Room::factory(10)->create();
        // Создание бронирований
        $booking = Booking::factory(10)->create();

        // Получить бронирования
        $response = $this->get(route('bookings.index'));

        // * Проверка:
        // Ответ является перенаправлением на страницу авторизации
        $response->assertRedirect(route('login'));
    }

    /**
     * Просмотр таблицы бронирований аутентифицированным пользователем (можно)
     * @test
     */
    public function index_can_by_auth()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание и аутентификация пользователя
        $this->actingAs(User::factory()->create());
        // Создание отелей
        $hotel = Hotel::factory(10)->create();
        // Создание номеров отелей
        $room = Room::factory(10)->create();
        // Создание бронирований
        $booking = Booking::factory(10)->create();

        // Получить бронирования
        $response = $this->get(route('bookings.index'));

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Маршрутом возвращен указанный шаблон
        $response->assertViewIs('bookings.index');
        // Текст в шаблоне
        $response->assertSeeText(['Фильтрация по отелю', 'Показать завершённые']);
    }

// ****************************************************************************
//                                admin.index
// ****************************************************************************
    /**
     * Управление бронированиями НЕаутентифицированным пользователем (нельзя)
     * @test
     */
    public function admin_index_can_not_by_not_auth()
    {
        // Создание отелей
        $hotel = Hotel::factory(10)->create();
        // Создание номеров отелей
        $room = Room::factory(10)->create();
        // Создание бронирований
        $booking = Booking::factory(10)->create();

        // Получить бронирования
        $response = $this->get(route('admin.bookings.index'));

        // * Проверка:
        // Ответ является перенаправлением на страницу авторизации
        $response->assertRedirect(route('login'));
    }

    /**
     * Управление бронированиями администратором (можно)
     * @test
     */
    public function admin_index_can_by_admin()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Аутентификация пользователя №1 (админа)
        $this->actingAs(User::first());
        // Создание отелей
        $hotel = Hotel::factory(10)->create();
        // Создание номеров отелей
        $room = Room::factory(10)->create();
        // Создание бронирований
        $booking = Booking::factory(10)->create();

        // Получить бронирования
        $response = $this->get(route('admin.bookings.index'));

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Маршрутом возвращен указанный шаблон
        $response->assertViewIs('bookings.index');
        // Текст в шаблоне
        $response->assertSeeText(['Фильтрация по отелю', 'Показать завершённые']);
    }

    /**
     * Управление бронированиями менеджером (можно)
     * @test
     */
    public function admin_index_can_by_manager()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя №2
        $user = User::factory()->create();
        // Создание отелей
        $hotel = Hotel::factory(10)->create();
        // Создание номеров отелей
        $room = Room::factory(10)->create();
        // Создание бронирований
        $booking = Booking::factory(10)->create();
        // Роль пользователя - менеджер отеля
        RoleUser::factory()->create([
            'user_id' => $user->id,
            'name' => 'manager',
        ]);
        // Отель менеджера
        HotelUser::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel[1]->id,
        ]);

        // Аутентификация пользователя
        $this->actingAs($user);

        // Получить бронирования
        $response = $this->get(route('admin.bookings.index'));

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Маршрутом возвращен указанный шаблон
        $response->assertViewIs('bookings.index');
        // Текст в шаблоне
        $response->assertSeeText(['Фильтрация по отелю', 'Показать завершённые']);
    }

// ****************************************************************************
//                                show
// ****************************************************************************
    /**
     * Просмотр карточки бронирования НЕаутентифицированным пользователем (нельзя)
     * @test
     */
    public function show_can_not_by_not_auth()
    {
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create();

        // Получить бронирование
        $response = $this->get(route('bookings.show', ['booking' => $booking]));

        // * Проверка:
        // Ответ является перенаправлением на страницу авторизации
        $response->assertRedirect(route('login'));
    }

    /**
     * Просмотр карточки бронирования автором бронирования (можно)
     * @test
     */
    public function show_can_by_owner()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        // Аутентификация пользователя
        $this->actingAs($user);

        // Получить бронирование
        $response = $this->get(route('bookings.show', ['booking' => $booking]));

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Маршрутом возвращен указанный шаблон
        $response->assertViewIs('bookings.show');
        // Текст в шаблоне
        $response->assertSeeText([
            $user->name,
            $user->email,
            $booking->room->hotel->name,
            $booking->room->name,
        ]);
    }

// ****************************************************************************
//                                admin.show
// ****************************************************************************
    /**
     * Управление бронированием НЕаутентифицированным пользователем (нельзя)
     * @test
     */
    public function admin_show_can_not_by_not_auth()
    {
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create();

        // Получить бронирование
        $response = $this->get(route('admin.bookings.show', ['booking' => $booking]));

        // * Проверка:
        // Ответ является перенаправлением на страницу авторизации
        $response->assertRedirect(route('login'));
    }

    /**
     * Управление бронированием автором бронирования (можно)
     * @test
     */
    public function admin_show_can_by_owner()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        // Аутентификация пользователя
        $this->actingAs($user);

        // Получить бронирование
        $response = $this->get(route('admin.bookings.show', ['booking' => $booking]));

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Маршрутом возвращен указанный шаблон
        $response->assertViewIs('bookings.show');
        // Текст в шаблоне
        $response->assertSeeText([
            $user->name,
            $user->email,
            $booking->room->hotel->name,
            $booking->room->name,
        ]);
    }

    /**
     * Управление бронированием менеджером отеля (можно)
     * @test
     */
    public function admin_show_can_by_manager()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание чужого бронирования (пользователя №1)
        $booking = Booking::factory()->create([
            'user_id' => 1,
        ]);
        // Роль пользователя - менеджер отеля
        RoleUser::factory()->create([
            'user_id' => $user->id,
            'name' => 'manager',
        ]);
        // Отель менеджера
        HotelUser::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
        ]);

        // Аутентификация пользователя
        $this->actingAs($user);
        // Получить бронирование
        $response = $this->get(route('admin.bookings.show', ['booking' => $booking]));

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Маршрутом возвращен указанный шаблон
        $response->assertViewIs('bookings.show');
        // Текст в шаблоне
        $response->assertSeeText([
            $user->name,
            $user->email,
            $booking->room->hotel->name,
            $booking->room->name,
        ]);
    }

    /**
     * Управление бронированием администратором (можно)
     * @test
     */
    public function admin_show_can_by_admin()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя №2
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        // Аутентификация пользователя №1
        $this->actingAs(User::first());

        // Получить бронирование
        $response = $this->get(route('admin.bookings.show', ['booking' => $booking]));

        // * Проверка:
        // Код ответа 200
        $response->assertStatus(200);
        // Маршрутом возвращен указанный шаблон
        $response->assertViewIs('bookings.show');
        // Текст в шаблоне
        $response->assertSeeText([
            $user->name,
            $user->email,
            $booking->room->hotel->name,
            $booking->room->name,
        ]);
    }

// ****************************************************************************
//                                destroy
// ****************************************************************************
    /**
     * Отмена бронирования неаутентифицированным пользователем (нельзя)
     * @test
     */
    public function destroy_can_not_by_not_auth()
    {
        // Создание пользователя
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        // Удалить бронирование
        $response = $this->delete(route('bookings.destroy', ['booking' => $booking]));

        // * Проверка:
        // Ответ является перенаправлением на страницу авторизации
        $response->assertRedirect(route('login'));
    }

    /**
     * Отмена бронирования автором бронирования (можно)
     * @test
     */
    public function destroy_can_by_owner()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        // Аутентификация пользователя
        $this->actingAs($user);

        // Получить бронирование
        $response = $this->delete(route('bookings.destroy', ['booking' => $booking]));

        // * Проверка:
        // Ответ является перенаправлением
        $response->assertRedirect();
        // Количество записей в таблице - 0
        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Отмена бронирования администратором (можно)
     * @test
     */
    public function destroy_can_by_admin()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя №2
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание бронирования пользователя
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        // Аутентификация пользователя №1
        $this->actingAs(User::first());

        // Получить бронирование
        $response = $this->delete(route('bookings.destroy', ['booking' => $booking]));

        // * Проверка:
        // Ответ является перенаправлением
        $response->assertRedirect();
        // Количество записей в таблице - 0
        $this->assertDatabaseCount('bookings', 0);
    }

    /**
     * Отмена бронирования менеджером отеля (можно)
     * @test
     */
    public function destroy_can_by_manager()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();
        // Создание пользователя
        $user = User::factory()->create();
        // Создание отеля
        $hotel = Hotel::factory()->create();
        // Создание номера отеля
        $room = Room::factory()->create();
        // Создание чужого бронирования пользователя-админа
        $booking = Booking::factory()->create([
            'user_id' => 1,
        ]);
        // Роль пользователя - менеджер отеля
        RoleUser::factory()->create([
            'user_id' => $user->id,
            'name' => 'manager',
        ]);
        // Отель менеджера
        HotelUser::factory()->create([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
        ]);

        // Аутентификация пользователя
        $this->actingAs($user);

        // Получить бронирование
        $response = $this->delete(route('bookings.destroy', ['booking' => $booking]));

        // * Проверка:
        // Ответ является перенаправлением
        $response->assertRedirect();
        // Количество записей в таблице - 0
        $this->assertDatabaseCount('bookings', 0);
    }
}
