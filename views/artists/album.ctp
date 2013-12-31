<section class="artist-page">
    <div class="breadCrumb">
        <?php
        $libId = $this->Session->read('library');
        $patId = $this->Session->read('patron');
        if (!empty($_SERVER['HTTP_REFERER']))
        {
            $reffer_url = $_SERVER['HTTP_REFERER'];
        }
        if (isset($genre))
        {
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

            $genre_crumb_name = isset($genre_text_conversion[trim($genre)]) ? $genre_text_conversion[trim($genre)] : trim($genre);

            $html->addCrumb(__('All Genre', true), '/genres/view/');
            if ($genre_crumb_name != "")
            {
                $html->addCrumb($this->getTextEncode($genre_crumb_name), '/genres/view/' . base64_encode($genre_crumb_name));
            }

            echo $html->getCrumbs(' > ', __('Home', true), '/homes');
            echo " > ";
            if (strlen($artisttext) >= 30)
            {
                $artisttext = substr($artisttext, 0, 30) . '...';
            }
            echo $this->getTextEncode($artisttext);
        }
        else
        {
            echo $html->link('Home', array('controller' => 'homes', 'action' => 'index'));
            echo " > ";
            echo "<a style='cursor: pointer;;' onClick='history.back();' >Search Results</a>";
            echo " > ";
            if (strlen($artisttext) >= 30)
            {
                $artisttext = substr($artisttext, 0, 30) . '...';
            }
            echo $this->getTextEncode($artisttext);
        }

        function ieversion()
        {
            ereg('MSIE ([0-9]\.[0-9])', $_SERVER['HTTP_USER_AGENT'], $reg);
            if (!isset($reg[1]))
            {
                return -1;
            }
            else
            {
                return floatval($reg[1]);
            }
        }

        $ieVersion = ieversion();
        ?>
    </div>
    
    <br class="clr">
    
    <header class="clearfix">
        <?php
        if (isset($artisttitle))
        {
            ?>
            <h2><?php echo $this->getTextEncode($artisttitle); ?></h2>
        <?php } ?>
        <div class="faq-link">Need help? Visit our <a href="/questions">FAQ section.</a></div>
    </header>
    
    
</section>