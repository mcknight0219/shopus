$(document).ready(function() {

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

    // Remind
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
        $(".borderedbutton").click(function() {
            console.log('Hello');
            $('form').submit();
        });
    }
});

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
    if( $('#profileWeixinId').val() === '' ) {
       $('div#editProfile').webuiPopover({
            content: 'Go here to complete your profile !',
            placement: 'bottom-left',
            trigger: 'manual',
            dimissible: true,
            autoHide: 4500,
            closeable: true
       }).webuiPopover('show'); 
    }
}