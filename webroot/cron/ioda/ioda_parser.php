<?php
/**
File Name : ioda_parser.php
File Description : This file is executed as a backend process (set up in crontab). This File is used for parsing the xml and inserting the node values into freegal and IODA database.
@author : m68 Interactive
**/

ini_set('SMTP', '192.168.10.10');	
set_time_limit(0);
include_once('library/config.php');
include_once('library/function.php');
include_once('library/mp3_id3v11.php');
include_once('library/getid3/getid3.php');
include_once('library/getid3/write.php');
include_once('library/SimpleImage.php');
// error_reporting(0);
date_default_timezone_set('America/New_York') ;
$rootpath = explode('/' , ROOTPATH);
$folder_name = $rootpath[count($rootpath) - 2] ;
if($folder_name == '')
{
	echo "Please configure ROOTPATH in config.php ";
	exit;
}
$log_file = $folder_name . ".txt";

ini_set('display_errors','1');
$dir_name = ROOTPATH;;
if ($handle = opendir($dir_name)) 
{
	$insert_count = 0;
	$update_count = 0;
	$delete_count = 0;
	$invalid_xml_count = 0;
	$no_of_files = 0;
	$display_message = '';
	while (false !== ($dir = readdir($handle))) 
	{
		$dir_name = ROOTPATH;
		if ($dir != "." && $dir != ".." && $dir != "test" ) 
		{
			$handle_dir = opendir($dir_name.$dir);
			$dir_name .=  $dir . '/';
			
			
			while (false !== ($file = readdir($handle_dir)))
			{
				if ($file != "." && $file != "..")
				{
					$path_info = pathinfo(strtolower($file));
					
					if(isset($path_info['extension']) && ($path_info['extension'] == "xml"))
					{
					
						$no_of_files++;
						$array =  simplexml_load_file( $dir_name . $file);
						$json = json_encode($array);
						$xml_array = json_decode($json,TRUE);
						
						echo exec('sudo chmod 777 -R ' . $dir_name);
						
						//checking for dir.complete
						if(!file_exists($dir_name.'dir.complete'))
						{
							echo "file not exist" . $dir_name.'dir.complete' . "\n";
							continue;
						}
						
						// print_r($xml_array);
						$outputFile = "output_".$xml_array['ioda_release_id']."_".date('Y_m_d_h_i_s').".txt";
						$logFileWrite=fopen(IMPORTLOGS.$outputFile,'w') or die("Can't Open the file!");
						
						if($xml_array['export_action'] == "insert")
						{
							$insert_count++;
							do_insert_to_freegal_db($xml_array);
						}
						else if($xml_array['export_action'] == "delete")
						{
							$delete_count++;
							do_delete_to_freegal_db($xml_array['track']);

							$chk_query = "SELECT * FROM releases WHERE ioda_release_id = " . $xml_array['ioda_release_id'];
							$result = mysql_query($chk_query , $ioda);
							if(mysql_num_rows($result) > 0)
							{
								$delete_query = "UPDATE releases set is_deleted = 1 , export_action = 'delet' WHERE ioda_release_id = " . $xml_array['ioda_release_id'];
								mysql_query($delete_query , $ioda);
								
								$display_message .=  "Relese data deleted for release id : " . $xml_array['ioda_release_id'] . "\n" ;
								continue;
							}
							else
							{
								//send email notifing that the record is mising in database and is found while trying to delete the album.
								
								$headers = 'From: IODA XML Import' . "\r\n" .
											'X-Mailer: PHP/' . phpversion();
								$message = "Hello
								
								The Release record is found missing while trying to delete.
								Missing record's release id is ". $xml_array['ioda_release_id'] . "
								Please contact IODA for re-supply.
								Manifest log: $log_file
								
								Thanks";
								mail(TO, 'Record Missing', $message, $headers);
								
								$display_message .=  "Can't delete record having release id : " . $xml_array['ioda_release_id'] . ". The record is missing in database.\n" ;
							}

						}
						else if($xml_array['export_action'] == "update")
						{
							$update_count++;
							do_insert_to_freegal_db($xml_array);
							$chk_query = "SELECT * FROM releases WHERE ioda_release_id = " . $xml_array['ioda_release_id'];
							$result = mysql_query($chk_query , $ioda);
							$relese_data = mysql_fetch_assoc($result);

							if(mysql_num_rows($result) > 0)
							{
								//Delete all elements related to ioda_release_id
								$promo_buylinks_query = "DELETE FROM promo_buylinks WHERE fk_ioda_release_id = " . $relese_data['ioda_release_id'];
								$territories_query = "DELETE FROM territories WHERE ioda_release_id = " . $relese_data['ioda_release_id'];
								
								//delete entry from files table before deleting from trcks table
								$files_query = "SELECT ioda_track_id FROM tracks WHERE ioda_release_id = " . $relese_data['ioda_release_id'];
								$files_result = mysql_query($files_query , $ioda);
								while($row = mysql_fetch_assoc($files_result))
								{
									$files_delete_query = "DELETE FROM files WHERE fk_ioda_track_id  = " . $row['ioda_track_id'];
									mysql_query($files_delete_query , $ioda);
								}
								
								$tracks_query = "DELETE FROM tracks WHERE ioda_release_id = " . $relese_data['ioda_release_id'];
								$labels = "DELETE FROM labels WHERE ioda_label_id = " . $relese_data['label'];
								
								mysql_query($promo_buylinks_query , $ioda);
								mysql_query($territories_query , $ioda);
								mysql_query($tracks_query , $ioda);
								mysql_query($labels , $ioda);

							
								$interval_override = split_to_array($relese_data['interval_override']);
								foreach($interval_override as $io)
								{
									$io_query = "DELETE FROM interval_overrides WHERE interval_override_id = " . $io;
									mysql_query($io_query , $ioda);
								}
								
								$release_query = "DELETE FROM releases WHERE ioda_release_id = " . $relese_data['ioda_release_id'];
								$image_query = "DELETE FROM images WHERE image_id = " . $relese_data['image_id'];
								
								mysql_query($release_query , $ioda);
								mysql_query($image_query , $ioda);
								
							}
							else
							{
								//send email notifing that the record is mising in database and is found while trying to update the album.
								$headers = 'From: IODA XML Import' . "\r\n" .
											'X-Mailer: PHP/' . phpversion();
								$message = "Hello
								
								Assets missings for Release id ". $xml_array['ioda_release_id'] . "
								Please contact IODA for re-supply.
								
								Manifest log: $log_file
								
								Thanks";
								mail(TO, 'Assets Missing', $message, $headers);
							}
						}
						
						
						$ioda_release_id = $xml_array['ioda_release_id'];
						insert_teritories($xml_array['territories'] , $ioda_release_id);
						unset($xml_array['territories']);
						$xml_array['label'] = insert_label($xml_array['label'] , $ioda_release_id);
						
						if(isset($xml_array['primary_style']))
						{
							$xml_array['primary_style'] = insert_style($xml_array['primary_style']);
						}
						else
						{
							$xml_array['primary_style'] = '';
						}
						
						if(isset($xml_array['other_style']))
						{
							$xml_array['other_style'] = insert_style($xml_array['other_style']);
						}
						else
						{
							$xml_array['other_style'] = '';
						}
						
						if(isset($xml_array['interval_override']))
						{
							$xml_array['interval_override'] = insert_interval_override($xml_array['interval_override']);
						}
						else
						{
							$xml_array['interval_override'] = '';
						}
						
						if(isset($xml_array['work']))
						{
							$xml_array['work_ids'] = insert_works($xml_array['work']);
							unset($xml_array['work']);
						}
						else
						{
							$xml_array['work_ids'] = '';
						}
						
						/*inserting cover image*/
						if(isset($xml_array['image']))
						{
							$xml_array['image_id'] = insert_image($xml_array['image']);
							unset($xml_array['image']);
						}
						else
						{
							$xml_array['image_id'] = 0;
						}
						
						if(isset($xml_array['primary_artist']))
						{
							$xml_array['primary_artist'] = insert_artist($xml_array['primary_artist'] , 1);
						}
						
						if(isset($xml_array['featured_artist']))
						{
							$xml_array['featured_artist'] = insert_artist($xml_array['featured_artist'] , 2);
						}
						if(isset($xml_array['track']))
							insert_tracks($xml_array['track'] , $xml_array['ioda_release_id']);
						unset($xml_array['track']);
						if(isset($xml_array['bonus_material']))
						{
							$xml_array['bonus_material_ids'] = insert_bonus_materials($xml_array['bonus_material']);
							unset($xml_array['bonus_material']);
						}
						if(isset($xml_array['promo_buylinks']))
						{
							insert_promo_buylinks($xml_array['promo_buylinks'] , $xml_array['ioda_release_id']);
						}
						unset($xml_array['promo_buylinks']);
						
						
						
						if(insert_into_db($xml_array , "releases" , true , 'ioda_release_id' , $xml_array['ioda_release_id']))
						{
							if($xml_array['export_action'] == 'update')
							{
							$display_message .=  "Relese data updated for release id : " . $xml_array['ioda_release_id'] . "\n" ;
							}
							else if($xml_array['export_action'] == 'insert')
							{
								$display_message .=  "Relese data inserted for release id : " . $xml_array['ioda_release_id'] . "\n" ;
							}
						}
					echo exec('sudo rm -r ' . $dir_name);
					}
				}
			}

		}
	}
	echo $display_message;
	closedir($handle);
	global $ioda , $freegal ;
	mysql_close($ioda);
	mysql_close($freegal);
}


