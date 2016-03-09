var Vue = require('vue');

Vue.use(require('vue-resource'))

var NameForm = require('./components/NameForm.vue');

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
            firstName: '',
            lastName: '',
            errors: ''
        },

        
    },

    components: {
        'name-form': NameForm
    },

    ready: function() {
        var that = this;
        $(event.target).webuiPopover({
            placement: 'right',
            dismissible: false,
            width: '250px',
            closeable: true,
            trigger: 'manual',
            html: true,
            content: function() {
                return $('#nameFormPopover');
            },
            onShow: function() {
                that.profileData.editing = true;
            },
            onHide: function() {
                that.profileData.editing = false;
            }
        });
    },

    methods: {
        enableProfileEdit: function() {
            this.profileData.editable = true;
        },

        disableProfileEdit: function() {
            this.profileData.editable = false;
        },

        showNameEditor: function(event) {
            
        },

        showAddressEditor: function(event) {
            var that = this;
            $(event.target).webuiPopover({
                placement: 'right',
                dismissible: false,
                width: '250px',
                closeable: true,
                content: function() {
                    return $('#popoverAddressForm').html();
                },
                onShow: function() {
                    that.profileData.editing = true;
                },
                onHide: function() {
                    that.profileData.editing = false;
                }
            });
        },

        // Save changes on server
        updateProfile: function(event) {
            alert('Hello');
            this.$http.post('profile/edit', {
                "firstName": this.nameFormData.firstName,
                "lastName" : this.nameFormData.lastName
            }, function (data) {
                console.log(data);
            });
        },

        updateAddress: function(event) {
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


