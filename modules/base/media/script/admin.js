$(document).ready(function(){
    $('#menu-admin').click(function(e){
        unsetClickableUsers();
        $.get('admin', function(data){
           $('#display-info').html(data); 
           trackPage('hopefuls');
           setClickableUsers();
           setAdminEvents();
        });
    });
});

setAdminEvents = function() {
    $("#forum-topic-id").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $("#admin-form").submit();
        }
    });

    $('#admin-form').submit(function(e){
        e.preventDefault();
        $.post('admin/updategame',{ forum_topic_id: $('#forum-topic-id').val() }, function(data){
           $('#display-info').html(data); 
           trackPage('admin/updategame');
           setAdminEvents();
        });
        return false;
    });
}
