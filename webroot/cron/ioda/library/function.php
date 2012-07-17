<?php
$is_update = false;
$global_release_id = '';

function do_insert_to_freegal_db($array)
{
	global $is_update , $global_release_id;
	$insert_product['ProdID'] = $array['ioda_release_id'];
	$insert_product['provider_type'] = 'ioda';
	
	//print_r($array);
	//into product table
	if(if_exists_in_freegal_db('PRODUCT' , $insert_product))
	{
		$is_update = true;
	}
	else
	{
		insert_into_db($insert_product , 'PRODUCT' , true , array('ProdID' , 'provider_type')  , array($array['ioda_release_id'] , 'ioda') , true);
	}
	$insert_album = array();
	
	$insert_album['ProdID'] =  $array['ioda_release_id'];
	
	$insert_album['ProductID'] =  $array['ioda_release_id'];
	$global_release_id = $array['ioda_release_id'];
	delete_freegal('Genre' , array('ProdID' => $global_release_id , 'provider_type' => 'ioda'));
	$insert_album['AlbumTitle'] =  $array['release_name'];
	$insert_album['Title'] =  $array['release_name'];
	$insert_album['ArtistText'] =  return_artist_freegal($array['primary_artist'] , 1);
	$insert_album['ArtistURL'] =  return_artisturl_freegal($array['primary_artist'] , 1);

	if($insert_album['ArtistText'] == '')
	{
		$insert_album['ArtistText'] = $array['display_artist_name'];
	}

	$insert_album['Artist'] =  $insert_album['ArtistText'];
	$insert_album['FileID'] = insert_file_freegal(@$array['image'],$insert_album['ProdID'],'image');
	$insert_album['Label'] = $array['label']['label_name'];
	$insert_album['Advisory'] =  isset($array['parental_advisory']) ? strtoupper($array['parental_advisory']) : 'F' ;
	$insert_album['DownloadStatus'] = '1';
	$insert_album['TrackBundleCount'] = '0';
	
	if(isset($array['other_style']) && !empty($array['other_style']))
		$other_style_array = $array['other_style'];
	else
		$other_style_array = '';
	$global_style = get_global_style($array['primary_style'] , $other_style_array , $array['ioda_release_id']);
	
	$insert_album['UPC'] = $array['upc_ean'];
	$insert_album['PublicationStatus'] = $array['export_action'];
	$insert_album['LastUpdated'] = date('Y-m-d H:i:s');
	$insert_album['StatusNotes'] = '';
	$insert_album['PublicationDate'] = $array['publish_date'];
	
	insert_artist_freegal($array['primary_artist']);
	
	$insert_album['provider_type'] = 'ioda';
	$territories = return_teritories_freegal($array['territories'] ,$array['publish_date'] ,$array['ioda_release_id']  );
	
	if($is_update)
	{
		update_freegal_db($insert_album , 'Albums'  , array('ProdID' => $insert_album['ProdID']));
	}
	else
	{
		insert_into_db($insert_album , 'Albums' , false ,'' ,'',true);
	}
	
	
	//inserting data to songs table
	insert_tracks_freegal($array['track'] , $insert_album , $territories, $array['ioda_release_id'] , $global_style,$array['territories'],$insert_album['PublicationDate'] , $array['image']['file_name']);
	
	//reset global variables
	$global_release_id = '';
	$global_style = '';
	$is_update = false;
	
}

function get_global_style($primary , $other , $release_id)
{
	$ps = '';
	$os = '';
	if(is_numeric(key($primary)))
	{
		foreach($primary as $st)
		{
			$insert_style = array();
			$insert_style['ProdID'] = $release_id;
			$insert_style['Genre'] = $st['ioda_style_name'];
			$insert_style['GenreID'] = $st['ioda_style_id'];
			$insert_style['provider_type'] = 'ioda';
			insert_into_db($insert_style , 'Genre' , false , '' , '' , true );
			$ps .=  '"' .$st['ioda_style_name']. '"' . ",";
			
			
		}
	}
	else
	{
		$st = $primary;
		$insert_style = array();
		$insert_style['ProdID'] = $release_id;
		$insert_style['Genre'] = @$st['ioda_style_name'];
		$insert_style['GenreID'] = @$st['ioda_style_id'];
		$insert_style['provider_type'] = 'ioda';
		insert_into_db($insert_style , 'Genre' , false , '' , '' , true );
		$ps = @$st['ioda_style_name'];
	}
	
	
	if(is_array($other) && is_numeric(key($other)))
	{
		foreach($other as $st)
		{
			$insert_style = array();
			$insert_style['ProdID'] = $release_id;
			$insert_style['Genre'] = $st['ioda_style_name'];
			$insert_style['GenreID'] = $st['ioda_style_id'];
			$insert_style['provider_type'] = 'ioda';
			insert_into_db($insert_style , 'Genre' , false , '' , '' , true );
			$os .=  '"' .$st['ioda_style_name']. '"' . ",";
			
			
		}
	}
	else
	{
		$st = $other;
		$insert_style = array();
		$insert_style['ProdID'] = $release_id;
		$insert_style['Genre'] = @$st['ioda_style_name'];
		$insert_style['GenreID'] = @$st['ioda_style_id'];
		$insert_style['provider_type'] = 'ioda';
		insert_into_db($insert_style , 'Genre' , false , '' , '' , true );
		$os = @$st['ioda_style_name'];
	}
	
	return $ps . "," . $os;
}

