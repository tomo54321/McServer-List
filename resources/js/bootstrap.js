window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


window.$.fn.isInViewport = function() {
    var elementTop = window.$(this).offset().top;
    var elementBottom = elementTop + window.$(this).outerHeight();

    var viewportTop = window.$(window).scrollTop();
    var viewportBottom = viewportTop + window.$(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
};