<?php $this->pageTitle = 'Admin'; ?>
<form>
<fieldset>
<legend>Welcome <?php echo $session->read('Auth.User.first_name'); ?></legend>
Welcome to the Administrative Section of <b><i>Freegal Music</i></b>
</fieldset>
</form>