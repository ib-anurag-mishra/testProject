<?php

/**
 * @file Streaming.php
 * Class which performs streaming status updated task for all songs record
 * 
 * 
 * you need to to put (&) person at the end of the script command executation
 * */
class Streaming
{

    //set the database connection variables for staging for test only 
    //luther database setting

    /*
      var $STAGE_DB_HOST = 'localhost';
      var $STAGE_DB_USER = 'narendran';
      var $STAGE_DB_PASS = 'NMx{h7b<366g';
      var $STAGE_SONY_DB   = 'sony';
      var $STAGE_FREEGAL_DB   = 'freegal';
      var $STAGE_ORCHARD_DB   = 'theorchard';
     */




    //db1 database setting
    var $STAGE_DB_HOST = '10.181.56.177';
    var $STAGE_DB_USER = 'freegal_test';
    var $STAGE_DB_PASS = 'c45X^E1X7:TQ';
    var $STAGE_SONY_DB = 'sony2';
    var $STAGE_FREEGAL_DB = 'freegal';
    var $STAGE_ORCHARD_DB = 'theorchard';
    //set the database connection for production 
    var $PODUCTION_DB_HOST = '192.168.100.114';
    var $PODUCTION_DB_USER = 'freegal_prod';
    var $PODUCTION_DB_PASS = '}e47^B1EO9hD';
    var $PODUCTION_SONY_DB = 'sony';
    var $PODUCTION_FREEGAL_DB = 'freegal';
    var $PODUCTION_ORCHARD_DB = 'theorchard';
    var $sonyDBConnectionObj;
    var $freegalDBConnectionObj;
    var $orchardDBConnectionObj;
    var $LIVE = '0'; //1-live,0-stage
    var $EnableBigLogs = 0;
    var $EnableShortLogs = 0;
    var $LogsString = '';
    var $ShortLogsString = '';
    var $BigLogsFileObj;
    var $ShortLogsFileObj;
    var $Instance = null;
    var $ProcessedRowsCount = 1;
    var $ChunkSize = 1000;       //default set 1000
    var $LimitIndex = 0;         //default set 0
    var $LimitCount = 100000;    //default set 100000
    //array('US','CA','AU','IT','NZ','GB','IE');

    var $territoryArray = array('US', 'CA', 'AU', 'IT', 'NZ', 'GB', 'IE', 'BM', 'DE');

    /**
     * Constructer, (intialize object) connection to db1 
     *
     * */
    function __construct()
    {

        //set the database connection
        if ($this->LIVE == '1')
        {

            // connect to  Production          
            //connect to sony database
            $this->sonyDBConnectionObj = mysql_connect($this->PODUCTION_DB_HOST, $this->PODUCTION_DB_USER, $this->PODUCTION_DB_PASS) or die('Could not connect to mysql server for sony db of live.');
            mysql_select_db($this->PODUCTION_SONY_DB, $this->sonyDBConnectionObj) or die('Could not select database.');

            //connect to freegal database
            $this->freegalDBConnectionObj = mysql_connect($this->PODUCTION_DB_HOST, $this->PODUCTION_DB_USER, $this->PODUCTION_DB_PASS, true) or die('Could not connect to mysql server for freegal db of live.');
            mysql_select_db($this->PODUCTION_FREEGAL_DB, $this->freegalDBConnectionObj) or die('Could not select database.');

            //connect to orchard database
            $this->orchardDBConnectionObj = mysql_connect($this->PODUCTION_DB_HOST, $this->PODUCTION_DB_USER, $this->PODUCTION_DB_PASS, true) or die('Could not connect to mysql server for freegal db of live.');
            mysql_select_db($this->PODUCTION_ORCHARD_DB, $this->orchardDBConnectionObj) or die('Could not select database.');
        }
        else
        {

            // connect to  stage         
            //connect to sony database
            $this->sonyDBConnectionObj = mysql_connect($this->STAGE_DB_HOST, $this->STAGE_DB_USER, $this->STAGE_DB_PASS) or die('Could not connect to mysql server for sony db of stage.');
            mysql_select_db($this->STAGE_SONY_DB, $this->sonyDBConnectionObj) or die('Could not select sony database .');


            //connect to freegal database
            $this->freegalDBConnectionObj = mysql_connect($this->STAGE_DB_HOST, $this->STAGE_DB_USER, $this->STAGE_DB_PASS, true) or die('Could not connect to mysql server for freegal db of stage.');
            mysql_select_db($this->STAGE_FREEGAL_DB, $this->freegalDBConnectionObj) or die('Could not select freegal database.');

            /*
              //connect to orchard database
              $this->orchardDBConnectionObj = mysql_connect($this->STAGE_DB_HOST, $this->STAGE_DB_USER, $this->STAGE_DB_PASS, true)
              or die('Could not connect to mysql server for freegal db of live.' );
              mysql_select_db($this->STAGE_ORCHARD_DB, $this->orchardDBConnectionObj)
              or die('Could not select orchard database.');
             */
        }
    }

