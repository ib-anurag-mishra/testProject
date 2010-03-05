<ul>
 <?php foreach($albumResults as $albumResult): ?>
     <li><?php echo $albumResult['Physicalproduct']['Title']; ?></li>
 <?php endforeach; ?>
 <?php foreach($artistResults as $artistResult): ?>
     <li><?php echo $artistResult['Physicalproduct']['ArtistText']; ?></li>
 <?php endforeach; ?>
 <?php foreach($songResults as $songResult): ?>
     <li><?php echo $songResult['Home']['Title']; ?></li>
 <?php endforeach; ?>
</ul> 