function insert_tracks_freegal($tracks , $insert_album , $territories , $release_id , $global_style,$teritories , $relese_date , $cover_image)
{

	if(is_integer(key($tracks)))
	{
		foreach($tracks as $track)
		{
			//delete from songs , albums , Genre table if it exists
			$insert_product['ProdID'] = $track['ioda_track_id'];
			$insert_product['provider_type'] = 'ioda';
			
			
			if(if_exists_in_freegal_db('PRODUCT' , $insert_product))
			{
				delete_freegal('Songs' , array('ProdID' => $track['ioda_track_id'] , 'provider_type' => 'ioda'));
				delete_freegal('Audio' , array('TrkID' => $track['ioda_track_id'] , 'provider_type' => 'ioda'));
				delete_freegal('Genre' , array('ProdID' => $track['ioda_track_id'] , 'provider_type' => 'ioda'));
				delete_freegal('countries' , array('ProdID' => $track['ioda_track_id'] , 'provider_type' => 'ioda'));
			}
			else
			{
				//into product table
				insert_into_db($insert_product , 'PRODUCT' , true , array('ProdID' , 'provider_type')  , array($release_id , 'ioda') , true);
			}
			
			$insert_track = array();
			$insert_track['ProdID'] = $track['ioda_track_id'];
			$insert_track['ReferenceID'] = $insert_album['ProdID'];
			$insert_track['ProductID'] = $insert_album['ProductID'];
			$insert_track['Title'] = $insert_album['AlbumTitle'];
			$insert_track['SongTitle'] = $track['track_name'];
			$insert_track['ISRC'] = $track['isrc'];
			$insert_track['Composer'] = '';
			$insert_track['DownloadStatus'] = 'ioda';
			$insert_track['provider_type'] = 'ioda';
			$insert_track['CreatedOn'] = date('Y-m-d H:i:s');
			$insert_track['UpdateOn'] = date('Y-m-d H:i:s');

			if(isset($track['primary_artist']))
			{

				$insert_track['ArtistText'] = return_artist_freegal($track['primary_artist'] , 1);
				$insert_track['Artist'] = $insert_track['ArtistText'];
				$insert_track['Genre'] = insert_style_freegal($track['primary_artist'] , $track['ioda_track_id']);
				if($insert_track['Genre'] == "")
					$insert_track['Genre'] = $global_style;
				
			}

			$insert_track['Advisory'] =  isset($track['parental_advisory']) ? strtoupper($track['parental_advisory']) : 'F' ;
			$insert_track['DownloadStatus'] = 1;
			$insert_track['Territory'] = $territories;
				 	
			$insert_track['Sample_Duration'] = '0:30';
			$insert_track['FullLength_Duration'] = xml_format_to_time($track['track_length']);
			
			$audio_insert = array();
			$audio_insert['TrkID'] = $track['ioda_track_id'];
			$audio_insert['provider_type'] = 'ioda';
			
			$id3_info = array();
			$id3_info['genre'] =  $insert_track['Genre'];
			$id3_info['song_title'] =  $insert_track['SongTitle'];
			$id3_info['album_title'] =  $insert_track['Title'];
			$id3_info['track_no'] =  $track['sequence'];
			$id3_info['year'] = (isset($insert_album['PublicationDate']) && ($insert_album['PublicationDate'] != ''))?  date('Y' , strtotime($insert_album['PublicationDate'])) : '';
			$id3_info['cover_image'] = $cover_image;
			
			$id3_info['artist'] =  $track['primary_artist']['artist']['artist_name'];
			
			if(isset($track['media_file']))
			{
				foreach($track['media_file'] as $mf)
				{
					if(strlen(strstr($mf['file_name'],'128k_30s'))>0)
					{
						$insert_track['Sample_FileID'] = insert_file_freegal($mf,$insert_track['ProdID'],'audio',$id3_info);
						$audio_insert['FileID'] = $insert_track['Sample_FileID'];
						//insert data to Audio table for sample mp3 file
						$path_info = pathinfo($mf['file_name']);
						$audio_insert['CODEC'] = strtoupper($path_info['extension']);
						$audio_insert['Bitrate'] = 128;
						$audio_insert['Duration'] = '0:30';
						$audio_insert['provider_type'] = 'ioda';
						$audio_insert['AudioType'] = 'Sample MPEG Layer-3 File';
						$audio_insert['ClipOffsetStart'] = @xml_format_to_time($track['preview_start_time']);
						if(isset($track['preview_clip_length']))
						{
							$audio_insert['ClipOffsetEnd'] = xml_format_to_time($track['preview_clip_length']);
						}
						else
						{
							$audio_insert['ClipOffsetEnd'] = 0;
						}
						if($audio_insert['ClipOffsetEnd'] == 0)
						{
							$audio_insert['ClipOffsetEnd'] = '0:30';
						}
						
						$audio_insert['ClipOffsetEnd'] = time_to_xml($audio_insert['ClipOffsetStart'] , $audio_insert['ClipOffsetEnd']);

					}
					else
					{
						$insert_track['FullLength_FIleID'] = insert_file_freegal($mf , $insert_track['ProdID'],'audio',$id3_info);
						$path_info = pathinfo($mf['file_name']);
						$audio_insert['CODEC'] = strtoupper($path_info['extension']);
						$audio_insert['Bitrate'] = 256;
						$audio_insert['Duration'] = xml_format_to_time($track['track_length']);
						$audio_insert['FileID'] = $insert_track['FullLength_FIleID'];
						$audio_insert['AudioType'] = 'Full Length MPEG-1 Layer 3';
						$audio_insert['ClipOffsetStart'] = '';
						$audio_insert['ClipOffsetEnd'] = '';
						
						
					}
					
					insert_into_db($audio_insert , 'Audio' , false , '' , '' ,true );
				}
			}	

			update_freegal_db($insert_track , 'Songs' , array('ProdID' => $track['ioda_track_id']));
			
			
			//insert into countries table
			insert_teritories_freegal($teritories , $relese_date , $track['ioda_track_id']);
		}
	}
	else
	{
	
		$insert_product['ProdID'] = $tracks['ioda_track_id'];
		$insert_product['provider_type'] = 'ioda';
		//into product table
		
		if(if_exists_in_freegal_db('PRODUCT' , $insert_product))
		{
			delete_freegal('Songs' , array('ProdID' => $tracks['ioda_track_id'] , 'provider_type' => 'ioda'));
			delete_freegal('Audio' , array('TrkID' => $tracks['ioda_track_id'] , 'provider_type' => 'ioda'));
			delete_freegal('Genre' , array('ProdID' => $tracks['ioda_track_id'] , 'provider_type' => 'ioda'));
			delete_freegal('countries' , array('ProdID' => $tracks['ioda_track_id'] , 'provider_type' => 'ioda'));
		}
		else
		{
			//into product table
			insert_into_db($insert_product , 'PRODUCT' , true , array('ProdID' , 'provider_type')  , array($array['ioda_release_id'] , 'ioda') , true);
		}
			

		$insert_track = array();
		$insert_track['ProdID'] = $tracks['ioda_track_id'];
		$insert_track['ReferenceID'] = $insert_album['ProdID'];
		$insert_track['ProductID'] = $insert_album['ProductID'];
		$insert_track['Title'] = $insert_album['AlbumTitle'];
		$insert_track['SongTitle'] = $tracks['track_name'];
		$insert_track['ISRC'] = $tracks['isrc'];
		$insert_track['Composer'] = '';
		$insert_track['DownloadStatus'] = 1;
		$insert_track['CreatedOn'] = date('Y-m-d H:i:s');
		$insert_track['UpdateOn'] = date('Y-m-d H:i:s');

		if(isset($tracks['primary_artist']))
		{
			$insert_track['ArtistText'] = return_artist_freegal($tracks['primary_artist'] , 1);
			$insert_track['Artist'] = $insert_track['ArtistText'];
			$insert_track['Genre'] = insert_style_freegal($tracks['primary_artist'] , $tracks['ioda_track_id']);	
			if($insert_track['Genre'] == "")
					$insert_track['Genre'] = $global_style;

		}
		
		$insert_track['Advisory'] =  isset($tracks['parental_advisory']) ? $tracks['parental_advisory'] : '' ;
		$insert_track['DownloadStatus'] = 1;
		$insert_track['Territory'] = $territories;
				

		$insert_track['Sample_Duration'] = '0:30';
		$insert_track['FullLength_Duration'] = xml_format_to_time($tracks['track_length']);
		
		
		
		$audio_insert = array();
		$audio_insert['TrkID'] = $tracks['ioda_track_id'];
		$audio_insert['provider_type'] = 'ioda';
		
		$id3_info = array();
		$id3_info['genre'] =  $insert_track['Genre'];
		$id3_info['song_title'] =  $insert_track['SongTitle'];
		$id3_info['album_title'] =  $insert_track['Title'];
		$id3_info['track_no'] =  $tracks['sequence'];
		$id3_info['year'] =  date('Y' , strtotime($insert_album['PublicationDate'])); 
		$id3_info['artist'] =  $track['primary_artist']['artist']['artist_name'];
		$id3_info['cover_image'] = $cover_image;
			
		
		if(isset($tracks['media_file']))
		{
			foreach($tracks['media_file'] as $mf)
			{
				if(strlen(strstr($mf['file_name'],'128k_30s'))>0)
				{
					$insert_track['Sample_FileID'] = insert_file_freegal($mf,$insert_track['ProdID'] , 'audio',$id3_info);
					$audio_insert['FileID'] = $insert_track['Sample_FileID'];
					//insert data to Audio table for sample mp3 file
					$path_info = pathinfo($mf['file_name']);
					$audio_insert['CODEC'] = strtoupper($path_info['extension']);
					$audio_insert['Bitrate'] = 128;
					$audio_insert['Duration'] = '0:30';
					$audio_insert['AudioType'] = 'Full Length MPEG-1 Layer-3';
					$audio_insert['ClipOffsetStart'] = xml_format_to_time($tracks['preview_start_time']);
					$audio_insert['ClipOffsetEnd'] = xml_format_to_time($tracks['preview_clip_length']);
					if($audio_insert['ClipOffsetEnd'] == 0)
					{
						$audio_insert['ClipOffsetEnd'] = '0:30';
					}
					
					$audio_insert['ClipOffsetEnd'] = time_to_xml($audio_insert['ClipOffsetStart'] , $audio_insert['ClipOffsetEnd']);

				}
				else
				{
					$insert_track['FullLength_FIleID'] = insert_file_freegal($mf , $insert_track['ProdID'] , 'audio',$id3_info);
					$path_info = pathinfo($mf['file_name']);
					$audio_insert['CODEC'] = strtoupper($path_info['extension']);
					$audio_insert['Bitrate'] = 256;
					$audio_insert['Duration'] = xml_format_to_time($tracks['track_length']);
					$audio_insert['FileID'] = $insert_track['FullLength_FIleID'];
					$audio_insert['AudioType'] = 'Sample MPEG Layer-3 File';
					$audio_insert['ClipOffsetStart'] = '';
					$audio_insert['ClipOffsetEnd'] = '';
					
					
				}
				insert_into_db($audio_insert , 'Audio' , false , '' , '' ,true );
			}
		}
		
		update_freegal_db($insert_track , 'Songs' , array('ProdID' => $track['ioda_track_id']));
	
	}
}

