<section class="composer-page">
    <div class="breadcrumbs">
        <?php
            echo $html->link('Home', array('controller' => 'homes', 'action' => 'index'));
            echo " > ";
            echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
            if(!empty($artisttext)){
                echo " > ";
                if (strlen($artisttext) >= 30)
                {
                    $artisttext = substr($artisttext, 0, 30) . '...';
                }
                echo $this->getTextEncode($artisttext);
            }
        ?>
    </div>
    <br class="clr">
    <header class="clearfix">
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
    </header> 
    <h3>Albums</h3>
    <div class="composer-albums">
        <div class="album-detail-container">
            <div class="cover-image">
                <a href="/artists/view/TWlsZXkgQ3lydXM=/27953468/c29ueQ==" data-hasqtip="2" oldtitle="We Can't Stop" title="" aria-describedby="qtip-2">
                    <img width="162" height="162" alt="We Can't Stop" src="http://music.libraryideas.com/000/000/000/000/279/534/68/00000000000027953468-250x250_72dpi_RGB_100Q.jpg?token=5fc5a0389e043979fcaad
        ">
                </a>
            </div>
            <div class="album-info">
                <div class="album-title"><strong><a href="/artists/view/TWlsZXkgQ3lydXM=/27953468/c29ueQ==" data-hasqtip="1" oldtitle="We Can't Stop" title="" aria-describedby="qtip-1">We Can't Stop </a></strong></div>
                <div class="genre">Genre: <a href="javascript:void(0)">Pop</a></div>
                 <div class="label">
                     Label: RCA Records Label<br>(P) 2013 RCA Records, a division of Sony Music Entertainment                                
                 </div>                
                <button class="stream-now-btn" onclick="javascript:loadAlbumData('W3siU29uZyI6eyJQcm9kSUQiOiIyNzk1MzQ2OSIsIkFydGlzdFRleHQiOiJNaWxleSBDeXJ1cyIsIlNvbmdUaXRsZSI6IldlIENhbid0IFN0b3AiLCJBZHZpc29yeSI6IkYiLCJwcm92aWRlcl90eXBlIjoic29ueSJ9LCJDZG5QYXRoIjoiMDAwXC8wMDBcLzAwMFwvMDAwXC8yNzlcLzUzNFwvNjkiLCJTYXZlQXNOYW1lIjoiTWlsZXlDeXJ1c19XZUNhbnRTdG9wX0cwMTAwMDI5OTA5MDdiXzFfMS0yNTZLXzQ0U18yQ19jYnIxeC5tcDMiLCJGdWxsTGVuZ3RoX0R1cmF0aW9uIjoiNDowMCJ9XQ==');">Stream Now</button>                                        <button class="menu-btn"></button>
                <section class="options-menu">
                    <input type="hidden" data-provider="sony" value="album" id="27953468">
                    <ul>
                        <li>
                                                                            <span id="wishlist27953468" class="beforeClick"> <a href="#" class="add-to-wishlist no-ajaxy">Add to Wishlist</a> </span>
                                <span style="display:none;" class="afterClick"><a href="JavaScript:void(0);" class="add-to-wishlist">Please Wait...</a></span>
                                                                    </li>

                            <li><a href="#" class="add-to-playlist no-ajaxy">Add to Playlist</a></li>
                        </ul>
                        <ul class="playlist-menu">
                            <li><a href="#">Create New Playlist</a></li>                                                                 
                        </ul>

                </section>
            </div>
        </div>
    </div>    
</section>