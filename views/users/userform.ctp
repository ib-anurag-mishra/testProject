        <?php
        $this->pageTitle = 'Admin';
        echo $this->Form->create('User', array( 'controller' => 'User','action' => $formAction));
       // echo $this->element('sql_dump');
        if(empty($getData))
        {
               $getData['User']['first_name'] = "";
               $getData['User']['last_name'] = "";
               $getData['User']['email'] = "";
               $getData['User']['username'] = "";
               $getData['User']['id'] = "";
               $getData['Group']['id'] = "";
        }
        ?>
        <fieldset>
        <legend><?php echo $formHeader;?></legend>
        <?php echo $this->Form->hidden( 'id', array( 'label' => false ,'value' => $getData['User']['id'])); ?>
        <?php echo $this->Form->label('First Name');?><br/>        
        <?php echo $this->Form->input('first_name', array('label' => false, 'value' => $getData['User']['first_name'] ) ); ?>        
        <?php echo $this->Form->label('Last Name');?><br/>
        <?php echo $this->Form->input( 'last_name', array('label' => false ,'value' => $getData['User']['last_name'])); ?>
        <?php echo $this->Form->label('email');?><br/>
        <?php echo $this->Form->input( 'email', array( 'label' => false ,'value' => $getData['User']['email']) ); ?>
        <?php echo $this->Form->label('Password');?><br/>
        <?php echo $this->Form->input('password', array('label' => false,'value' => '') ); ?>
        <?php echo $this->Form->label('Admin Type');?><br/>
        <?php echo $this->Form->input('type_id',array('type' => 'select','label' => false,'options' => $options, 'selected' => $getData['Group']['id'])) ;?>        
        <p class="submit"><input type="submit" value="Save" /></p>
        <?php echo $this->Form->end(); ?>
        <?php
        echo $session->flash();
        ?>

         </fieldset>
         
        