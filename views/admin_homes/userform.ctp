        <?php
        

        echo $form->create('AdminHome', array( 'controller' => 'AdminHome','action' => $formAction));
        if(empty($getData))
        {
               $getData['Admin']['first_name'] = "";
               $getData['Admin']['last_name'] = "";
               $getData['Admin']['email'] = "";
               $getData['Admin']['username'] = "";
               $getData['Admin']['id'] = "";
               $getData['Admintype']['id'] = "";
        }
        ?>
        <fieldset>
        <legend><?php echo $formHeader;?></legend>
        <?php echo $form->hidden( 'id', array( 'label' => false ,'value' => $getData['Admin']['id'])); ?>
        <?php echo $form->label('First Name');?><br/>
        <?php echo $form->input( 'first_name', array( 'label' => false ,'value' => $getData['Admin']['first_name']) ); ?>
        <?php echo $form->label('Last Name');?><br/>
        <?php echo $form->input( 'last_name', array( 'label' => false ,'value' => $getData['Admin']['last_name'])); ?>
        <?php echo $form->label('email');?><br/>
        <?php echo $form->input( 'email', array( 'label' => false ,'value' => $getData['Admin']['email']) ); ?>
        <?php echo $form->label('Username');?><br/>
        <?php echo $form->input('username', array( 'label' => false ,'value' => $getData['Admin']['username'])); ?>
        <?php echo $form->label('Password');?><br/>
        <?php echo $form->password( 'password', array( 'label' => false ) ); ?><br/>
        <?php echo $form->label('Admin Type');?><br/>
        <?php echo $form->select('type_id',$options,$getData['Admintype']['id']) ;?>
        <p class="submit"><input type="submit" value="Save" /></p>
        <?php echo $form->end(); ?>
         <fieldset>
        <?php
        echo $session->flash();
        ?>
         </fieldset>
         </fieldset>
         
        