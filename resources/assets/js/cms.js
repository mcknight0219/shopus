var Vue = require('vue');
Vue.use(require('vue-resource'));
Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf_token"]').attr('content');

var NameForm        = require('./components/NameForm.vue');
var AddressForm     = require('./components/AddressForm.vue');
var UploadModal     = require('./components/UploadModal.vue');
var AddProductModal = require('./components/AddProductModal.vue');
var ProductCell     = require('./components/ProductCell.vue');

new Vue({
    el: '#app',

    data: {
        profileData: {
            city: 'city',
            country: 'country',
            name: 'Your Name',
            url: 'photo/profile',
            subscribed: false,
            editable: false,
            editing: false,
            showModal: false,
            ticket: '',
            ticketUrl: ''
        },

        cmsData: { 
            showAddProductModal: false,
            products: []
        }
    },

    computed: {
        address: function () {
            return this.profileData.city + ' ' + this.profileData.country;
        },

        ticketUrl: function () {
            if (this.profileData.ticket.length > 0) {
                return ;
            }
        }
    },

    components: {
        'name-form': NameForm,
        'address-form': AddressForm,
        'modal': UploadModal,
        'add-product': AddProductModal,
        'product-cell': ProductCell
    },

    ready: function () {
        var vm = this;

        this.$http.get('profile/get').then(function (response) { 
            if (response.status !== 200) {
                return;
            }

            var data = response.data;
            if (data.firstName.length > 0 && data.lastName.length > 0) {
                vm.profileData.name = data.firstName + ' ' + data.lastName;
            }
            if (data.city.length > 0) {
                vm.profileData.city = data.city;
            }
            if (data.country.length > 0) {
                vm.profileData.country = data.country;
            }
            if (data.weixin.length > 0) {
                vm.profileData.subscribed = true;
            } else {
                // need display qr ticket photo here
                vm.$http.get('profile/qr').then(function (response) {
                    vm.profileData.ticket = response.data.ticket;
                    vm.profileData.ticketUrl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" + encodeURI(vm.profileData.ticket);
                });
            }
        }, function (error) { 
            // what should we do here ?
        });

        this.$http.get('product/all').then(function (response) {
            vm.cmsData.products = response.data;
        });
    },

    watch: { 
        'profileData.showModal': function(val, oldVal) { 
            if (oldVal && ! val) {
                var i = this.profileData.url.indexOf('?');
                var url = this.profileData.url;
                if (i !== -1) {
                    url = url.substring(0, i);
                }

                this.profileData.url = url + "?" + (new Date()).getTime();
            }
        },

        'cmsData.addShowProductModal': function(val, oldVal) { 
            // Modal closed, need to update product info
            if (oldVal && ! val) { 
            
            }
        }
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
        }
    }
});


