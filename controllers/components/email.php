<?php 
 /**
 * File Name : email.php
 * This is a component to send email from CakePHP using PHPMailer
 */

class EmailComponent
{
  /**
   * Send email using SMTP Auth by default.
   */
    var $from         = 'admin@freegalmusic.com';
    var $fromName     = "FreegalMusic.com";
    var $smtpUserName = '';  // SMTP username
    var $smtpPassword = ''; // SMTP password
    var $smtpHostNames= "localhost";  // specify main and backup server
    var $text_body = null;
    var $html_body = null;
    var $to = null;
    var $toName = null;
    var $subject = null;
    var $cc = null;
    var $bcc = null;
    var $template = 'email/default';
    var $attachments = null;
    var $smtpAuth = false;

    var $controller;

    function startup( &$controller ) {
      $this->controller = &$controller;
    }

    function bodyText() {
      /** This is the body in plain text for non-HTML mail clients*/
      ob_start();
      $temp_layout = $this->controller->layout;
      $this->controller->layout = '';  // Turn off the layout wrapping
      echo $this->controller->render($this->template . '_text'); 
      $mail = ob_get_clean();
      $this->controller->layout = $temp_layout; // Turn on layout wrapping again
      return $mail;
    }

    function bodyHTML() {
      /** This is HTML body text for HTML-enabled mail clients*/
      ob_start();
      $temp_layout = $this->controller->layout;
      $this->controller->layout = 'email';  //  HTML wrapper for my html email in /app/views/layouts
      echo $this->controller->render($this->template . '_html'); 
      $mail = ob_get_clean();
      $this->controller->layout = $temp_layout; // Turn on layout wrapping again
      return $mail;
    }

    function attach($filename, $asfile = '') {
      if (empty($this->attachments)) {
        $this->attachments = array();
        $this->attachments[0]['filename'] = $filename;
        $this->attachments[0]['asfile'] = $asfile;
      } else {
        $count = count($this->attachments);
        $this->attachments[$count+1]['filename'] = $filename;
        $this->attachments[$count+1]['asfile'] = $asfile;
      }
    }


    function send()
    {
      App::import('vendor', 'PHPMailer', array('file' => 'phpmailer/class.phpmailer.php'));
  
      $mail = new PHPMailer();
  
      $mail->IsSMTP();            // set mailer to use SMTP
      $mail->SMTPAuth = $this->smtpAuth;     // turn on SMTP authentication
      $mail->Host   = $this->smtpHostNames;
      $mail->Username = $this->smtpUserName;
      $mail->Password = $this->smtpPassword;
  
      $mail->From     = $this->from;
      $mail->FromName = $this->fromName;
      $mail->AddAddress($this->to, $this->toName );
      $mail->AddReplyTo($this->from, $this->fromName );
	  $mail->ConfirmReadingTo = '';
	  
      $mail->CharSet  = 'UTF-8';
      $mail->WordWrap = 50;  // set word wrap to 50 characters
  
      if (!empty($this->attachments)) {
        foreach ($this->attachments as $attachment) {
          if (empty($attachment['asfile'])) {
            $mail->AddAttachment($attachment['filename']);
          } else {
            $mail->AddAttachment($attachment['filename'], $attachment['asfile']);
          }
        }
      }
  
      $mail->IsHTML(true);  // set email format to HTML
  
      $mail->Subject = $this->subject;
      $mail->Body    = $this->bodyHTML();
      $mail->AltBody = $this->bodyText();
      $result = $mail->Send();
  
      if($result == false ) $result = $mail->ErrorInfo;
  
      return $result;
    }
}