//--------------------------------------Sending the report email----------------------------------

$message = "Hi" . "\n\n" . "Please see the import results below:" . "\n\n" ."Manifest Filename: " . " " . $log_file . "\n" . "Total Number of Xml files in manifest:" . $no_of_files . "\n";			
$message = $message ."Number of Xml files processed for Insert :" . $insert_count . "\n" . "Number of Xml files processed for Update :" . $update_count . "\n" . "Number of Xml files processed for Delete :" . $delete_count . "\n". "Number of Invalid XML files :" . $invalid_xml_count . "\n";

$message .=  "\nThanks";
$headers = 'From: IODA XML Import' . "\r\n" .
		  'X-Mailer: PHP/' . phpversion();

mail(TO, 'Report', $message, $headers);


$fh =  fopen(REPORT_LOGS . $log_file, 'w') or die("can't open file");
fwrite($fh, $message);
fclose($fh);

//function to insert promo buy link information to IODA database

function insert_promo_buylinks($promo_buylinks , $ioda_release_id)
{
	if(is_integer(key($promo_buylinks)))
	{
		$str_ids = '';
		foreach($promo_buylinks as $promo_buylink)
		{
			$promo_buylinks['fk_ioda_release_id'] = $ioda_release_id;
			$str_ids .= insert_into_db($promo_buylink , 'promo_buylinks' ) . "|";
		}
		return $str_ids;
	}
	else
	{
		$promo_buylinks['fk_ioda_release_id'] = $ioda_release_id;
		return insert_into_db($promo_buylinks , 'promo_buylinks');
	}
}

