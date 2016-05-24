<?php $logged_in = $user->isLoggedIn(); ?>
<?php if ($logged_in) { ?>
    <h1 class='logged-in'>NSF SPACEX LANDING BINGO - Admin</h1>
<?php } else { ?>
    <h1>NSF SPACEX LANDING BINGO</h1>
<?php } ?>
<?php if ($logged_in) { 
    echo "<a href='" . BASE_URL . "/rebuild'>Rebuild From Forum</a>\n"; 
    echo "<a href='" . BASE_URL . "/user/logout'>Log Out</a>\n"; 
} ?>
<table class='board'>
    <?php
    $grid = $this->getGrid();
    echo "<tr>\n";
    echo "<th></th>\n";
    foreach ($grid['A'] as $index=>$temp) {
        echo "<th>$index</th>\n";
    }
    echo "</tr>\n";
    foreach ($grid as $letter=>$row) {
        echo "<tr>\n";
        echo "<th>$letter</th>\n";
        foreach ($row as $index=>$square) {
            $class = '';
            $title = '';
            $has_failures = count($square['failures']) > 0;
            if ($has_failures) {
                $class = " class='data'";
            }
            if ($square['taken']) {
                $class = " class='data taken'";
//                $title = " title='{$square['name']}'";
            }

            echo "<td{$class}{$title}>\n";
            ?>
            <div class='source-post'>
                <h2><?php echo "$letter-$index" ?></h2>
                <?php if ($square['taken'] ) { ?>
                <p><?php echo $square['name'].': '.$square['date'];?></p>
                <p>
                    <a href='<?php echo $square['link']?>' target='_blank'>Reference Post</a>
                    <?php if ($logged_in) { ?>
                        <a href='<?php echo BASE_URL.'/remove/'.$square['post_id'].'/'.$letter.$index?>'>X</a>
                    <?php } ?>
                </p>
                <?php } ?>
                <?php if ($has_failures) { ?>
                <h3>Failed Selections</h3>
                <ul class='failures'>
                    <?php foreach ($square['failures'] as $failure) { ?>
                        <li>
                            <a href='<?php echo $failure['link'] ?>' target='_blank' class='date'><?php echo $failure['date']?></a>:
                            <span class='poster'><?php echo $failure['name']?></span> -
                            <span class='reason'><?php echo $failure['reason']?></span>
                            <?php if (strlen($failure['counter_link']) > 0) { ?>
                                <a href='<?php echo $failure['counter_link']?>' target='_blank'>Reference</a>
                            <?php } ?>
                            <?php if ($logged_in) { ?>
                                <?php if ($failure['reason'] == 'Not a vote') { ?>
                                <a href='<?php echo BASE_URL.'/reenable/'.$failure['post_id'].'/'.$letter.$index?>'>+</a>
                                <?php } else { ?>
                                <a href='<?php echo BASE_URL.'/remove/'.$failure['post_id'].'/'.$letter.$index?>'>X</a>
                                <?php } ?>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
            <?php

            echo "</td>\n";
        }
        echo "</tr>\n";
    }
    ?>
</table>
<div class='additional'>
    <ul id='menu'>
        <li id='menu-icon'>
            <img src='<?php echo BASE_URL.'/modules/base/media/images/menu-icon.png'?>' />
        <ul>
            <h2 class='ui-state-disabled'>Display&nbsp;Options</h2>
            <li id='menu-none'>None</li>
            <li id='menu-hopefuls'>Hopefuls</li>
            <li id='menu-hapless'>Hapless</li>
            <li id='menu-rules'>Rules</li>
            <li id='menu-help'>Help</li>
            <?php if ($logged_in) { ?>
            <li id='menu-admin'>Admin</li>
            <?php } ?>
        </ul>
        </li>
    </ul>
    <form id='lookup'>
        <label for='user-name'>Username</label>
        <input type='text' name='user-name' id='user-name' />
        <input type='submit' value='Search' />
    </form>
    <div id='info'>
        <span class='square'></span>
        <span class='name'></span>
    </div>
</div>
<div id='user-info'>
</div>
<div id='display-info'>
</div>
