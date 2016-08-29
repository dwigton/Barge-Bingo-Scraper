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
        $.post('admin/updategame',{ 
                forum_topic_id: $('#forum-topic-id').val(),
                start_time: $('#start-time').val(),
                end_time: $('#end-time').val(),
            }, function(data){
            $('#display-info').html(data); 
            trackPage('admin/updategame');
            setAdminEvents();
        });
        return false;
    });

    $('#start-time').datetimepicker({
        startDate:'+1971/05/01',
        onShow:function( ct ){
            this.setOptions({
                maxDate:jQuery('#end-time').val()?jQuery('#end-time').val():false
            })
        }
    });
    $('#end-time').datetimepicker({
        startDate:'+1971/05/01',
        onShow:function( ct ){
            this.setOptions({
                minDate:jQuery('#start-time').val()?jQuery('#start-time').val():false
            })
        }
    });
}
