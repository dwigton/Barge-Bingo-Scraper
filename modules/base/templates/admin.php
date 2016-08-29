<h2>Administration Actions</h2>
<form id='admin-form' action='/admin/updategame' method='post'>
    <label for='forum-topic-Id'>Forum Topic Id</label>
    <input id='forum-topic-id' type='text' name='forum-topic-id' value="<?php echo $thread_id;?>"/><br />
    <p>Start and End times are in UTC.</p>
    <label for='start-time'>Start Date and Time</label>
    <input id="start-time" name="start-time" type="text" value="<?php echo $start_time;?>"/><br />
    <label for='end-time'>End Date and Time</label>
    <input id="end-time" name="end-time" type="text" value="<?php echo $end_time;?>"/><br />
    <input type='submit' value='Submit' />
</form>