//insert into countries table
function insert_teritories_freegal($teritories , $relese_date , $ioda_track_id)
{
	foreach($teritories as $array_teritories)
	{
		$country_array = array();
		$country_array['ProdID'] = $ioda_track_id;
		$country_array['Territory'] = $array_teritories['country_code'];
		$country_array['SalesDate'] = $array_teritories['publish_date'];
		$country_array['provider_type'] = 'ioda';
		insert_into_db($country_array , 'countries' , false , '' , '' , true );
		
	}
}

//getting all teritories and put them inside countries table
function return_teritories_freegal($teritories , $relese_date , $ioda_track_id)
{
	$terr = '';
	foreach($teritories as $array_teritories)
	{
		$terr .=  $array_teritories['country_code'] . ",";
		
		$country_array = array();
		$country_array['ProdID'] = $ioda_track_id;
		$country_array['Territory'] = $array_teritories['country_code'];
		$country_array['SalesDate'] = $array_teritories['publish_date'];
		$country_array['provider_type'] = 'ioda';
		insert_into_db($country_array , 'countries' , false , '' , '' , true );
		
	}
	return $terr;
	
	//insert into countries table
}


function insert_style_freegal($primary_artists , $track_id)
{
	global $global_style;
	if(is_numeric(key($primary_artists)))
	{
		$stl = '';
		foreach($primary_artists as $primary_artist)
		{
			$st = $primary_artist['artist']['primary_style'];
			$insert_style = array();
			
			if(!isset($st['ioda_style_name']) || $st['ioda_style_name'] == '')
			{
				continue;
			}
			
			$insert_style['ProdID'] = $track_id;
			$insert_style['Genre'] = $st['ioda_style_name'];
			$insert_style['GenreID'] = $st['ioda_style_id'];
			$insert_style['provider_type'] = 'ioda';
			insert_into_db($insert_style , 'Genre' , false , '' , '' , true );
			$stl .=  '"' .$st['ioda_style_name']. '"' . ",";
			
			
		}
		return $stl;
	}
	else
	{
		$st = @$primary_artists['artist']['primary_style'];
		$insert_style = array();
		
		if(isset($st['ioda_style_name']) && $st['ioda_style_name'] != '')
		{
			$insert_style['ProdID'] = $track_id;
			$insert_style['Genre'] = @$st['ioda_style_name'];
			$insert_style['GenreID'] = @$st['ioda_style_id'];
			$insert_style['provider_type'] = 'ioda';
			insert_into_db($insert_style , 'Genre' , false , '' , '' , true );
			return @$st['ioda_style_name'];
		}
	}
	
	$genre_array = explode(',' , $global_style);
	
	
	foreach($genre_array as $gen)
	{
		if($gen == '')
			continue;
		$insert_style['ProdID'] = $track_id;
		$insert_style['Genre'] = @$gen;
		$insert_style['GenreID'] = '';
		$insert_style['provider_type'] = 'ioda';
		insert_into_db($insert_style , 'Genre' , false , '' , '' , true );
	}
	return $global_style;
	
}

