<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// APNS setup
$apnsData = array(
  'production'=>array(
    'certificate'		=>'/var/www/freegal_push/certs/apns-prod.pem',
    'ssl'				=>'ssl://gateway.push.apple.com:2195',
    'feedback'			=>'ssl://feedback.push.apple.com:2196'
  ),
  'sandbox'=>array(
    'certificate'		=>'/var/www/freegal_push/certs/apns-dev.pem',
    'ssl'				=>'ssl://gateway.sandbox.push.apple.com:2195',
    'feedback'			=>'ssl://feedback.sandbox.push.apple.com:2196'
  )
);

if(date('w') == 0){
  $curWeekStartDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), (date('d')-date('w'))-6, date('Y')));
  $curWeekEndDate = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), (date('d')-date('w')), date('Y')));
} else {
  $curWeekStartDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), (date('d')-date('w'))+1, date('Y')));
  $curWeekEndDate = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), (date('d')-date('w'))+7, date('Y')));
}

// Production: Google Project ID:  1051258522674
define("ANDRIOD_KEY", "AIzaSyBdS1fBnfF4sevT7RinnPPdWXJyr275XFo");
// Local: Google Project ID:  1051258522674
//define("ANDRIOD_KEY", "AIzaSyBcV1F3cgMQv9r7W_jwOZ-D3F-XQSc7qA8");
define("ANDRIOD_API_URL", "https://android.googleapis.com/gcm/send");
define("CURRENT_WEEK_START_DATE", $curWeekStartDate);
define("CURRENT_WEEK_END_DATE", $curWeekEndDate);

class database
{
  public $db;
  
  public function database()
  {
    //$this->db = new mysqli("192.168.2.178", "infobeans","infobeans", "freegal");
    $this->db = new mysqli("10.181.56.177", "freegal_pushd",'7c2<2]xc6uLC', "freegal");
    //$this->db = new mysqli("192.168.100.114", "freegal_pushp",'o}aH8862gJ^L', "freegal");
  }
}
?>