//function to insert works node to IODA database
function insert_works($works)
{
	if(is_integer(key($works)))
	{
		$str_ids = '';
		foreach($works as $work)
		{
			if(isset($work['movement']))
			{
				$work['movement_id'] = insert_movements($work['movement']);
				unset($work['movement']);
			}
			$str_ids .= insert_into_db($work , 'works' , true, 'ioda_work_id' , $work['ioda_work_id']) . "|";
		}
		return $str_ids;
	}
	else
	{
		if(isset($works['movement']))
		{
			$works['movement_id'] = insert_movements($works['movement']);
			unset($works['movement']);
		}
		return insert_into_db($works , 'works' , true, 'ioda_work_id' , $works['ioda_work_id']);
	}
}

//function to insert movements node to IODA database
function insert_movements($movements)
{
	if(is_integer(key($movements)))
	{
		$str_ids = '';
		foreach($movements as $movement)
		{
			$str_ids .= insert_into_db($movement , 'movements' , true, 'ioda_movement_id' , $movement['ioda_movement_id']) . "|";
		}
		return $str_ids;
	}
	else
	{
		return insert_into_db($movements , 'movements' , true, 'ioda_movement_id' , $movements['ioda_movement_id']);
	}
}

////function to insert bonus material node to IODA database
//$type = 0 specifies check for unique,1 means dont check for unique
function insert_bonus_materials($bonus_materials , $type = 0)
{

	if($type == 1)
	{
		insert_into_db($bonus_materials , 'bonus_materials');
		return;
	}
	else if(is_integer(key($bonus_materials)))
	{
		$str_ids = '';
		foreach($bonus_materials as $bonus_material)
		{
			if(isset($bonus_material['file']))
			{
				$bonus_material['file_id'] = insert_files($bonus_material['file'] , 0);
				unset($bonus_material['file']);
			}
			$str_ids .= insert_into_db($bonus_material , 'bonus_materials' , true, 'ioda_asset_id' , $bonus_material['ioda_asset_id']) . "|";
		}
		return $str_ids;
	}
	else
	{
		if(isset($bonus_materials['file']))
		{
			$bonus_materials['file_id'] = insert_files($bonus_materials['file'] , 0);
			unset($bonus_materials['file']);
		}
		return insert_into_db($bonus_materials , 'bonus_materials' , true, 'ioda_asset_id' , $bonus_materials['ioda_asset_id']);
	}
}