function insert_artist_freegal($primary_artists )
{
	global $logFileWrite,$global_release_id;

	if(key($primary_artists) == 'artist')
	{
		foreach($primary_artists as $primary_artist)
		{
			
			$insert_atrist = array();
			$insert_atrist['artist_name'] = $primary_artist['artist_name'];
			if(isset($primary_artist['image']))
			{
				$insert_atrist['artist_image'] = $primary_artist['image']['file_name'];
				$src = ROOTPATH . $global_release_id . "/" . $primary_artist['image']['file_name'];
				sendFile($src , $global_release_id , $primary_artist['image']['file_name'],$logFileWrite);

			}
			$insert_atrist['territory'] = @$primary_artist['country'];
			
			insert_into_db($insert_atrist , 'artists' , false , '' , '' , true );
			
			
		}
	}
	else
	{
		$insert_atrist = array();
		$insert_atrist['artist_name'] = $primary_artists['artist_name'];
		if(isset($primary_artist['image']))
		{
			$insert_atrist['artist_image'] = $primary_artists['image']['file_name'];
			$src = ROOTPATH . $global_release_id . "/" . $primary_artists['image']['file_name'];
			sendFile($src , $global_release_id , $primary_artists['image']['file_name'],$logFileWrite);

		}

		
		$insert_atrist['territory'] = $primary_artists['country'];
		insert_into_db($insert_atrist , 'artists' , false , '' , '' , true );
		
		
	}
	
}	

