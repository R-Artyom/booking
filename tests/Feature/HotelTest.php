<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HotelTest extends TestCase
{
    // Очищать базу данных после каждого теста
    use RefreshDatabase;
    // Подключение фейкера данных
    use WithFaker;

    // Общие настройки каждого теста класса
    protected function setUp(): void
    {
        // Родительские настройки
        parent::setUp();
        // Создание фейковой среды с таким же названием диска, что и реальный (для работы с файлами)
        Storage::fake('public');
    }
// ****************************************************************************
//                                admin.store
// ****************************************************************************
    /**
     * Создание отеля администратором (можно)
     * @test
     */
    public function store_can_by_admin()
    {
        // Отключение перехвата исключений обработчиком Laravel
        $this->withoutExceptionHandling();

        // Создание файла разрешенного размера
        $file = File::create('my_image.jpeg', config('image.max_size'));
        // Создание удобства
        $facility = Facility::factory()->create();
        $checkedFacilities = [
            $facility->id,
        ];
        // Данные запроса
        $requestData = [
            'name' => 'Название',
            'address' => 'Адрес',
            'description' => 'Описание',
            'image' => $file,
            'checkedFacilities' => $checkedFacilities,
        ];

        // Аутентификация пользователя
        $this->actingAs(User::first());

        // Создать отель с данными запроса
        $response = $this->post(route('admin.hotels.store'), $requestData);

        // Получить созданную запись
        $hotel = Hotel::first();

        // * Проверка:
        // Ответ является перенаправлением
        $response->assertRedirect();
        // Количество записей в таблице - 1
        $this->assertDatabaseCount('hotels', 1);
        // Таблица 'hotels' содержит запись с переданными данными
        $this->assertEquals($requestData['name'], $hotel->name);
        $this->assertEquals($requestData['address'], $hotel->address);
        $this->assertEquals($requestData['description'], $hotel->description);
        $this->assertEquals('hotels/' . $file->hashName(), $hotel->getRawOriginal('poster_url'));
        // Загруженный файл находится на диске (getRawOriginal - ссылка на изображение в обход аксессора)
        Storage::disk('public')->assertExists($hotel->getRawOriginal('poster_url'));
    }

    /**
     * Создание отеля НЕаутентифицированным пользователем (нельзя)
     * @test
     */
    public function store_can_not_by_not_auth()
    {
        // Создание файла разрешенного размера
        $file = File::create('my_image.jpeg', config('image.max_size'));
        // Создание удобства
        $facility = Facility::factory()->create();
        $checkedFacilities = [
            $facility->id,
        ];

        // Данные запроса
        $requestData = [
            'name' => 'Название',
            'address' => 'Адрес',
            'description' => 'Описание',
            'image' => $file,
            'checkedFacilities' => $checkedFacilities,
        ];

        // Создать отель с данными запроса
        $response = $this->post(route('admin.hotels.store'), $requestData);

        // * Проверка:
        // Ответ является перенаправлением на страницу авторизации
        $response->assertRedirect(route('login'));
        // Количество записей в таблице - 0
        $this->assertDatabaseCount('hotels', 0);
    }

    /**
     * Валидация всех атрибутов по правилу 'required'
     * @test
     */
    public function store_validate_all_required_attributes()
    {
        // Данные запроса
        $requestData = [];

        // Аутентификация пользователя
        $this->actingAs(User::first());

        // Создать отель с данными запроса
        $response = $this->post(route('admin.hotels.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['name', 'address', 'description', 'image']);
    }

    /**
     * Валидация всех атрибутов с типом 'string'
     * @test
     */
    public function store_validate_all_string_attributes()
    {
        // Создание файла разрешенного размера
        $file = File::create('my_image.jpeg', config('image.max_size'));

        // Данные запроса
        $requestData = [
            'name' => 99,
            'address' => 99,
            'description' => 99,
            'image' => $file,
        ];

        // Аутентификация пользователя
        $this->actingAs(User::first());

        // Создать отель с данными запроса
        $response = $this->post(route('admin.hotels.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['name', 'address', 'description']);
    }

    /**
     * Валидация всех атрибутов по правилу "max"
     * @test
     */
    public function store_validate_all_max_attributes()
    {
        // Создание файла неразрешенного размера
        $file = File::create('my_image.jpeg', config('image.max_size') + 1);
        // Создание удобства
        $facility = Facility::factory()->create();
        $checkedFacilities = [
            $facility->id,
        ];
        // Данные запроса
        $requestData = [
            'name' => $this->faker->password(101, 101),
            'address' => $this->faker->password(501, 501),
            'description' => 'Описание',
            'image' => $file,
            'checkedFacilities' => $checkedFacilities,
        ];

        // Аутентификация пользователя
        $this->actingAs(User::first());

        // Создать отель с данными запроса
        $response = $this->post(route('admin.hotels.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['name', 'address', 'image']);
    }

    /**
     * Валидация всех атрибутов по правилу 'mimetypes'
     * @test
     */
    public function store_validate_all_mimetypes_attributes()
    {
        // Создание файла разрешенного размера, но запрещенного расширения
        $file = File::create('file.doc', config('image.max_size'));
        // Создание удобства
        $facility = Facility::factory()->create();
        $checkedFacilities = [
            $facility->id,
        ];
        // Данные запроса
        $requestData = [
            'name' => 'Название',
            'address' => 'Адрес',
            'description' => 'Описание',
            'image' => $file,
            'checkedFacilities' => $checkedFacilities,
        ];

        // Аутентификация пользователя
        $this->actingAs(User::first());

        // Создать отель с данными запроса
        $response = $this->post(route('admin.hotels.store'), $requestData);

        // * Проверка:
        // Сессия содержит сообщение об ошибке для каждого поля
        $response->assertSessionHasErrors(['image']);
    }
}