//function to insert tracks node to IODA database
function insert_tracks($tracks , $ioda_release_id)
{
	if(is_integer(key($tracks)))
	{
		$str_tracks_id = '';
		foreach($tracks as $track)
		{
			if(isset($track['primary_artist']))
			{
				$track['primary_artist'] = insert_artist($track['primary_artist'] , 1);
			}
			
			if(isset($track['featured_artist']))
			{
				$track['featured_artist'] = insert_artist($track['featured_artist'] , 2);
			}
			
			if(isset($track['musical_works']))
			{
				$track['musical_works'] = insert_musical_work($track['musical_works']['musical_work']);
			}
			
			if(isset($track['media_file']))
			{
				insert_files($track['media_file'] , $track['ioda_track_id']);
				unset($track['media_file']);
			}
			$track['ioda_release_id'] = $ioda_release_id;
			

			$str_tracks_id .= insert_into_db($track , 'tracks' , true , 'ioda_track_id' , $track['ioda_track_id'] ) . "|";
		}
		return $str_tracks_id;
	}
	else
	{
		if(isset($tracks['primary_artist']))
		{
			$tracks['primary_artist'] = insert_artist($tracks['primary_artist'] , 1);
		}
		
		if(isset($tracks['featured_artist']))
		{
			$tracks['featured_artist'] = insert_artist($tracks['featured_artist'] , 2);
		}
		
		if(isset($tracks['musical_works']))
		{
			$tracks['musical_works'] = insert_musical_work($tracks['musical_works']['musical_work']);
		}
		$tracks['ioda_release_id'] = $ioda_release_id;
		return insert_into_db($tracks , 'tracks' , true , 'ioda_track_id' , $tracks['ioda_track_id'] ) ;
	}
}