    /**
     * @function  getAllSongsData-1009240
     * Fetches all songs data for processing
     *
     * @return array
     * */
    function getAllSongsData()
    {

        $totRows = $this->LimitCount + $this->LimitIndex;
        $iniTotRows = $totRows;

        $log_id = md5(time());
        $this->LogsString = PHP_EOL . "---------- Request (" . $log_id . ") Start ---" . date('Y-m-d H:i:s') . " -------------" . PHP_EOL;

        while ($totRows > 0)
        {

            $this->ShortLogsString = '';
            $this->LogsString = '';

            $index = $limit = null;
            if ($totRows < $this->ChunkSize)
            {
                $index = $this->LimitIndex;
                $limit = $totRows;
            }
            else
            {
                $index = $this->LimitIndex;
                $limit = $this->ChunkSize;
            }

            /*
              $songQuery ='SELECT Songs.ProdID, Songs.provider_type, Songs.DownloadStatus, Songs.ProductID, Songs.ISRC FROM Songs where provider_type="ioda" and ProdID="1009240" ORDER BY Songs.ProdID ASC
              LIMIT '.$index.', '.$limit;
             */
            $songQuery = 'SELECT Songs.ProdID, Songs.provider_type, Songs.DownloadStatus, Songs.ProductID, Songs.ISRC FROM Songs where Songs.provider_type="sony" ORDER BY Songs.ProdID ASC LIMIT ' . $index . ', ' . $limit;
            $bigQurylog = $songQuery;


            $obj_resultset = mysql_query($songQuery, $this->freegalDBConnectionObj);
            if (mysql_num_rows($obj_resultset) > 0)
            {
                while ($arr_row = mysql_fetch_assoc($obj_resultset))
                {

                    $this->ShortLogsString = '';
                    $this->LogsString = '';

                    $this->ShortLogsString .= PHP_EOL . date('Y-m-d h:i:s') . " SNO: " . $this->ProcessedRowsCount . " ProdID: " . $arr_row['ProdID'] . " ProviderType: " . $arr_row['provider_type'];
                    $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " SNO: " . $this->ProcessedRowsCount . " ProdID: " . $arr_row['ProdID'] . " ProviderType: " . $arr_row['provider_type'] . PHP_EOL;

                    if ($bigQurylog)
                    {
                        $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " getAllSongsData-Query: " . $bigQurylog . PHP_EOL;
                        $bigQurylog = '';
                    }

                    if ($arr_row['provider_type'] == 'sony')
                    {
                        $this->getRecordInSonyDB($arr_row);
                    }
                    else if ($arr_row['provider_type'] == 'ioda')
                    {
                        die;
                        $this->updateIODARecords($arr_row);
                    }
                    else
                    {
                        $this->LogsString .= PHP_EOL . 'provider type not availble. provider type=' . $arr_row['provider_type'] . ' ProdID=' . $arr_row['ProdID'] . PHP_EOL;
                        //continue;
                    }

                    $this->ShortLogsString .= PHP_EOL;

                    if ($this->EnableShortLogs)
                    {
                        fwrite($this->ShortLogsFileObj, $this->ShortLogsString);
                    }

                    if ($this->EnableBigLogs)
                    {
                        fwrite($this->BigLogsFileObj, $this->LogsString);
                    }

                    $this->ProcessedRowsCount++;
                }
            }

            $this->LimitIndex = $this->LimitIndex + $limit;
            $totRows = $iniTotRows - $this->LimitIndex;
        }
    }

    /**
     * @function  openLogsFiles
     * creating the log file
     *
     * @return array
     * */
    function openLogsFiles()
    {

        if ($this->EnableShortLogs)
        {
            $shortLogsFileName = "logs/shortLogs" . $this->Instance . ".txt";
            $this->ShortLogsFileObj = fopen($shortLogsFileName, "a");
        }

        if ($this->EnableBigLogs)
        {
            $BigLogsFileName = "logs/BigLogs" . $this->Instance . ".txt";
            $this->BigLogsFileObj = fopen($BigLogsFileName, "a");
        }
    }

    /**
     * @function  openLogsFiles
     * close the log file
     *
     * @return array
     * */
    function closeLogsFiles()
    {
        if ($this->EnableShortLogs)
        {
            $shortLogsFileName = "logs/shortLogs" . $this->Instance . ".txt";
            if (file_exists($shortLogsFileName))
            {
                fclose($this->ShortLogsFileObj);
            }
        }

        if ($this->EnableBigLogs)
        {
            $BigLogsFileName = "logs/BigLogs" . $this->Instance . ".txt";
            if (file_exists($BigLogsFileName))
            {
                fclose($this->BigLogsFileObj);
            }
        }
    }