function return_artist_freegal($primary_artists , $type)
{
	if(is_numeric(key($primary_artists)))
	{
		$pa_ids = '';
		foreach($primary_artists as $primary_artist)
		{
			$pa_ids .= $primary_artist['artist']['artist_name']  . ",";
		}
		return $pa_ids;
	}
	else
	{
		//return artist_do_insert($primary_artists['artist'] , $type);
		return $primary_artists['artist']['artist_name'];
	}
}	


function return_artisturl_freegal($primary_artists , $type)
{
	if(is_numeric(key($primary_artists)))
	{
		
		$pa_ids = '';
		foreach($primary_artists as $primary_artist)
		{
			if(isset($primary_artist['artist']['url']))
				$pa_ids .= $primary_artist['artist']['url']  . ",";
			else
				$pa_ids .= ",";
		}
		return $pa_ids;
	}
	else
	{
		
		if(isset($primary_artists['artist']['url']))
			return $primary_artists['artist']['url'];
		else
			return '';
	}
}
	
function insert_file_freegal($image , $release_id  , $type = "audio" , $id3_info = array())
{
		global $logFileWrite , $global_release_id;
		$insert_file['SourceURL'] =  $image['file_name'];
		$insert_file['SaveAsName'] =  $image['file_name'];
		$insert_file['DigitalSignature'] =  $image['md5_checksum'];
		$insert_file['HostURL'] =  '';
		$insert_file['CdnPath'] =  pathToCdn($global_release_id , $image['file_name']);
		
		$src = ROOTPATH . $global_release_id . "/" . $image['file_name'];
		
		
		if($type == 'image')
		{
			if(file_exists($src))
			{	
				$path_parts = pathinfo($src);
				$image_file_name = $path_parts['filename'];
				$image_extension = $path_parts['extension'];
				$resize_85x85 = ROOTPATH . $global_release_id . "/" . $image_file_name . "_85" . ".".$image_extension;
				$resize_100x100 = ROOTPATH . $global_release_id . "/" . $image_file_name . "_100" . ".".$image_extension;
				
				$image_resize = new SimpleImage();
				$image_resize->load($src);
				$image_resize->resize(85,85);
				$image_resize->save($resize_85x85);

				$image_resize->load($src);
				$image_resize->resize(100,100);
				$image_resize->save($resize_100x100);
				
				
				$image_resize->load($src);
				$image_resize->resize(250,250);
				$image_resize->save($src);
				
				//send tw0 imges 85x85 and 100x100
				sendFile($resize_85x85 , $global_release_id , $image_file_name . "_85" . ".".$image_extension , $logFileWrite);
				sendFile($resize_100x100 , $global_release_id , $image_file_name . "_100" . ".".$image_extension , $logFileWrite);
				
				
			}
		}
		
		if('audio' == $type)
		{
			$new_file_name = stripped($id3_info['artist']) . "_". stripped($id3_info['song_title']) . "_" .  $image['file_name'];

			$image['file_name'] = $new_file_name;
			$new_src = ROOTPATH . $global_release_id . "/" . $new_file_name; 
			//exec("mv $src $new_src");
			//echo "mv $src $new_src";
			copy($src , $new_src);
			$src = $new_src;
			
			$insert_file['SourceURL'] =  $new_file_name;
			$insert_file['SaveAsName'] =  $new_file_name;
			
			//$id3_src = ROOTPATH . $global_release_id . "/id3_" . $id3_info['cover_image'];
			$img_src = ROOTPATH . $global_release_id . "/" . $id3_info['cover_image'];
			
			// if(!file_exists($id3_src))
			// {	
				// $image_id1 = new SimpleImage();
				// $image_id1->load($img_src);
				// $image_id1->resizeToWidth(200);
				// $image_id1->save($id3_src);

				
			// }

			if(strpos($image['file_name'] , '_30s') === FALSE)
			{
			
				//define('IN_ID',true);
				$tag1 = new mp3_id3v11();
				$tag1->load_file($src);
				$tag1->set_tag($id3_info['song_title'],$id3_info['artist'] ,$id3_info['album_title'], $id3_info['year'] , '' , $id3_info['track_no'] , $id3_info['genre'] );
				$tag1->write_file();
			
				$TaggingFormat = 'UTF-8';
				// Initialize getID3 engine
				$getID3 = new getID3;
				$getID3->setOption(array('encoding'=>$TaggingFormat));

				
				// Initialize getID3 tag-writing module
				$tagwriter = new getid3_writetags;
				//$tagwriter->filename = '/path/to/file.mp3';
				$tagwriter->filename = $src;

				//$tagwriter->tagformats = array('id3v1', 'id3v2.3');
				$tagwriter->tagformats = array('id3v2.3');

				// set various options (optional)
				$tagwriter->overwrite_tags = true;
				//$tagwriter->overwrite_tags = false;
				$tagwriter->tag_encoding   = $TaggingFormat;
				$tagwriter->remove_other_tags = true;

				
				//print_r($id3_info);
				$TagData = array();
				$genre_array = explode(',' , $id3_info['genre']);
				$TagData = array(
					'title'   => array( $id3_info['song_title'] ) ,
					'artist'  => array( $id3_info['artist'] ),
					'album'   => array( $id3_info['album_title']),
					'year'    => array( $id3_info['year']) ,
					'genre'   => array( $genre_array[0]),
					'track'   => array( $id3_info['track_no']),
				);

				
				$image_name = $img_src;
				//$image_name = $id3_src;

				ob_start();
				
				if ($fd = fopen($image_name , 'rb')) {
					ob_end_clean();
					$APICdata = fread($fd, filesize($image_name));
					fclose ($fd);

					list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize($image_name);
					$imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
					if (isset($imagetypes[$APIC_imageTypeID])) {

						$TagData['attached_picture'][0]['data']          = $APICdata;
						$TagData['attached_picture'][0]['picturetypeid'] = '3'; // $_POST['APICpictureType'];
						$TagData['attached_picture'][0]['description']   = "cover"; //$_FILES['userfile']['name'];
						$TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];

					} else {

						fwrite($logFileWrite, 'invalid image format (only GIF, JPEG, PNG)');
					}
				} else {
					$errormessage = ob_get_contents();
					ob_end_clean();
					fwrite($logFileWrite, 'cannot open '.$image_name);

				}
				//unlink($id3_src);
				
				$tagwriter->tag_data = $TagData;
				$tagwriter->WriteTags();
				
				// write tags
				if ($tagwriter->WriteTags()) {
				
					if (!empty($tagwriter->warnings)) {
						fwrite($logFileWrite, 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings));

					}
				} else {
					fwrite($logFileWrite, 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors));
				}
				
			}
			
		}

		if(file_exists($src))
		{
			sendFile($src , $global_release_id , $image['file_name'],$logFileWrite);
		}
		else
		{
			echo "File ". $src . "doesn't exists."; 
			
		}
		
		$file_id =  insert_into_db($insert_file , 'File',false,'','',true);

		return $file_id;
	
}


