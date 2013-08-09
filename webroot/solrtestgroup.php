<?php
// make sure browsers see this page as utf-8 encoded HTML
header('Content-Type: text/html; charset=utf-8');
$limit = 10;
$keyword = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : false;
$results = false;

if ($keyword )
{
	// The Apache Solr Client library should be on the include path
	// which is usually most easily accomplished by placing in the
	// same directory as this script ( . or current directory is a default
	// php include path entry in the php.ini)
	require_once('Apache/Solr/Service.php');

	// create a new solr service instance - host, port, and webapp
	// path (all defaults in this example)
	if($type == 'video'){
		$core = '/solr/freegalmusicvideo';
	} else {
		$core = '/solr/freegalmusic';
	}
	$solr = new Apache_Solr_Service('192.168.100.24', 8080, $core);

	// if magic quotes is enabled then stripslashes will be needed
  
	if (get_magic_quotes_gpc() == 1)
	{
		$keyword = stripslashes($keyword );
	}
	
	$searchkeyword = str_replace(array(' ','(',')','"',':','!','{','}','[',']','^','~','*','?'), array('\ ','\(','\)','\"','\:','\!','\{','\}','\[','\]','\^','\~','\*','\?'), $keyword);
	
	switch($type){
        case 'song':
            $query = '(TSongTitle:(*'.strtolower($searchkeyword).'*) OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;
        case 'genre':
            $query = '(TGenre:(*'.strtolower($searchkeyword).'*) OR Genre:'.$searchkeyword.')';
            $field = 'Genre';
            break;
        case 'album':
            $query = '(TTitle:(*'.strtolower($searchkeyword).'*) OR Title:'.$searchkeyword.' OR TArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:'.$searchkeyword.' OR TComposer:(*'.strtolower($searchkeyword).'*) OR Composer:'.$searchkeyword.')';
            $field = 'Title';
            break;
        case 'artist':
            $query = '(TArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:'.$searchkeyword.')';
            $field = 'ArtistText';
            break;
        case 'label':
            $query = '(TLabel:(*'.strtolower($searchkeyword).') OR Label:'.$searchkeyword.')';
            $field = 'Label';
            break;
        case 'video':
            $query = '(TVideoTitle:(*'.strtolower($searchkeyword).'*) OR VideoTitle:('.$searchkeyword.')  OR TArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:('.$searchkeyword.'))';
            $field = 'VideoTitle';
            break;
        case 'composer':
            $query = '(TComposer:(*'.strtolower($keyword).'*) OR Composer:'.$searchkeyword.')';
            $field = 'Composer';
            break;
		case 'all':
			$query = '((TSongTitle:(*'.strtolower($searchkeyword).'*) OR CGenre:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CTitle:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CArtistText:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CLabel:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CComposer:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*)) OR (SongTitle:('.$searchkeyword.') OR Title:('.$searchkeyword.') OR ArtistText:('.$searchkeyword.') OR Composer:('.$searchkeyword.')))';
			break;
        default:
            $query = '((TSongTitle:(*'.strtolower($searchkeyword).'*) OR CGenre:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CTitle:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CArtistText:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CLabel:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*) OR CComposer:(*'.strtolower(str_replace(array(' ','!'),array('\ ','\!'),$keyword)).'*)) OR (SongTitle:('.$searchkeyword.') OR Title:('.$searchkeyword.') OR ArtistText:('.$searchkeyword.') OR Composer:('.$searchkeyword.')))';
            break;
        /*default:
			$query = '(TSongTitle:('.strtolower($searchkeyword).') OR SongTitle:'.$searchkeyword.')';
            $field = 'SongTitle';
            break;*/
	}
  
	$additionalParams = array(
		'group' => 'true',
		'group.field' => array(
			$field
		),
		'group.query' => $query
	);

	// in production code you'll always want to use a try /catch for any
	// possible exceptions emitted  by searching (i.e. connection
	// problems or a query parsing error)
	if($type!='all'){
		try
		{
			echo "Query Started at : ".time();
			if(!empty($additionalParams)){
				$results = $solr->search($query, 0, $limit, $additionalParams);
			} else {
				$results = $solr->search($query, 0, $limit);
			}
			echo "Query Ended at : ".time();
		}
		catch (Exception $e)
		{
			// in production you'd probably log or email this error to an admin
			// and then show a special message to the user but for this example
			// we're going to show the full exception
			die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
		}
	} else {
		$queries = array(array('query'=>'(TSongTitle:(*'.strtolower($searchkeyword).'*) OR SongTitle:'.$searchkeyword.')','field'=>'SongTitle'),
			array('query'=>'(TArtistText:(*'.strtolower($searchkeyword).'*) OR ArtistText:'.$searchkeyword.')','field'=>'ArtistText'),
			array('query'=>'(TGenre:(*'.strtolower($searchkeyword).'*) OR Genre:'.$searchkeyword.')','field'=>'Genre')
		);
		
		foreach($queries as $query){
		
			$additionalParams = array(
				'group' => 'true',
				'group.field' => array(
					$query['field']
				),
				'group.query' => $query['query']
			);
		
			try
			{
				echo "Query Started at : ".time();
				if(!empty($additionalParams)){
					$results[] = $solr->search($query['query'], 0, $limit, $additionalParams);
				} else {
					$results[] = $solr->search($query['query'], 0, $limit);
				}
				echo "Query Ended at : ".time();
			}
			catch (Exception $e)
			{
				// in production you'd probably log or email this error to an admin
				// and then show a special message to the user but for this example
				// we're going to show the full exception
				die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
			}
		}
	}
}

?>
<html>
	<head>
		<title>PHP Solr Client Example</title>
	</head>
	<body>
		<form  accept-charset="utf-8" method="get">
			<label for="q">Search:</label>
			<input id="q" name="q" type="text" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'utf-8'); ?>"/>
			<select id="type" name="type" >
				<option value="all" <?php echo (($type=="all")?"selected='selected'":""); ?>>All</option>
				<option value="album" <?php echo (($type=="album")?"selected='selected'":""); ?>>Album</option>
				<option value="artist" <?php echo (($type=="artist")?"selected='selected'":""); ?>>Artist</option>
				<option value="genre" <?php echo (($type=="genre")?"selected='selected'":""); ?>>Genre</option>
				<option value="song" <?php echo (($type=="song")?"selected='selected'":""); ?>>Song</option>
				<option value="label" <?php echo (($type=="label")?"selected='selected'":""); ?>>Label</option>
				<option value="video" <?php echo (($type=="video")?"selected='selected'":""); ?>>Video</option>
			</select>
	  <input type="submit"/>
    </form>
<?php

// display results
if ($results)
{
  $total = (int) $results->response->numFound;
  $start = min(1, $total);
  $end = min($limit, $total);
?>
    <div>Results <?php echo $start; ?> - <?php echo $end;?> of <?php echo $total; ?>:</div>
    <ol>
<?php
  // iterate result documents
  print_r($results);
  /*foreach ($results->response->docs as $doc)
  {
?>
      <li>
        <table style="border: 1px solid black; text-align: left">
<?php
    // iterate document fields / values
    foreach ($doc as $field => $value)
    {
?>
          <tr>
            <th><?php echo htmlspecialchars($field, ENT_NOQUOTES, 'utf-8'); ?></th>
            <td>
			<?php if(!is_array($value)){
					echo htmlspecialchars($value, ENT_NOQUOTES, 'utf-8'); 
				} else {
					print_r($value); 
				}
			?>
			</td>
          </tr>
<?php
    }
?>
        </table>
      </li>
<?php
  }
?>
    </ol>
<?php
*/
}
?>
  </body>
</html>