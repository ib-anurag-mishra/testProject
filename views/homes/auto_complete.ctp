 <?php
 /*
	 File Name : auto_complete.ctp
	 File Description : View page for auto_complete
	 Author : m68interactive
 */
 $finalResults = Array();
 if(count($output) > 0 && $type == 'album'){ 
   foreach($output as $albumResult):
       $finalResults[$albumResult['Song']['Title']] = $albumResult['Song']['Title'];
   endforeach;
 }
 if(count($output) > 0 && $type == 'artist'){
   foreach($output as $artistResult):
       $finalResults[$artistResult['Song']['ArtistText']] = $artistResult['Song']['ArtistText'];
   endforeach;
 }
 if(count($output) > 0 && $type == 'song'){
   foreach($output as $songResult):
       $finalResults[$songResult['Song']['SongTitle']] = $songResult['Song']['SongTitle'];
   endforeach;
 }
 if(count($output) > 0 && $type == 'composer'){
   foreach($output as $songResult):
       $finalResults[$songResult['Participant']['Name']] = $songResult['Participant']['Name'];
   endforeach;
 }
 if(count($finalResults) > 0)
 {
   foreach($finalResults as $key => $value):
       echo "$key|$value\n";
       endforeach;
 }
 ?>