function pathToCdn($ReleaaseID)
{

$dirpath = CDNPATH . "/" . $ReleaaseID ;

	// $length = strlen($prodId);
	// $less_lenght = 10 - $length;
	// $cdn_string = $prodId;
	// for($i=0;$i<$less_lenght;$i++)
	// {
		// $cdn_string = "0" . $cdn_string;
	// }

	
	// $dirpath = "";
	// for($i=0;$i<=10;$i=$i+3)
	// {

		// $dir=substr($cdn_string,$i,3);
		// if(strlen($dir) == 3)
		// {
			// $dirpath .= $dir."/";
		// }
		// else
		// {
			// $d = substr($cdn_string,-1);
			// $dirpath .= $d;
		// }
	// }
	return $dirpath;
}


function do_delete_to_freegal_db($tracks)
{
	global $freegal;
	if(is_integer(key($tracks)))
	{
		foreach($tracks as $track)
		{
			$query = "UPDATE Songs SET DownloadStatus = 0 WHERE ProdID = $track[ioda_track_id]";
			mysql_query($query , $freegal);
		}
	}
	else
	{
		$query = "UPDATE Songs SET DownloadStatus = 0 WHERE ProdID = $tracks[ioda_track_id]";
		mysql_query($query , $freegal);
	}
}