    /**
     * @function  getRecordInSonyDB
     * check the song prodid in sony database table
     *
     * @return array
     * */
    function getRecordInSonyDB($arr_row)
    {

        $ProdID = $arr_row['ProdID'];
        $provider_type = $arr_row['provider_type'];
        $sql = "select Distinct SALES_TERRITORY.TERRITORY_CODE,PRODUCT_OFFER.ProdID,Availability.AvailabilityType, Availability.AvailabilityStatus,SALES_TERRITORY.SALES_START_DATE FROM Availability INNER JOIN PRODUCT_OFFER ON Availability.ProdID = PRODUCT_OFFER.ProdID INNER JOIN SALES_TERRITORY ON SALES_TERRITORY.PRODUCT_OFFER_ID = PRODUCT_OFFER.PRODUCT_OFFER_ID  WHERE Availability.AvailabilityType = 'SUBSCRIPTION' AND SALES_TERRITORY.PRICE_CATEGORY = 'SUBSCRIPTION' AND Availability.AvailabilityStatus = 'I' AND PRODUCT_OFFER.ProdID ='" . $ProdID . "'";

        $obj_resultset = mysql_query($sql, $this->sonyDBConnectionObj);

        $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " getRecordInSonyDB-Query: " . $sql . PHP_EOL;

        if (mysql_num_rows($obj_resultset) > 0)
        {
            while ($sony_arr_row = mysql_fetch_assoc($obj_resultset))
            {
                $this->ShortLogsString .= " SonyDb: Yes";
                $this->insertStreamingRecs($sony_arr_row, $provider_type);
            }
        }
        else
        {
            $this->ShortLogsString .= " SonyDb: No";
        }
    }

    /**
     * @function  insertStreamingRecs
     * updated or add the sony records in the freegal countries table accroding to the territory
     *
     * @param $sony_arr_row array records information
     * @return bool
     * */
    function insertStreamingRecs($sony_arr_row, $providerType = 'sony')
    {

        //get all variables
        $prodID = trim($sony_arr_row['ProdID']);
        $salesStartDate = trim($sony_arr_row['SALES_START_DATE']);
        $territoryCode = trim($sony_arr_row['TERRITORY_CODE']);

        //check the existance
        if ($territoryCode && $prodID && $salesStartDate)
        {
            //create courntries table name
            $tableName = strtolower($territoryCode) . "_countries";

            //check the record exist or not in the particular countries table
            $checkSQL = "select ProdID from " . $tableName . " where ProdID='" . $prodID . "' and provider_type='sony' and Territory='" . $territoryCode . "'";
            $checkSQLResultset = mysql_query($checkSQL, $this->freegalDBConnectionObj);

            $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " insertStreamingRecs-Query: " . $checkSQL . PHP_EOL;


            if (mysql_num_rows($checkSQLResultset) > 0)
            {
                //update the records if exist the record
                $updateSQL = "update " . $tableName . " set StreamingStatus=1,StreamingSalesDate='" . $salesStartDate . "' where ProdID='" . $prodID . "' and provider_type='sony' and Territory='" . $territoryCode . "'";
                $this->ShortLogsString .= " Update " . $tableName;
                if (mysql_query($updateSQL, $this->freegalDBConnectionObj))
                {
                    $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " insertStreamingRecs-update-Query: " . $updateSQL . PHP_EOL;
                    $this->LogsString .= PHP_EOL . "Update Successfully: " . PHP_EOL;
                }
                else
                {
                    $this->LogsString .= PHP_EOL . "Update Failed: " . PHP_EOL;
                }
            }
            else
            {
                //insert the record if not exist
                $insertSQL = "insert into " . $tableName . "(ProdID,provider_type,Territory,DownloadStatus,StreamingStatus,StreamingSalesDate) values('" . $prodID . "','" . $providerType . "','" . $territoryCode . "','0','1','" . $salesStartDate . "')";
                $this->ShortLogsString .= " Insert " . $tableName;
                if (mysql_query($insertSQL, $this->freegalDBConnectionObj))
                {
                    $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . "insertStreamingRecs-insert-Query: " . $insertSQL . PHP_EOL;
                    $this->LogsString .= PHP_EOL . "Insert Successfully: " . PHP_EOL;
                }
                else
                {
                    $this->LogsString .= PHP_EOL . "insert Failed: " . PHP_EOL;
                }
            }
        }
    }

