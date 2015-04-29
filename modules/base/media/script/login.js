$(document).ready(function(){
    $('#login-form').submit(function(e){
        e.preventDefault();
        var password=Sha1.hash($(this).attr('formkey')+$(this).find('.password').val());
        $(this).find('.password').val("");
        $(this).find('.hash').val(password);
        $(this).off().submit();
        return false;
    });
});