//function to insert musical_work node to IODA database
function insert_musical_work($musical_works)
{
	if(is_integer(key($musical_works)))
	{
		$str_musical_work_id = '';
		foreach($musical_works as $musical_work)
		{
			if(isset($musical_work['share']))
			{
				$musical_work['share_id'] = insert_share($musical_work['share']);
				unset($musical_work['share']);
			}
			$str_musical_work_id .= insert_into_db($musical_work , 'musical_works') . "|";
		}
		return $str_musical_work_id;
	}
	else
	{
		if(isset($musical_works['share']))
		{
			$musical_works['share_id'] = insert_share($musical_works['share']);
			unset($musical_works['share']);
		}
		return insert_into_db($musical_works , 'musical_works');
	}
}

//function to insert share node to IODA database
function insert_share($shares)
{
	if(is_integer(key($shares)))
	{
		$str_sh_id = '';
		foreach($shares as $sh)
		{
			if(isset($sh['writer']))
			{
				$sh['writer_id'] = insert_writer($sh['writer']);
				unset($sh['writer']);
			}
			if(isset($sh['publisher']))
			{
				$sh['publisher_id'] = insert_publisher($sh['publisher']);
				unset($sh['publisher']);
			}
			$str_sh_id .= insert_into_db($sh , 'shares') . "|";
		}
		return $str_sh_id;
	}
	else
	{
		if(isset($shares['writer']))
		{
			$shares['writer_id'] = insert_writer($shares['writer']);
			unset($shares['writer']);
		}
		if(isset($shares['publisher']))
		{
			$shares['publisher_id'] = insert_publisher($shares['publisher']);
			unset($shares['publisher']);
		}
		return insert_into_db($shares , 'shares');
	}
}

//function to insert writer node to IODA database
function insert_writer($writers)
{
	return insert_into_db($writers , 'writers');
}

function insert_publisher($publisher)
{
	return insert_into_db($publisher , 'publishers');
}

function insert_files($files,$ioda_track_id)
{
	if(is_integer(key($files)))
	{
		$str_ids = '';
		foreach($files as $media_file)
		{
			$media_file['fk_ioda_track_id'] = $ioda_track_id;
			$str_ids .= insert_into_db($media_file , 'files') . "|";;
		}
		return $str_ids;
	}
	else
	{
		$files['fk_ioda_track_id'] = $ioda_track_id;
		return insert_into_db($files , 'files');
	}
}




function insert_artist($primary_artists , $type)
{
	if(is_numeric(key($primary_artists)))
	{
		$pa_ids = '';
		foreach($primary_artists as $primary_artist)
		{
			$pa_ids .= artist_do_insert($primary_artist['artist'] , $type) . "|";
		}
		return $pa_ids;
	}
	else
	{
		return artist_do_insert($primary_artists['artist'] , $type);
	}
}

function artist_do_insert($primary_artists , $type)
{
	if(isset($primary_artists['role']))
	{
		$primary_artists['role'] = insert_role($primary_artists['role']);
	}
	else
	{
		$primary_artists['role'] = 0;
	}
	
	if(isset($primary_artists['primary_style']))
	{
		$primary_artists['primary_style'] = insert_style($primary_artists['primary_style']);
	}
	else
	{
		$primary_artists['primary_style'] = 0;
	}
	
	if(isset($primary_artists['other_style']))
	{
		$primary_artists['other_style'] = insert_style($primary_artists['other_style']);
	}
	else
	{
		$primary_artists['other_style'] = 0;
	}
	
	if(isset($primary_artists['image']))
	{
		$primary_artists['image'] = insert_image($primary_artists['image'] , $primary_artists['ioda_artist_id'] , 'Artist image');
	}
	else
	{
		$primary_artists['image'] = 0;
	}
	
	if(isset($primary_artists['similar_artist']))
	{
		insert_similar_artist($primary_artists['similar_artist'] , $primary_artists['ioda_artist_id']);
		unset($primary_artists['similar_artist']);
	}
	
	$primary_artists['type'] = $type;
	
	return insert_into_db($primary_artists , 'artist' , true , 'ioda_artist_id' , $primary_artists['ioda_artist_id']);
}

