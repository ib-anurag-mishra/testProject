<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <title> Freegal Music | The New Music Library </title>
                 <?php
                        echo $html->css('freegal_styles');                       
                        echo $javascript->link('jquery-1');
                        echo $javascript->link('jquery');
                        echo $javascript->link('freegal');
                        echo $javascript->link('curvycorners');
                        echo $javascript->link('freegal_002');
                 ?>
        </head>
        
        <body>
                <div id="container">
                        <div id="header">
                                <div id="lib_name">The Public Library</div>
        
                                <div id="header_right">
                                        <ul>
                                                <li>Weekly Downloads 15/20 <a href="#"><img src="../img/question.png" border="0" width="12" height="14"></a> | <a href="faq">FAQ</a></li>
                                                <li><img src="../img/freegal_logo.png"></li>
                                        </ul>
                                </div>
                        </div>
        
                        <div id="content">
                                <div class="navigation">			
                                        <ul class="menu" id="nav">
                                                <li class="parent item1"><a href="/freegal/index.php"><span>Home</span></a></li>
                                                <li class="parent item2"><a href="#"><span>Genre</span></a>
                                                        <ul>
                                                                <li class="parent item8"><a href="/genres?genres=all"><span>See All</span></a></li>
                                                                <li class="parent item9"><a href="/genres?genres=alternative"><span>Alternative</span></a></li>
        
                                                                <li class="parent item10"><a href="/genres?genres=country"><span>Country</span></a></li>
                                                                <li class="parent item11"><a href="/genres?genres=electronica"><span>Electronica</span></a></li>
                                                                <li class="parent item12"><a href="/genres?genres=folk"><span>Folk</span></a></li>
                                                        </ul>
                                                </li>
                                                <li class="item3"><a href="/management"><span>Featured Artist</span></a>
                                                        <ul>
        
                                                                <li class="parent item13"><a href="/artist?artist=billy_idol"><span>Billy Idol</span></a></li>
                                                                <li class="parent item14"><a href="/artist?artist=black_eyed_peas"><span>Black Eyed Peas</span></a></li>
                                                                <li class="parent item15"><a href="/artist?artist=ciara"><span>Ciara</span></a></li>
                                                                <li class="parent item16"><a href="/artist?artist=pink"><span>P!nk</span></a></li>
                                                        </ul>
                                                </li>
                                                <li class="item4"><a href="/contact"><span>Newly Added</span></a></li>
        
                                                <li id="search">
                                                        <form name="search_form" method="put" action="search" class="search_form">
                                                                <input type="text" name="txtSearch" size="24" onclick="if(this.value=='Search') {this.value='';}" value="Search">
                                                        </form>
                                                        <a href="#">Advanced Search</a>
                                                </li>
                                        </ul>
                                </div>
                        <?php echo $content_for_layout; ?>
                <div id="footer">
                        <a href="about">About Freegal Music</a> | <a href="terms">Terms &amp; Conditions</a> | <a href="faq">FAQ</a>
                </div>               
        
        </body>
</html>
