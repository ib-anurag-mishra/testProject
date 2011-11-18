 <?php
 /*
	 File Name : auto_complete.ctp
	 File Description : View page for auto_complete
	 Author : m68interactive
 */
 $finalResults = Array();
 if(count($albumResults) > 0){ 
   foreach($albumResults as $albumResult):
       $finalResults[$albumResult['Song']['Title']] = $albumResult['Song']['Title'];
   endforeach;
 }
 if($finalResults != '')
 {
   foreach($finalResults as $key => $value):
       echo "$key|$value\n";
       endforeach;
 }
 else
 {
   echo "No results found";
 }
 ?>
 