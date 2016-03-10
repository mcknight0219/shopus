var Vue = require('vue');

Vue.use(require('vue-resource'))

var NameForm    = require('./components/NameForm.vue');
var AddressForm = require('./components/AddressForm.vue');
var WeixinForm  = require('./components/WeixinForm.vue');

Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf_token"]').attr('content');

new Vue({
    el: '#app',

    data: {
        loginData: {
            email: '',
            password: ''
        },

        registerData: {
            email: '',
            password: ''
        },

        profileData: {
            weixin: '',
            city: '',
            country: '',
            editable: false,
            editing: false
        },

        nameFormData: {
            firstName: 'Qiang',
            lastName: 'Guo',
            errors: ''
        },

        
    },

    components: {
        'name-form': NameForm,
        'address-form': AddressForm,
        'weixin-form': WeixinForm
    },

    methods: {
        enableProfileEdit: function() {
            this.profileData.editable = true;
        },

        disableProfileEdit: function() {
            this.profileData.editable = false;
        },

        triggerPopover: function(element, content) {
            var that = this;
            element.webuiPopover({
                placement: 'right',
                dismissible: false,
                width: '250px',
                closeable: true,
                trigger: 'manual',
                html: true,
                content: content,
                onShow: function() {
                    that.profileData.editing = true;
                },
                onHide: function() {
                    that.profileData.editing = false;
                }
            });

            element.webuiPopover('show');
        },

        showNameEditor: function(event) {
            this.triggerPopover($(event.target), $('#nameFormPopover'));
        },

        showAddressEditor: function(event) {
           this.triggerPopover($(event.target), $('#addressFormPopover'));
        },

        showWeixinEditor: function(event) {
            this.triggerPopover($(event.target), $('#weixinFormPopover'));
        },

        update: function() {
            alert('Hello');
        },

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

        register: function() {
            var re = /^\S+@\S+$/;
            if (this.registerData.email === '' || this.registerData.email.match(re) === null) {
                this.notify($('#email'), 'bottom', 'Email entered is not correct');
            } else if (this.registerData.password === '') {
                this.notify($('#password'), 'bottom', 'Password must not be empty');
            } else {
                $('#registerForm').submit();
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


