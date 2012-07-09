<?php
/*
	 File Name : advance_search.ctp
	 File Description : View page for advance search
	 Author : m68interactive
 */
?>
<link type="text/css" rel="stylesheet" href="/css/advanced_search.css">
<div class="breadCrumb">
<?php
	$html->addCrumb(__('Advance Search', true), '/search/advanced_search');
	echo $html->getCrumbs('&nbsp;>&nbsp;', __('Home', true), '/homes');
?>
</div>

<!-- Search Form -->
<div id="leftCol">
	<div id="leftColWrapper">
		<form method="get"><h1 ><label for="search_query">Search music on freegal.com</label></h1>
			<input type="text"  id="search_query" value="bob" class="query" name="q">
			<input type="hidden" value="all" name="type">
			<input type="submit" value="search">
			<ul  class="clearit" id="searchfilter">
				<li  class=" current  first "><a  href="/search?q=boby">All Music</a></li>
				<li ><a  href="#">Albums</a></li>
				<li ><a  href="#">Artists</a></li>                                
				<li ><a  href="#">Composers</a></li>        
				<li ><a href="#">Genres</a></li> 
				<li ><a href="#">Label</a></li>
				<li ><a href="#">Songs</a></li>				           
			</ul>            
		</form>      
	 </div>	
</div>


<div  class="fullWidth" id="resultsSummary">
	<div class="search_result_text"> 
		<h3>Results for your search "bob" </h3>
	</div> 
	<div  id="hide_blocks">
		<a>Hide</a>
	</div>
</div>
<!-- Search Form End-->

<!-- leftColblock Start -->
<div  id="leftColblock">
        <div  id="leftColblockWrapper">             
             <div  class="results" id="albumblock">
				<h2  class="heading">
					<span class="h2Wrapper">Albums</span>
				</h2> 																								
					<div  id ="albumblockR1">
						<div  class ="albumblockC1">
							<a  href="#"><img   class="art" src="/img/discover-beyond.jpg"> </a>
							<div class="albumblockArtistexts">
								<a class="albumblockArtisLink">Discover Beyond</a>
								<br />
								<a  href="#">Genre: Rock</a>
								<a href="#" class="playbutton "><img   src="http://cdn.last.fm/flatness/preview/play_indicator.png" alt="Play" class="transparent_png play_icon"></a>
								<br />
								<span  class="stats">Label: Columbia/Legacy(2007)</span>
							</div>
						</div>
						
						<div  class ="albumblockC2">
							<a  href="#"><img   class="art" src="/img/discover-beyond.jpg"> </a>
							<div class="albumblockArtistexts">
								<a class="albumblockArtisLink">Discover Beyond</a>
								<br />
								<a  href="#">Genre: Rock</a>
								<a href="#" class="playbutton "><img   src="http://cdn.last.fm/flatness/preview/play_indicator.png" alt="Play" class="transparent_png play_icon"></a>
								<br />
								<span  class="stats">Label: Columbia/Legacy(2007)</span>
							</div>
						</div>													
					</div>	

					<div  id ="albumblockR2">		
						<div  class ="albumblockC1">
							<a  href="#"><img   class="art" src="/img/discover-beyond.jpg"> </a>
							<div class="albumblockArtistexts">
								<a class="albumblockArtisLink">Discover Beyond</a>
								<br />
								<a  href="#">Genre: Rock</a>
								<a href="#" class="playbutton "><img   src="http://cdn.last.fm/flatness/preview/play_indicator.png" alt="Play" class="transparent_png play_icon"></a>
								<br />
								<span  class="stats">Label: Columbia/Legacy(2007)</span>
							</div>
						</div>
						
						<div  class ="albumblockC2">
							<a  href="#"><img   class="art" src="/img/discover-beyond.jpg"> </a>
							<div class="albumblockArtistexts">
								<a class="albumblockArtisLink">Discover Beyond</a>
								<br />
								<a  href="#">Genre: Rock</a>
								<a href="#" class="playbutton "><img   src="http://cdn.last.fm/flatness/preview/play_indicator.png" alt="Play" class="transparent_png play_icon"></a>
								<br />
								<span  class="stats">Label: Columbia/Legacy(2007)</span>
							</div>
						</div>	
					</div>			
									 
					<span class="more_link">
						<a  href="#">See more albums</a>
					</span>
			</div>		
                  
            <div  id="ComposersWrapper">
					<h2>Composers</h2>                    
					<ul >
						<li ><span class="left_text"><a>Bob Dylan</a></span><span class="right_text">(48)</span></li> 
						<li ><span class="left_text"><a>Bob Dylan</a></span><span class="right_text">(48)</span></li> 
						<li ><span class="left_text"><a>Bob Dylan</a></span><span class="right_text">(48)</span></li> 
						<li ><span class="left_text"><a>Bob Dylan</a></span><span class="right_text">(48)</span></li> 
					</ul>							
					<span class="more_link"><a  href="#">See more Composers</a></span>
            </div>
			
			<div id="GenreWrapper">
					<h2>Genres</h2>                    
					<ul >
						<li ><span class="left_text"><a>Chinese Pop/Rock</a></span><span class="right_text">(48)</span></li> 
						<li ><span class="left_text"><a>Chinese Pop/Rock</a></span><span class="right_text">(48)</span></li> 
						<li ><span class="left_text"><a>Chinese Pop/Rock</a></span><span class="right_text">(48)</span></li> 
						<li ><span class="left_text"><a>Chinese Pop/Rock</a></span><span class="right_text">(48)</span></li> 
					</ul>								
					<span class="more_link"><a  href="#">See more Genres</a></span>
            </div>				  
        </div>
    </div>
