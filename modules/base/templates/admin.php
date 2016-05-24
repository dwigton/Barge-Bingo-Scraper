<h2>Administration Actions</h2>
<form id='admin-form' action='/admin/updategame' method='post'>
    <label for='forum-topic-Id'>Forum Topic Id</label>
    <input id='forum-topic-id' type='text' name='forum-topic-id' value="<?php echo $thread_id;?>"/><br />
    <input type='submit' value='Submit' />
</form>
