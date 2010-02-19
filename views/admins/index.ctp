<?php
echo $form->create('Admin', array( 'action' => 'login')); ?>
        <fieldset>
          <legend>Login Area</legend>
         <?php echo $form->label('Username');?><br/>
          <?php echo $form->input( 'username', array( 'label' => false ) ); ?>
          <?php echo $form->label('Password');?><br/>
          <?php echo $form->password( 'password', array( 'label' => false ) ); ?>
        <p class="submit"><input type="submit" value="Login" /></p>
      <?php echo $form->end(); ?>