<!-- leftColblock End -->
	
<!-- Right blocks -->
	
	<div  id="rightCol">
        <div   id="ArtistWrapper">              
  				<h2>Artists</h2>                    
				<ul >				
					<li ><span class="left_text"><a>Bob Wills & His Texas Playboys... </a></span><span class="right_text">(48)</span></li> 
					<li ><span class="left_text"><a>Bob Wills & His Texas Playboys... </a></span><span class="right_text">(48)</span></li> 
					<li ><span class="left_text"><a>Bob Wills & His Texas Playboys... </a></span><span class="right_text">(48)</span></li> 
					<li ><span class="left_text"><a>Bob Wills & His Texas Playboys... </a></span><span class="right_text">(48)</span></li> 
					<li ><span class="left_text"><a>Bob Wills & His Texas Playboys... </a></span><span class="right_text">(48)</span></li> 
				</ul>								
				<span class="more_link"><a  href="#">See more Artists</a></span>		
		</div>
		
		 <div  id="LabelWrapper">              
			<h2>Labels</h2>                    
			<ul >
				<li ><span class="left_text"><a>Legacy/Columbia</a></span><span class="right_text">(48)</span></li> 
				<li ><span class="left_text"><a>Legacy/Columbia</a></span><span class="right_text">(48)</span></li>
				<li ><span class="left_text"><a>Legacy/Columbia</a></span><span class="right_text">(48)</span></li>
				<li ><span class="left_text"><a>Legacy/Columbia</a></span><span class="right_text">(48)</span></li>
			</ul>								
			<span class="more_link"><a  href="#">See more Labels</a></span>		
		</div>                
    </div>
	
	
<!-- Added for track Songs -->
	
<div >
	<div  class="links" id="genreArtist">Artist<a href="#"></a></div>
	<div  class="links" id="genreAlbum">Album<a href="#"></a></div>
	<div  class="links"  id="genreTrack">Track<a href="#"></a></div>
	<div  id="genreDownload">Download</div>
<br class="clr">
<div id="genreResults">
	<table cellspacing="0" cellpadding="0" style="margin-left: 53px;">
	      <tbody>
			<tr >      
				<td width="210" valign="top" style="padding-left: 5px;">
					<p>
						<span title=""><a href="#">Bob Corritore, Koko Ta..</a></span>											
					</p>
				</td>
				<td width="210" valign="top" style="padding-left: 10px;">
					<p><a href="#">Harmonica Blues	</a></p>
				</td>
				<td valign="top" style="width: 274px; padding-left: 10px;">
					<p>
						What Kind of Man Is This?

					</p>
				</td>
				<td width="196" valign="top" align="center" style="padding-left: 10px;">
					<span id="song_3748486" class="beforeClick">
						<a href="#">Download Now</a>
					</span>
				</td>
			</tr>			
			<tr >      
				<td width="210" valign="top" style="padding-left: 5px;">
					<p>
						<span title=""><a href="#">Bob Corritore, Koko Ta..</a></span>											
					</p>
				</td>
				<td width="210" valign="top" style="padding-left: 10px;">
					<p><a href="#">Harmonica Blues	</a></p>
				</td>
				<td valign="top" style="width: 274px; padding-left: 10px;">
					<p>
						What Kind of Man Is This?

					</p>
				</td>
				<td width="196" valign="top" align="center" style="padding-left: 10px;">
					<span id="song_3748486" class="beforeClick">
						<a href="#">Download Now</a>
					</span>
				</td>
			</tr>

	</tbody></table>
	
<!-- End Added for track Songs -->
</div>
<div class="paging">
    	<span class="disabled">&lt;&lt; previous</span>&nbsp;<span class="current">1</span> | 
		<span><a href="#">2</a></span> | 
		<span><a href="#">3</a></span> | 		
		<span><a href="#">4</a></span> | &nbsp;
		<span><a class="next" href="#">next >></a></span></div>
</div>	
	
	



