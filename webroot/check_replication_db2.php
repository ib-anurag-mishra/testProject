<?php
/**
 * Author:      Andrew Garner <andrew.garner@rackspace.com>
 * Company:     Rackspace
 * Version:     0.3
 * Description: This script checks the slave status on the configured host 
 *              printing "BAD <description>" or "OK <description>" for failure
 *              or success respectively.
 *              The possible failures could include:
 *              1) connection failure
 *              2) query failure (permissions, network, etc.)
 *              3) fetch failure (???)
 *              4) slave or io thread is not running
 *              5) Unknown master state (seconds_behind_master is null)
 *              6) seconds_behind_master has exceeded the configured threshold
 *              
 *              If none of these condition occur, we asssume success and return
 *              an "OK" response, otherwise we include the error we can find 
 *              (mysqli_connect_error() or $mysqli->error, or problem 
 *               description).  A monitoring system need only check for:
 *              /^BAD/ (alert) or /^OK/ (everybody happy)
 * Changelog:
 *      2008-03-18 16:44:15 - Initial release; dubbed v0.1
 *      2008-03-19 11:09:41 - Cleaned up testing code into a more extensible format; dubbed v0.2
 *      2008-11-04 15:57:59 - Added a quick check that looks for a 'skip_alerts' file in the same
 *                            directory as check_replication.php. This file can be created by an 
 *                            external program (such as a backup program) so that false alerts
 *                            are not generated. Dubbed v0.3 [Tim "Sweetums" Soderstrom]
 */

    error_reporting(E_ALL);
    header("Content-Type: text/plain"); # Not HTML
    $host = "192.168.100.115";
    $user = "rep_monitor";
    $pass = "P9{h4C2djD";
    $sql = "SHOW SLAVE STATUS";
    $skip_file = 'skip_alerts';
    $link = mysql_connect($host, $user, $pass, null);

    if($link)
        $result = mysql_query($sql, $link);
    else {
        printf("BAD: Connection Failed %s", mysql_error());
        mysql_close($link);
        return;
    }

    if($result)
        $status = mysql_fetch_assoc($result);
    else {
        printf("BAD: Query failed - %s\n", mysql_error($link));
        mysql_close($link);
        return;
    }

    mysql_close($link);
    

    $slave_lag_threshold = 60;

    /*
        This is a series of tests in an associative array mapping 
        test_name => test_parameters where test_parameters is a tuple 
        of 3 values:
            [1] Field name from SHOW SLAVE STATUS to check
            [2] An expression to evaluate; $var will be the value from [1]
            [3] Error message on failure; This is printed with a BAD status 
                if [2] evaluates to a non-true value
     */
    $tests = array(
        'test_slave_io_thread' => array('Slave_IO_Running', "\$var === 'Yes'", 
                                        'Slave IO Thread is not running'),
        'test_slave_sql_thread' => array('Slave_SQL_Running', "\$var === 'Yes'", 
                                        'Slave SQL Thread is not running'),
        'test_last_err' => array('Last_Errno', "\$var == 0", 
                                 "Error encountered during replication - "
                                 .$status['Last_Error']),
        'test_master_status' => array('Seconds_Behind_Master', "isset(\$var)", 
                                        'Unknown master status (Seconds_Behind_Master IS NULL)'),
        'test_slave_lag' => array('Seconds_Behind_Master', 
                                  "\$var < \$slave_lag_threshold", 
                                  "Slave is ${status['Seconds_Behind_Master']}s behind master (threshold=$slave_lag_threshold)")
    );

    $epic_fail = false;
    if(is_file($skip_file))
        $epic_fail = false;
    else
    {
        foreach($tests as $test_name => $data) {
            list($field, $expr, $err_msg) = $data;
            $var = $status[$field];
            $val = eval("return $expr;");
            if(!$val) {
                print "BAD: $err_msg\n";         
                $epic_fail = true;
            }
        }
    }

    if(!$epic_fail) {
        print "OK: Checks all completed successfully\n";
    }
?>
