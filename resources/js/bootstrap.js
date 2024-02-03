window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

// Обновление изображения после загрузки файла
const inputFile = document.getElementById('image')
if (inputFile) {
    const preview = document.getElementById('preview')
    const error = document.getElementsByClassName('error')
    const noError = document.getElementsByClassName('no-error')
    inputFile.addEventListener('change', updateImage)
    function updateImage() {
        // Изменить путь к отображаемому файлу, если инпут заполнен данными
        if (this.files && this.files.length) {
            preview.src = window.URL.createObjectURL(this.files[0]);
            // Скрыть сообщение об ошибках загрузки файла, если оно есть
            if (error.length) {
                error[0].hidden = true;
                noError[0].hidden = false;
            }
        }
    }
}
