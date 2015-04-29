<?php 
    $user=$this->getUser();
    $formkey = $this->getFormKey();
?>
<h1>LANDING BINGO LOGIN</h1>
<div class='error'>
<?php foreach ($errors as $error) { ?>
    <p><?php echo $error;?></p>
<?php } ?>
</div>
<form id='login-form' action='/user/verify' formkey='<?php echo $formkey?>' method='post'>
    <label for='username'>User Name</label>
    <input type='text' name='username' /><br />
    <label for='password'>Password</label>
    <input class='password' type='password' name='password' />
    <input class='hash' type='hidden' name='hash' /><br />
    <input id='login-form-submit' type='submit' value='Submit' />
</form>
