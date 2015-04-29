<?php $logged_in = $admin->isLoggedIn(); ?>
<h3><?php echo $name.(isset($user['square']) ? " has selected {$user['square']}" : " does not have a valid vote.")?></h3>
<table>
    <tr><th>Square</th><th>Reference Post</th><th>Result</th><?php echo ($logged_in ? '<th>Action</th>' : '')?></tr>
    <?php foreach($user['posts'] as $post) { ?>
        <tr>
            <td><?php echo $post['square']?></td>
            <td><a href='<?php echo $post['link'] ?>' target='_blank'><?php echo $post['date']?></a></td>
            <td><?php echo $post['info']?></td>
            <?php if ($logged_in) { ?>
                <td>
                <?php if ($post['info'] == 'Not a vote') { ?>
                <a href='<?php echo BASE_URL.'/reenable/'.$post['post_id'].'/'.str_replace('-','',$post['square'])?>'>+</a>
                <?php } else { ?>
                <a href='<?php echo BASE_URL.'/remove/'.$post['post_id'].'/'.str_replace('-','',$post['square'])?>'>X</a>
                <?php } ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>
