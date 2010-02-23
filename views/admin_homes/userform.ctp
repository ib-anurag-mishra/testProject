        <?php
        

        echo $this->Form->create('AdminHome', array( 'controller' => 'AdminHome','action' => $formAction));
        //echo $this->element('sql_dump');
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
        <?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['Admin']['id'])); ?>
        <?php echo $this->Form->label('First Name');?><br/>        
        <?php echo $this->Form->input('first_name', array('label' => false, 'value' => $getData['Admin']['first_name'] ) ); ?>        
        <?php echo $this->Form->label('Last Name');?><br/>
        <?php echo $this->Form->input( 'last_name', array('label' => false ,'value' => $getData['Admin']['last_name'])); ?>
        <?php echo $this->Form->label('email');?><br/>
        <?php echo $this->Form->input( 'email', array( 'label' => false ,'value' => $getData['Admin']['email']) ); ?>        
        <?php echo $this->Form->label('Username');?><br/>
        <?php echo $this->Form->input('username', array( 'label' => false ,'value' => $getData['Admin']['username'])); ?>
        <?php echo $this->Form->label('Password');?><br/>
        <?php echo $this->Form->password( 'password', array( 'label' => false,'value' => '') ); ?><br/>
        <?php echo $this->Form->label('Admin Type');?><br/>
        <?php echo $this->Form->input('type_id',array('type' => 'select','label' => false,'options' => $options, 'selected' => $getData['Admintype']['id']));?>  
        <p class="submit"><input type="submit" value="Save" /></p>
        <?php echo $this->Form->end(); ?>
         <fieldset>
        <?php
        echo $session->flash();
        ?>
         </fieldset>
         </fieldset>
         