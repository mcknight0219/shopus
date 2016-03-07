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
        }
    },

    methods: {
        enableProfileEdit: function() {
            this.profileData.editable = true;
        },

        disableProfileEdit: function() {
            this.profileData.editable = false;
        },

        showNameEditor: function(event) {
            var that = this;
            $(event.target).webuiPopover({
                placement: 'right',
                dismissible: false,
                width: '250px',
                closeable: true,
                content: function() {
                    return $('#popoverNameForm').html();
                },
                onShow: function() {
                    that.profileData.editing = true;
                },
                onHide: function() {
                    that.profileData.editing = false;
                }
            });
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
        update: function(event) {
            alert('Hello');

            this.$http.post('profile/edit', {
                "firstName": this.nameFormData.firstName,
                "lastName" : this.nameFormData.lastName
            }, function (data) {
                console.log(data);
            });
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


$(document).ready(function() {
    // First time I feel sick about jquery :(
    if( location.pathname === '/cms/brand' ) {
        $(document).on('click', '.title', function(e) {
            var target = $(e.target);
            var brandId = target.closest('.productcell').attr('data-index-number');
            var orig = target.text();
            target.replaceWith(function() {
                return "<input id=\"brandName\" class=\"title\" style=\"outline:none; border-width: 0 0 2px 0\" type=\"text\" data-source=\"" + orig + "\">";
            });
            $('#brandName').focus();
            $('#brandName').focusout(function() {
                var isEmpty = $(this).val() === '';
                var newVal = isEmpty ? $(this).attr('data-source') : $(this).val();
                $(this).replaceWith(function() {
                   return "<div class=\"title singleline editable\">" + newVal + "</div>";
                });

                if( !isEmpty ) {
                    _postBrandChange(brandId, 'name', newVal);
                }
            })
        });

        $(document).on('click', '.caption', function(e) {
            var target = $(e.target);
            var brandId = target.closest('.productcell').attr('data-index-number');
            var orig = target.text();
            target.replaceWith(function() {
            	return "<input id=\"brandWebsite\" class=\"caption\" style=\"outline:none; border-width: 0 0 2px 0\" type=\"text\" data-source=\"" + orig + "\">";
            });
			$('#brandWebsite').focus();
			$('#brandWebsite').focusout(function() {
                var isEmpty = $(this).val() === '';
				var newVal = isEmpty ? $(this).attr('data-source') : $(this).val();
				$(this).replaceWith(function() {
				 return "<div class=\"caption singleline editable\">" + newVal  + "</div>";
				});
                if( !isEmpty ) {
                    _postBrandChange(brandId, 'website', newVal);
                }
			});
        });

        $(document).on('change', '.selector', function(e) {
            var target = $(e.target);
            var brandId = target.closest('.productcell').attr('data-index-number');
            var file = new FormData();
            if( target[0].files && target[0].files[0] ) {
                file.append('logo', target[0].files[0]);
                $.ajax({
                    url: '/cms/brand/' + brandId + '/edit',
                    type: 'POST',
                    data: file,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function(data, textStatus, xhr) {
                        if( xhr.status === 200) {
                            // jQuery is fragile
                            target.next().attr('src', '/cms/brand/' + brandId + '/logo');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log(error);
                    }
                });
            }
        });
    }

    if( location.pathname === '/cms/product/add' ) {
        $('#fileSelector').change(function() {
            _toggleProgressbar();
            var file = new FormData();
            if( $(this)[0].files &&  $(this)[0].files[0] ) {
                var type = $('form select').val().toLowerCase();
                file.append(type, $(this)[0].files[0]);
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if( e.lengthComputable ) {
                                var percent = e.loaded / e.total * 100;
                                console.log(percent);
                                $('#uploadProgressbar').attr('value', percent);
                            }
                        }, false);
                        
                        return xhr;
                    },
                    url: '/cms/product/photo',
                    type: 'POST',
                    data: file,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function(data, textStatus, xhr) {
                        _toggleProgressbar();
                        $('#fileSelector').val = "";
                    },
                    error: function(jqXHR, status, errorMessage) {
                        console.log(errorMessage);
                    }
                });
            }
        });

        $('.borderedbutton').click(function() {
            _remindEmpty($('form input[name="brand"]'), 'Brand cannot be empty') ||
            _remindEmpty($('form input[name="name"]'),  'Name cannot be empty') ||
            _remindEmpty($('form input[name="price"]'), 'Price cannot be empty') ||
            _remindEmpty($('form textarea'), 'Description cannot be empty') || $('form').submit();
        });
    }
});

function _postBrandChange(brandId, fieldName, value) {
    var o = {};
    o[fieldName] = value;
	$.ajax({ url: '/cms/brand/' + brandId + '/edit', type: 'POST', dataType: 'text', data: o });
}

function _toggleProgressbar() {
    var $selector = $('#uploadProgressbar');
    if( $selector.attr('hidden') === undefined ) {
        $selector.attr('hidden', true);
    } else {
        $selector.removeAttr('hidden');
    }
}
//# sourceMappingURL=all.js.map
