var Vue = require('vue');
Vue.use(require('vue-resource'));
Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf_token"]').attr('content');

var NameForm    = require('./components/NameForm.vue');
var AddressForm = require('./components/AddressForm.vue');
var WeixinForm  = require('./components/WeixinForm.vue');
var UploadModal = require('./components/UploadModal.vue');
var AddProductModal = require('./components/AddProductModal.vue');
var ProductCell = require('./components/ProductCell.vue');

new Vue({
    el: '#app',

    data: {
        profileData: {
            weixin: 'weixin id',
            city: 'city',
            country: 'country',
            name: 'Your Name',
            url: 'photo/profile',
            subscribed: false,
            editable: false,
            editing: false,
            showModal: false,
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

        showQR: function () {
            return this.profileData.weixin !== 'weixin id' && ! this.profileData.subscribed;
        }
    },

    components: {
        'name-form': NameForm,
        'address-form': AddressForm,
        'weixin-form': WeixinForm,
        'modal': UploadModal,
        'add-product': AddProductModal,
        'product-cell': ProductCell
    },

    ready: function () {
        var vm = this;

        this.$http.get('profile/get').then(function (response) { 
            var data = response.data;
            if (data.status() !== 200) {
                return;
            }
            
            if (data.firstName.length > 0 && data.lastName.length > 0) {
                this.profileData.name = data.firstName + ' ' + data.lastName;
            }
            if (data.city.length > 0) {
                this.profileData.city = data.city;
            }
            if (data.country.length > 0) {
                this.profileData.country = data.country;
            }
            if (data.weixin.length > 0) {
                this.profileData.weixin = data.weixin;
            }

            // check if user has subscribed to our offical account
            this.profileData.subscribed = data.subscribed;
        }, function (error) { 
            // what should we do here ?
        });

        this.$http.get('product/all', function (response) {
            vm.cmsData.products = response.data();
        }, function (error) {
            console.log(error);
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

        // Watch if user has been entered anew
        'profileData.weixin': function(val, oldVal) {

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


