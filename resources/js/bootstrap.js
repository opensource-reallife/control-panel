import _ from 'lodash';
window._ = _;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

import $ from 'jquery';
import { createPopper } from '@popperjs/core';
import 'bootstrap-daterangepicker';

window.$ = window.jQuery = $;
window.Popper = { createPopper };
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error(
        'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
    );
}
import moment from 'moment';
import 'moment/locale/de';

window.moment = moment;
moment.locale('de');

import * as coreui from '@coreui/coreui'
window.coreui = coreui

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

if (!window.Exo) {
    window.Exo = {
        UserId: null,
        UserName: null,
        Rank: null,
        Env: null,
        PusherKey: window.Exo.PusherKey || '',
    };
}

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: window.Exo.PusherKey,
    wsHost: window.location.hostname,
    wsPort: window.Exo.Env === 'production' || window.Exo.Env === 'release-production' ? 443 : 6001,
    forceTLS: window.Exo.Env === 'production' || window.Exo.Env === 'release-production',
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});