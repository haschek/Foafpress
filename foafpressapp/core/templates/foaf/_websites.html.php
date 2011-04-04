<?php

if (is_array($list_of_websites) && count($list_of_websites) > 0)
{
    echo '<div id="websites"><h2 class="hideme">Websites</h2><ul>'.PHP_EOL;
    foreach ($list_of_websites as $website)
    {
        echo '<li class="to-from to-'.$website['source-icon-class'].'"><a href="'.$website['url'].'">'.$website['label'].'</a></li>'.PHP_EOL;
        unset($website);
    }
    echo '</ul></div>'.PHP_EOL;
    
    unset($list_of_websites);
}


?>