function sendFile($src,$ReleaseID,$FileName,$logFileWrite)
{
	if(empty($FileName))
		return;
	if(!($con = ssh2_connect(SFTP_HOST,SFTP_PORT)))
	{
		echo "Not Able to Establish Connection\n";
	}
	else
	{
		if(!ssh2_auth_password($con,SFTP_USER,SFTP_PASS))
		{
			echo "fail: unable to authenticate\n";
		}
		else
		{
			$sftp = ssh2_sftp($con);
			//$dirName =  $fileId % 1000;
			createPath($sftp,$ReleaseID);

			if(!is_dir("ssh2.sftp://$sftp".CDNPATH."/".$ReleaseID))
			{
				ssh2_sftp_mkdir($sftp,CDNPATH."/".$ReleaseID);
			}

			echo "Sending file $FileName...\n";
			
			//send to local server instance
			if(!is_dir(SERVER_PATH.$ReleaseID))
			{
				echo exec('sudo mkdir ' . SERVER_PATH.$ReleaseID);
			
			}
			echo exec('sudo cp ' . $src  . ' '  . SERVER_PATH.$ReleaseID);
			
			//send to CDN
			if(!ssh2_scp_send($con, $src, CDNPATH."/".$ReleaseID."/".$FileName, 0644)){
				echo "error\n";
				fwrite($logFileWrite, "error\n");
			}
			else
			{
				fwrite($logFileWrite, "File Sucessfully sent\n");
			}

		}
	       ssh2_exec($con, 'exit');
	}
}

