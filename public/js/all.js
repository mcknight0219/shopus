$(document).ready(function() {
    $(".borderedbutton").click(function() {
        var email = $('#email').val();
        if( email.length === 0 ) {
            return;
        }
        else if( !_validateEmail(email) ) {
            
        } else {
            $('form#loginForm').submit();
        }
    })
});

function _validateEmail(email) {
    var re = /^\S+@\S+$/;
    return email.match(re) !== null;
}
//# sourceMappingURL=all.js.map
