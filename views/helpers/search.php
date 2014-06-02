<?php
 /*
	 File Name : Search.php
	 File Description : helper file for getting search detail
	 Author : m68interactive
 */
class SearchHelper extends AppHelper {
    
   /*
     Function Name : checkDownloadForSearch
     Desc : check download for search
     * 
     *      
     * @param $TerritoryDownloadStatusArray Array       
     * @param $TerritorySalesDateArray Array  
     * @param $territory Int   
    
     *   
     * @return boolean
    */
    function checkDownloadForSearch($TerritoryDownloadStatusArray,$TerritorySalesDateArray,$territory) {
       $downloadSalesDate = '';
       $downloadStatus = '';
       
       //get the donwload status
       if(is_array($TerritoryDownloadStatusArray)){
           foreach ($TerritoryDownloadStatusArray as $territoryDownloadStatusInfo) {
                $Territory_status_array = explode("_", $territoryDownloadStatusInfo);

                if(isset($Territory_status_array[0]) && ($Territory_status_array[0] === $territory)){
                     $downloadStatus = trim($Territory_status_array[1]);
                }
           }
       }else{ 
           //if single value come then
            $Territory_status_array = explode("_", $TerritoryDownloadStatusArray);

            if(isset($Territory_status_array[0]) && ($Territory_status_array[0] === $territory)){
                    $downloadStatus = trim($Territory_status_array[1]);
            }           
       }
       
       //get the donwload sales date
       if(is_array($TerritorySalesDateArray)){
           foreach ($TerritorySalesDateArray as $TerritorySalesDateInfo) {
                $Territory_salesdate_array = explode("_", $TerritorySalesDateInfo);

                if(isset($Territory_salesdate_array[0]) && ($Territory_salesdate_array[0] === $territory)){
                     $downloadSalesDate = trim($Territory_salesdate_array[1]);
                }
           }
       }else{
            //if single value come then
            $Territory_salesdate_array = explode("_", $TerritorySalesDateArray);

            if(isset($Territory_salesdate_array[0]) && ($Territory_salesdate_array[0] === $territory)){
                    $downloadSalesDate = trim($Territory_salesdate_array[1]);
            }           
       }
       
       //check validation for download
       if(($downloadStatus == 1) && ($downloadSalesDate <= date('Y-m-d'))){
           return 1;           
       }else{
           return 0;
       }
        
    }
    
    
    
    
    /*
     Function Name : checkStreamingForSearch
     Desc : check stream for search
     * 
     *      
     * @param $TerritoryDownloadStatusArray Array       
     * @param $TerritorySalesDateArray Array  
     * @param $territory Int   
    
     *   
     * @return boolean
    */
    function checkStreamingForSearch($TerritoryStreamingStatusArray,$TerritoryStreamingSalesDateArray,$territory) {
       $StreamSalesDate = '';
       $StreamStatus = '';
       
      
       //get the donwload status
       if(is_array($TerritoryStreamingStatusArray)){
           foreach ($TerritoryStreamingStatusArray as $territoryStreamStatusInfo) {
                $Territory_status_array = explode("_", $territoryStreamStatusInfo);

                if(isset($Territory_status_array[0]) && ($Territory_status_array[0] === $territory)){
                     $StreamStatus = trim($Territory_status_array[1]);
                }
           }
       }else{
            //if single value come then
            $Territory_status_array = explode("_", $TerritoryStreamingStatusArray);

            if(isset($Territory_status_array[0]) && ($Territory_status_array[0] === $territory)){
                    $StreamStatus = trim($Territory_status_array[1]);
            }           
       }
       
       //get the download sales date
       if(is_array($TerritoryStreamingSalesDateArray)){
           foreach ($TerritoryStreamingSalesDateArray as $TerritorySalesDateInfo) {
                $Territory_salesdate_array = explode("_", $TerritorySalesDateInfo);

                if(isset($Territory_salesdate_array[0]) && ($Territory_salesdate_array[0] === $territory)){
                     $StreamSalesDate = trim($Territory_salesdate_array[1]);
                }
           }
       }else{
            //if single value come then
            $Territory_salesdate_array = explode("_", $TerritoryStreamingSalesDateArray);

            if(isset($Territory_salesdate_array[0]) && ($Territory_salesdate_array[0] === $territory)){
                    $StreamSalesDate = trim($Territory_salesdate_array[1]);
            }           
       }
       
       //check validation for Stream
       if(($StreamStatus == 1) && ($StreamSalesDate <= date('Y-m-d'))){
           return 1;           
       }else{
           return 0;
       }   
    }   
}
?>