function createPath($sftp,$ReleaseID)
{
	if(!is_dir("ssh2.sftp://$sftp".CDNPATH."/".$ReleaseID))
	{
		ssh2_sftp_mkdir($sftp,CDNPATH."/".$ReleaseID);
	}
	
}

function update_freegal_db($insert_array , $table , $field_to_check )
{
	global $ioda , $freegal;
	$cond = ''; 
	
	
	foreach($field_to_check as $key => $val)
	{
		$cond .= "and " . $key . " = " . "'" . $val . "' ";
	}
	$cond = substr($cond,3); 
	
	//check if the record already exists or not
	$check_exisatnce_in_db = "Select * from $table where $cond";
	$check_exisatnce_in_db_res = mysql_query($check_exisatnce_in_db);
	
	if(mysql_num_rows($check_exisatnce_in_db_res) == 0 )
	{
		foreach($insert_array as &$val)
		{
			$val = "'" . mysql_real_escape_string($val) . "'";
		}
		$fields = implode(',',array_keys($insert_array));
		$values = implode(',',  array_values($insert_array));
		
		$insert_query = "INSERT INTO " . $table . "($fields) VALUES( " . $values . ")";
		mysql_query($insert_query); 
		echo mysql_error();
		return;
	}
	
	//unset the condition array
	foreach($field_to_check as $key => $val)
	{
		unset($insert_array[$key]);
	}
	
	//update the record
	foreach($insert_array as &$ele)
	{
		$ele = "'" . mysql_real_escape_string($ele) . "'";
	}
	
	$set_statement = '';
	
	foreach($insert_array as $key => $val)
	{
		$set_statement .= "$key = $val ,";
	}
	
	$set_statement = substr($set_statement,0,-1); 
	
	$update_query = "UPDATE " . $table . " SET $set_statement WHERE $cond ";
	mysql_query($update_query , $freegal);
	echo mysql_error();

}


function if_exists_in_freegal_db($table , $field_to_check)
{
	global $ioda , $freegal;
	$cond = ''; 
	
	foreach($field_to_check as $key => $val)
	{
		$cond .= "and " . $key . " = " . "'" . $val . "' ";
	}
	$cond = substr($cond,3); 

	$selest_query = "SELECT * FROM  " . $table  . " WHERE $cond ";
	
	$result = mysql_query($selest_query , $freegal);
	echo mysql_error();
	if(mysql_num_rows($result) > 0)
		return true;
	else
		return false;
	
}




function delete_freegal($table , $field_to_check)
{
	global $ioda , $freegal;
	$cond = ''; 
	
	foreach($field_to_check as $key => $val)
	{
		$cond .= "and " . $key . " = " . "'" . $val . "' ";
	}
	$cond = substr($cond,3); 

	$selest_query = "DELETE FROM  " . $table  . " WHERE $cond ";
	if(mysql_query($selest_query , $freegal))
	{
		echo mysql_error();
		return true;
	}
	else
	{
		echo mysql_error();
		return false;
	}

}

function stripped($var){
	$var_array = explode(' ' ,  $var);
	$var = '';
	foreach($var_array as $ele)
	{
		$var .= ucfirst(ereg_replace("[^+A-Za-z0-9]", "", $ele));
	}



	return $var;     
}

function xml_format_to_time($xml_time)
{ 
	$xml_time = strtoupper($xml_time);
	$pt_pos = strpos($xml_time , 'PT');
	$m_pos = strpos($xml_time , 'M');
	$s_pos = strpos($xml_time , 'S');
	$minit = substr($xml_time , $pt_pos + 2 , $m_pos - $pt_pos -2 );
	$second = substr($xml_time , $m_pos + 1 , $s_pos - $m_pos - 1 );
	$second  = strlen($second) == 1 ? "0$second" : $second;
	return "$minit:$second";
}

function xml_to_time($time)
{ 
	return (float)str_replace(":", '.' , $time);
}

function time_to_xml($time1 ,$time2)
{
	$time1_arr = explode(":" , $time1 );
	$time2_arr = explode(":" , $time2 );
	$second_val = (($time1_arr[0] + $time2_arr[0] ) * 60)  + $time1_arr[1] + $time2_arr[1];
	$minute = (int)($second_val /60);
	$second = (int)($second_val %60);
	$second  = strlen($second) == 1 ? "0$second" : $second;
	return "$minute:$second";
}

?>
