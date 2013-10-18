<?php
/*
  File Name : index.ctp
  File Description : View page for genre index
  Author : m68interactive
 */
?>
<?php

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
<div id="genreViewAll">
    <div id="genreViewAllBox">
        <?php __('All Genres') ?>
    </div>
    <br class="clr" />
    <div height="400px" style="color:blue;">
        <?php
        $totalRows = count($genresAll);
//		$counters = array($i, ($i+($totalRows*1)), ($i+($totalRows*2)), ($i+($totalRows*3)));
        foreach ($genresAll as $genres):
            echo $html->link(ucwords($genres['Genre']['Genre']), array('controller' => 'genres', 'action' => 'view', base64_encode($genres['Genre']['Genre']))) . "</br>";
        endforeach;
//	}
        ?>
    </div>
</div>