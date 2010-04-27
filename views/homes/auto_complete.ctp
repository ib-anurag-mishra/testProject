 <?php
 $finalResults = Array();
 if(count($albumResults) > 0){ 
   foreach($albumResults as $albumResult):
       $finalResults[$albumResult['Physicalproduct']['Title']] = $albumResult['Physicalproduct']['Title'];
   endforeach;
 }
 if(count($artistResults) > 0){
   foreach($artistResults as $artistResult):
       $finalResults[$artistResult['Physicalproduct']['ArtistText']] = $artistResult['Physicalproduct']['ArtistText'];
   endforeach;
 }
 if(count($songResults) > 0){
   foreach($songResults as $songResult):
       $finalResults[$songResult['Home']['Title']] = $songResult['Home']['Title'];
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
 