function insert_similar_artist($similar_artist , $ioda_artist_id)
{
	if( is_integer(key($similar_artist)))
	{
		foreach($similar_artist as $sa)
		{
			$sa['ioda_artist_id'] = $ioda_artist_id;
			insert_into_db($sa , 'similar_artists');
		}
	}
	else
	{
		$similar_artist['ioda_artist_id'] = $ioda_artist_id;
		insert_into_db($similar_artist , 'similar_artists');
	}
}

function insert_role($roles)
{
	if( is_integer(key($roles)))
	{
		$stl = '';
		foreach($roles as $role)
		{
			if(isset($role['instrument']))
			{
				$role['instrument'] = insert_instrument($role['instrument']);
			}
			else
			{
				$role['instrument'] = 0;
			}
			
			$stl .= insert_into_db( $role , 'roles' , true , 'title' , $role['title'] ) . "|";
		}
		return $stl;
	}
	else
	{
		if(isset($roles['instrument']))
		{
			$roles['instrument'] = insert_instrument($roles['instrument']);
		}
		else
		{
			$roles['instrument'] = 0;
		}

		return insert_into_db($roles , 'roles' , true , 'title' , $roles['title']);
	}
}

function insert_instrument($instrument)
{
	return insert_into_db($instrument , 'instruments' , true , 'ioda_instrument_id' , $instrument['ioda_instrument_id']);
}


function insert_label($array_label)
{


	if(empty($array_label))
		return 0;
	if(isset($array_label['primary_style']))
	{
		$array_label['primary_style'] = insert_style($array_label['primary_style']);
		
	}
	else
	{
		$array_label['primary_style'] = 0;
	}
		
	return insert_into_db($array_label , 'labels' , true , 'ioda_label_id' , $array_label['ioda_label_id']);
}

function insert_style($style)
{

	if( is_integer(key($style)))
	{
		$stl = '';
		foreach($style as $st){
			$stl .= insert_into_db($st , 'styles' , true , 'ioda_style_id' , $st['ioda_style_id']) . "|";
		}
		return $stl;
		
	}
	else
	{
		return insert_into_db($style , 'styles' , true , 'ioda_style_id' , $style['ioda_style_id']);
	}
}

function insert_image($image , $asset_id = '' , $asset_name = '')
{
	if(is_integer(key($image)))
	{
		$stl = '';
		foreach($image as $img){
		
			//inserting to bonus_material
			if($asset_id != '')
			{
				$bonus_material = array();
				$bonus_material['ioda_asset_id'] = $asset_id;
				$bonus_material['bonus_material_name'] = $asset_name;
				$file = array();
				$file['file_name'] = $img['file_name'];
				$file['md5_checksum'] = $img['md5_checksum'];
				$file['format'] = $img['format'];
				$bonus_material['file_id'] = insert_files($file , 0);
				insert_bonus_materials($bonus_material , 1);
			}
			$stl .= insert_into_db($img , 'images') . "|";
		}
		return $stl;
	}
	else
	{
		//inserting to bonus_material
		if($asset_id != '')
		{
			$bonus_material = array();
			$bonus_material['ioda_asset_id'] = $asset_id;
			$bonus_material['bonus_material_name'] = $asset_name;
			$file = array();
			$file['file_name'] = $image['file_name'];
			$file['md5_checksum'] = $image['md5_checksum'];
			$file['format'] = $image['format'];
			$bonus_material['file_id'] = insert_files($file , 0);
			insert_bonus_materials($bonus_material , 1);
		}
		return insert_into_db($image , 'images');
	}
}

//getting all teritories
function insert_teritories($teritories , $ioda_release_id)
{
	foreach($teritories as $array_teritories)
	{
		$array_teritories['ioda_release_id'] = $ioda_release_id;
		if(isset($array_teritories['interval_override']))
		{
			$array_teritories['interval_override'] = insert_interval_override($array_teritories['interval_override']);
		}
		else
		{
			$array_teritories['interval_override'] = 0;
		}
		
		insert_into_db($array_teritories , 'territories');
		
	}
}

