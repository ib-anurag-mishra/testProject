<?php
/**
 * Description: View page for login
 * 
 * Copyright 2011, m68 Interactive. All Rights Reserved.
 *
 * @author Robert Richmond <rob@m68interactive.com>
 * @created 03-29-2011 09:41 AM
 */

	header('Content-type: text/xml');
	echo $this->Xml->header();
	echo $this->Xml->serialize($result, array('root' => 'Login', 'format' => 'tags', 'cdata' => true ,'slug' => false));
