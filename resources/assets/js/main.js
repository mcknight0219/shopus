$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') }
    });

    if( location.pathname === '/cms/login' || location.pathname === '/cms/register' ) { 

        $(".borderedbutton").click(function() {
            var email = $('#email').val();
            var pass  = $('#password').val();
            if( !_validateEmail(email) ) {
                $('#email').webuiPopover({
                    content: 'Email entered is not correct',
                    placement: 'bottom',
                    trigger: 'manual',
                    dismissible: true,
                    autoHide: 2500
                });
                $('#email').webuiPopover('show');

                return;
            }

            if( !_validatePassword(pass) ) {
                $('#password').webuiPopover({
                    content: 'Password must be at least six letters long and contain valid ascii letters',
                    placement: 'bottom',
                    trigger: 'manual',
                    dismissible: true,
                    autoHide: 2500
                });
                $('#password').webuiPopover('show');

                return;
            }

            var pass2 = $('#passwordAgain').val();
            // it's register page, check if retyped password agrees with original one
            if( pass2 !== undefined ) {
                if( pass !== pass2 ) {
                    $('#passwordAgain').webuiPopover({
                        content: 'Retyped password doesn\'t match',
                        placement: 'bottom',
                        trigger: 'manual',
                        dismissible: true,
                        autoHide: 2500
                    });
                    $('#passwordAgain').webuiPopover('show');
                    return;
                }
            }

            $('form').submit();
        });
    }

    // Remind user if no weixin id is associated
    if( location.pathname === '/cms') {
        _remindProfileEmpty();
         // On Cms homepage, user can click to edit their profile
        $('div#editProfile').click(function() {
            window.location.href='/cms/profile/edit';        
        });
    
        $('div#addProduct').click(function() {
            window.location.href='/cms/product/add';
        })
        // Toggle fold/unfold
        $('div#unfoldProduct').click(function() {
            var klass = $('div#unfoldProduct > i').attr('class');
            var dir = klass.match(/fa-chevron-(up|down)/)[1];
            if( dir === 'up' ) {
                klass = klass.replace(dir, 'down');
            } else {
                klass = klass.replace(dir, 'up');
            }
            $('div#unfoldProduct > i').attr('class', klass);
        });
    }

    if( location.pathname === '/cms/profile/edit') {
        if( $('form input[name="weixin"]').val() !== "" ) {
            $('form input[name="weixin"]').prop('readonly', true);
        }

        // if there is default text already filled
       ['address', 'city'].forEach(function(field) {
            if( $('form input[name="' + field + '"]').val() !== "" ) {
                $('form input[name="' + field + '"]').click(function() {
                    $(this).select();
                })
            }
       }); 

        $(".borderedbutton").click(function() {
            if( $('form input[name="weixin"]').val() === "" ) {
                $('form input[name="weixin"]').webuiPopover({
                    content: 'Please link your Weixin ID',
                    placement: 'bottom-right',
                    trigger: 'manual',
                    dismissible: true,
                    autoHide: 4500
                }).webuiPopover('show');
                
                return;
            }
            $('form').submit();
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
                    success: function(response) {
                        _toggleProgressbar();
                        $('#fileSelector').val = "";
                    },
                    error: function(jqXHR, status, errorMessage) {
                        console.log(errorMessage);
                    }
                })
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

function _toggleProgressbar() {
    var $selector = $('#uploadProgressbar');
    if( $selector.attr('hidden') === undefined ) {
        $selector.attr('hidden', true);
    } else {
        $selector.removeAttr('hidden');
    }
}

function _validateEmail(email) {
    var re = /^\S+@\S+$/;
    return email.match(re) !== null;
};

function _validatePassword(pass) {
    if( pass.length < 6 ) return false;
    var re = /[\x00-\x7f]/; 
    for( var i = 0; i < pass.length; ++i ) {
        if( !re.test(pass.charAt(i)) ) return false;
    }
    return true;
};

function _remindProfileEmpty() {
    if( $('#profileWeixinId').text() === '' ) {
       $('div#editProfile').webuiPopover({
            content: 'Go here to complete your profile !',
            placement: 'bottom',
            trigger: 'manual',
            dimissible: true,
            autoHide: 4500,
            closeable: true
       }).webuiPopover('show'); 
    }
};

function _remindEmpty(selector, msg) {
    return selector.val() === '' &&
    selector.webuiPopover({
        content: msg,
        placement: 'bottom',
        trigger: 'manual',
        dismissible: true,
        autoHide: 4500,
        closeable: true
    }).webuiPopover('show');
}