function insert_interval_override($interval_override)
{

	if(is_integer(key($interval_override)))
	{
		$stl = '';
		foreach($interval_override as $io){
			$stl .= insert_into_db( $io , 'interval_overrides') . "|";
		}
		return $stl;
	}
	else
	{
		return insert_into_db($interval_override , 'interval_overrides');
	}

}
$conn = '';
function insert_into_db($insert_array , $table , $check_if_exists = false , $field_to_check = '' , $field_value = '' , $second = false)
{
	global $ioda , $freegal , $conn;
	if(!$second && $conn != 'ioda')
	{
		if (!mysql_ping ($ioda)) {
			mysql_close($ioda);
			$ioda = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD ,true) or die("Could not connect to Database");
			if(!mysql_select_db(DB1, $ioda))
				die("Could not connect to Database");
		}
		
		$conn = 'ioda';

	}
	else if($conn != 'freegal')
	{
		if (!mysql_ping ($freegal)) {
			mysql_close($freegal);
			$freegal = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD ,true) or die("Could not connect to Database");
			if(!mysql_select_db(DB2, $freegal))
				die("Could not connect to Database");
		}
		
		$conn = 'freegal';
	}
	
	if($check_if_exists)
	{
		if(is_array($field_to_check))
		{
			$param_count = count($field_to_check);
			$cond = ''; 
			for($i = 0; $i < $param_count; $i++)
			{
				if($i == 0)
				{
					$cond .= " " . $field_to_check[$i] . " = " . "'" . $field_value[$i] . "' ";
				}
				else
				{
					$cond .= " and " . $field_to_check[$i] . " = " . "'" . $field_value[$i] . "' ";
				}
				
			}
			$select_query = "SELECT count(*) as count  FROM $table WHERE " . $cond;
		}
		else
		{
			$select_query = "SELECT count(*) as count FROM $table WHERE $field_to_check = '$field_value'";
		}
		if($second)
		{
			$result = mysql_query($select_query , $freegal);
			if(gettype($result) == 'boolean')
			{
				exit;
			}
		}
		else
		{
			
			$result = mysql_query($select_query , $ioda);
			if(gettype($result) == 'boolean')
			{
				exit;
			}
		}
		
		
		//echo $result."++".$select_query;
		$row_count = mysql_fetch_assoc($result);
		if($row_count['count'] > 0)
		{
			if(is_array($field_to_check))
			{
				return $insert_array["$field_to_check[0]"];
			}
			else
			{
				return $insert_array["$field_to_check"];
			}
		}
		
	}
	foreach($insert_array as &$ele)
	{
		$ele = "'" . mysql_real_escape_string($ele) . "'";
	}
	$fields = implode(',',array_keys($insert_array));
	$values = implode(',',array_values($insert_array));
	$insert_query = "INSERT INTO " . $table . "($fields) VALUES( " . $values . ")";

	if($second)
	{
		mysql_query($insert_query , $freegal);
		
	}
	else
	{
		mysql_query($insert_query , $ioda);

	}
	/*
	if(mysql_error())
	{
		echo $insert_query;
		echo mysql_error();	
		echo $second?'freegal':'ioda';
	}
	*/

	if($check_if_exists)
	{
		if(is_array($field_to_check))
		{
			return str_replace("'" , "" , $insert_array["$field_to_check[0]"]);			}
		else
		{
			return str_replace("'" , "" , $insert_array["$field_to_check"]);			}

		
	}
	else
	{
		if($second)
		{
			return (int)mysql_insert_id($freegal);
		}
		else
		{
			return (int)mysql_insert_id($ioda);
			
		}

	}
}

function split_to_array($var)
{
	$arrayc = explode('|' , $var);
	unset($arrayc[count($arrayc) - 1]);
	return $arrayc;
}
	
?>
