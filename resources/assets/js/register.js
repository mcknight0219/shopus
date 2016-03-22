var Vue = require('vue');
Vue.use(require('vue-resource'));
Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf_token"]').attr('content');


Vue.prototype.notify = function($element, $placement, $content) {
    $element.webuiPopover({
        content: $content,
        placement: $placement,
        trigger: 'manual',
        dismissible: true,
        autoHide: 3500
    });
    $element.webuiPopover('show');
};

new Vue({
    el: '#app',

    data: {
        loginData: {
            email: '',
            password: ''
        }
    },

    methods: {
         login: function() {
            var re = /^\S+@\S+$/;
            if (this.loginData.email === '' || this.loginData.email.match(re) === null) {
                this.notify($('#email'), 'bottom', 'Email entered is not correct');
            } else if (this.loginData.password === '') {
                this.notify($('#password'), 'bottom', 'Password must not be empty');
            } else {
                $('#loginForm').submit();
            }
        },

        notify: function($element, $placement, $content) {
            $element.webuiPopover({
                content: $content,
                placement: $placement,
                trigger: 'manual',
                dismissible: true,
                autoHide: 3500
            });
            $element.webuiPopover('show');
        }
    }
});