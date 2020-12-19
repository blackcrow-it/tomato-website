window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.interceptors.response.use(response => {
    return response.data;
}, error => {
    return Promise.reject(error.response.data);
});

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

window.currency = (number, defaultText = 'Miễn phí') => {
    number = parseInt(number);
    if (isNaN(number) || number <= 0) {
        return defaultText;
    }
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(number);
}

window.redirectPost = (url, postData) => {
    var form = document.createElement('form');
    form.method = 'post';
    form.action = url;

    document.body.appendChild(form);

    postData._token = document.querySelector('meta[name="csrf-token"]').content;

    (function buildInputTag(data, prefix = '') {
        for (var name in data) {
            var inputName = prefix ? (prefix + '[' + name + ']') : name;

            if (typeof(data[name]) === 'object') {
                buildInputTag(data[name], inputName);
            } else {
                if (data[name] === undefined) continue;
                if (data[name] == null) {
                    data[name] = '';
                }
                if (typeof(data[name]) === 'boolean') {
                    data[name] = data[name] ? 1 : 0;
                }

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = inputName;
                input.value = data[name];
                form.appendChild(input);
            }
        }
    })(postData);

    form.submit();
}
