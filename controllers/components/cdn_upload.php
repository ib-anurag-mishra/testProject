<?php
 /**
 * CDN Upload function
 * This function will upload images to the cdn
	Author : m68interactive
 */
 
Class CdnUploadComponent extends Object
{
    var $components = array('Session');

    /*
     Function Name : sendFile
     Desc : function used for uploading the file to CDN
    */
    function sendFile($src,$dst)
    {
        $SFTP_HOST = Configure::read('App.SFTP_HOST');
        $SFTP_PORT = Configure::read('App.SFTP_PORT');
        $SFTP_USER = Configure::read('App.SFTP_USER');
        $SFTP_PASS = Configure::read('App.SFTP_PASS');
        
        if(!($con = ssh2_connect($SFTP_HOST,$SFTP_PORT)))
        {
            echo "Not Able to Establish Connection\n";
        }
        else
        {
            if(!ssh2_auth_password($con,$SFTP_USER,$SFTP_PASS))
            {
                echo "fail: unable to authenticate\n";
            }
            else
            {
                $sftp = ssh2_sftp($con);
                if(!ssh2_scp_send($con, $src, $dst, 0644)){
                    echo "error\n";
                }
                else
                {
                    //echo "FILE Sucessfully sent\n";
                }

            }
        }
    }
    /*
     Function Name : deleteFile
     Desc : function used for deleting the file from CDN
    */

    function deleteFile($file){
        $SFTP_HOST = Configure::read('App.SFTP_HOST');
        $SFTP_PORT = Configure::read('App.SFTP_PORT');
        $SFTP_USER = Configure::read('App.SFTP_USER');
        $SFTP_PASS = Configure::read('App.SFTP_PASS');
        
        if(!($con = ssh2_connect($SFTP_HOST,$SFTP_PORT)))
        {
            echo "Not Able to Establish Connection\n";
        }
        else
        {
            if(!ssh2_auth_password($con,$SFTP_USER,$SFTP_PASS))
            {
                echo "fail: unable to authenticate\n";
            }
            else
            {
                $sftp = ssh2_sftp($con);
                if(!ssh2_sftp_unlink($sftp, $file)){
                    echo "error\n";
                }
                else
                {
                    //echo "FILE Sucessfully sent\n";
                }

            }
        }	
   }
	
}
?>