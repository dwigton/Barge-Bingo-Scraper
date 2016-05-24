var users = false;
$(document).ready(function(){
    $('.data').click(function(){
        $(this).find('.source-post').toggle();
    });
    $('.data').mouseleave(function(){
        $(this).find('.source-post').hide();
    });
    $('.board td').mouseenter(function(){
        $('#info .square').text($(this).find('h2').text());
        if ($(this).hasClass('taken')) {
            $('#info .name').text($(this).find('p').first().text());
        }
    });
    $('.board td').mouseleave(function(){
        $('#info .square').text('');
        $('#info .name').text('');
    });

    $('#user-name').focus(function(){
        if (users == false) {
            $.getJSON('data/usernamelist', function(data){
                users = data;
                $('#user-name').autocomplete({
                    source: users,
                    select: function(event,ui){
                        $("#lookup").submit();
                    }
                });
            });
        }
    });

    $("#user-name").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $("#lookup").submit();
        }
    });

    $('#lookup').submit(function(e){
        e.preventDefault();
        $.get('data/userdata/'+encodeURIComponent($('#user-name').val()), function(data){
           $('#user-info').html(data); 
           trackPage('data/userdata/'+encodeURIComponent($('#user-name').val()));
        });
        return false;
    });

    $('#menu').menu();
    $('.ui-menu-icon.ui-icon.ui-icon-carat-1-e').remove();

    $('#menu-none').click(function(e){
       unsetClickableUsers();
       trackPage('');
       $('#display-info').html(''); 
    });
    
    $('#menu-hopefuls').click(function(e){
        unsetClickableUsers();
        $.get('hopefuls/false', function(data){
           $('#display-info').html(data); 
           trackPage('hopefuls');
           setClickableUsers();
        });
    });

    $('#menu-hapless').click(function(e){
        unsetClickableUsers();
        $.get('hapless/false', function(data){
           $('#display-info').html(data); 
           trackPage('hapless');
           setClickableUsers();
        });
    });
    
    $('#menu-help').click(function(e){
        unsetClickableUsers();
        $.get('help', function(data){
           $('#display-info').html(data); 
           trackPage('help');
           setClickableUsers();
        });
    });
    
    $('#menu-rules').click(function(e){
        unsetClickableUsers();
        $.get('rules', function(data){
           $('#display-info').html(data); 
           trackPage('rules');
           setClickableUsers();
        });
    });

    $('#menu').mouseleave(function(){
        $('#menu').menu('collapse');
    });
    
});

function setClickableUsers(){
    $('#display-info .users li').click(function(){
            $('#user-name').val($(this).text());
            $('#lookup').submit();
            if ($("html, body").scrollTop() > $('#user-info').offset().top) {
                $("html, body").animate({ scrollTop: $('#user-info').offset().top }, 100);
            }
    });
}

function unsetClickableUsers(){
    $('#display-info .users li').off();
}

function trackPage(pageRoute) {
    if (window._gat && window._gat._getTracker) {
           _gaq.push(['_trackPageview', pageRoute]);
    }
}