    /**
     * @function  updateIODARecords
     * updated or add the ioda records in the freegal countries table accroding to the territory
     *
     * @param $sony_arr_row array records information
     * @return bool
     * */
    function updateIODARecords($iodaRow)
    {

        //get all variables
        $prodID = trim($iodaRow['ProdID']);
        $provider_type = trim($iodaRow['provider_type']);
        $DownloadStatus = trim($iodaRow['DownloadStatus']);
        $productID = trim($iodaRow['ProductID']);
        $ISRC = trim($iodaRow['ISRC']);


        //check the existance
        if (($provider_type == 'ioda') && $prodID && $productID && $ISRC)
        {
            //check about old IODA data
            $iodaQuery = "select is_ioda,ioda_release_id,upc,primary_sales_start_date from album where ioda_release_id='" . $productID . "'";
            $primarySalesDate = '';
            $getIodaProductIDQry = mysql_query($iodaQuery, $this->orchardDBConnectionObj);
            $getIodaProductID = mysql_fetch_assoc($getIodaProductIDQry);
            $primarySalesDate = $getIodaProductID['primary_sales_start_date'];
            $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " updateIODARecords-album-Query: " . $iodaQuery . PHP_EOL;
            if ($getIodaProductID['is_ioda'])
            {
                $productID = trim($getIodaProductID['upc']);
                $this->ShortLogsString .= " AlbumTable: Yes";
            }
            $this->ShortLogsString .= " SalesDate: " . $primarySalesDate;
            //check the record available in orchard countries table
            $getCoutriesListSQL = "select isrc,track_restricted_to,upc from countries where upc='" . $productID . "' and isrc='" . $ISRC . "' ";

            $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " updateIODARecords-country-Query: " . $getCoutriesListSQL . PHP_EOL;
            $checkSQLResultset = mysql_query($getCoutriesListSQL, $this->orchardDBConnectionObj);

            if (mysql_num_rows($checkSQLResultset) > 0)
            {
                while ($countryRow = mysql_fetch_assoc($checkSQLResultset))
                {

                    $this->ShortLogsString .= " CountryTable: Yes";
                    //fetch all contries code
                    $orchardCountriesArr = explode(",", $countryRow['track_restricted_to']);
                    //proccess record  according to the available contries
                    foreach ($this->territoryArray as $territoryCode)
                    {
                        //create courntries table name
                        $tableName = strtolower($territoryCode) . "_countries";
                        //check  available countris exist or not in the orchard countries array
                        if (in_array($territoryCode, $orchardCountriesArr))
                        {

                            //check the record exist or not in the particular countries table
                            $checkSQL = "select SalesDate,ProdID,provider_type,Territory from " . $tableName . " where ProdID='" . $prodID . "' and provider_type='" . $provider_type . "' and Territory='" . $territoryCode . "'";

                            $checkResultset = mysql_query($checkSQL, $this->freegalDBConnectionObj);

                            $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " updateIODARecords-country-Query: " . $checkSQL . PHP_EOL;

                            if (mysql_num_rows($checkResultset) > 0)
                            {
                                while ($countryRowUpdate = mysql_fetch_assoc($checkResultset))
                                {
                                    $this->ShortLogsString .= " Action: Update " . $tableName;
                                    //update the records 
                                    $updateSQL = "update " . $tableName . " set StreamingStatus=1,StreamingSalesDate='" . $countryRowUpdate['SalesDate'] . "' where ProdID='" . $countryRowUpdate['ProdID'] . "' and provider_type='" . $countryRowUpdate['provider_type'] . "' and Territory='" . $territoryCode . "'";
                                    if (mysql_query($updateSQL, $this->freegalDBConnectionObj))
                                    {
                                        $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . " updateIODARecords-update-Query: " . $updateSQL . PHP_EOL;
                                    }
                                    else
                                    {
                                        $this->LogsString .= PHP_EOL . "Error while updating" . PHP_EOL;
                                    }
                                }
                            }
                            else
                            {
                                $this->ShortLogsString .= " Action: Insert " . $tableName;
                                //insert the record if not exist
                                $insertSQL = "insert into " . $tableName . "(ProdID,provider_type,Territory,StreamingStatus,DownloadStatus,StreamingSalesDate) values('" . $prodID . "','" . $provider_type . "','" . $territoryCode . "','0','0','" . $primarySalesDate . "')";

                                if (mysql_query($insertSQL, $this->freegalDBConnectionObj))
                                {
                                    $this->LogsString .= PHP_EOL . date('Y-m-d h:i:s') . "updateIODARecords-insert-Query: " . $insertSQL . PHP_EOL;
                                }
                                else
                                {
                                    $this->LogsString .= PHP_EOL . "Error while inserting" . PHP_EOL;
                                }
                            }
                        }
                        else
                        {
                            $this->ShortLogsString .= " Territory: " . $territoryCode;
                        }
                    }
                }
            }
            else
            {
                $this->ShortLogsString .= " OrchardCountriesQuery: Fail";
            }
        }
    }

}

?>
