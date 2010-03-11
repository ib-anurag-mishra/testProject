 <?php
 $finalResults = Array();
 foreach($albumResults as $albumResult):
     $finalResults[$albumResult['Physicalproduct']['Title']] = $albumResult['Physicalproduct']['Title'];
 endforeach;
 foreach($artistResults as $artistResult):
     $finalResults[$artistResult['Physicalproduct']['ArtistText']] = $artistResult['Physicalproduct']['ArtistText'];
 endforeach;
 foreach($songResults as $songResult):
     $finalResults[$songResult['Home']['Title']] = $songResult['Home']['Title'];
 endforeach;
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
 