<?php

/*
  File Name : genre.php
  File Description : helper file 
  Author : m68interactive
 */

class GenreHelper extends AppHelper {

	function genreBreadcrumb($genre) {

		$genre_text_conversion = array(
			"Children's Music" => "Children's",
			"Classic" => "Soundtracks",
			"Comedy/Humor" => "Comedy",
			"Country/Folk" => "Country",
			"Dance/House" => "Dance",
			"Easy Listening Vocal" => "Easy Listening",
			"Easy Listening Vocals" => "Easy Listening",
			"Folk/Blues" => "Folk",
			"Folk/Country" => "Folk",
			"Folk/Country/Blues" => "Folk",
			"Hip Hop Rap" => "Hip-Hop Rap",
			"Rap/Hip-Hop" => "Hip-Hop Rap",
			"Rap / Hip-Hop" => "Hip-Hop Rap",
			"Jazz/Blues" => "Jazz",
			"Kindermusik" => "Children's",
			"Miscellaneous/Other" => "Miscellaneous",
			"Other" => "Miscellaneous",
			"Age/Instumental" => "New Age",
			"Pop / Rock" => "Pop/Rock",
			"R&B/Soul" => "R&B",
			"Soundtracks" => "Soundtrack",
			"Soundtracks/Musicals" => "Soundtrack",
			"World Music (Other)" => "World Music"
		);

		$new_genre = isset($genre_text_conversion[trim($genre)]) ? $genre_text_conversion[trim($genre)] : trim($genre);
		
		return $new_genre;
	}  

}

?>