<?php

return [
    // Максимально допустимый размер загружаемого файла изображения (в байтах)
    'max_size' =>env('IMAGE_MAX_SIZE', '2048'), // 2 Мб
    // Список разрешенных типов изображений
    'allowed_mime_types' => env('IMAGE_ALLOWED_TYPES', 'image/jpeg,image/png'),
];
