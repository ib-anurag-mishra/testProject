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
 if(count($artistResults) > 0){
   foreach($artistResults as $artistResult):
       $finalResults[$artistResult['Song']['ArtistText']] = $artistResult['Song']['ArtistText'];
   endforeach;
 }
 if(count($songResults) > 0){
   foreach($songResults as $songResult):
       $finalResults[$songResult['Song']['SongTitle']] = $songResult['Song']['SongTitle'];
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