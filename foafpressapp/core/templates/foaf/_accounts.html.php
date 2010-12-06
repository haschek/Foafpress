<?php

if (is_array($list_of_accounts) && count($list_of_accounts) > 0)
{
    echo '<div id="profiles"><h2>Profiles</h2><ul>'.PHP_EOL;
    foreach ($list_of_accounts as $account)
    {
        echo '<li class="to-from to-'.$account['source-icon-class'].'"><a href="'.$account['homepage-url'].'">'.$account['homepage-label'].'</a></li>'.PHP_EOL;
        unset($account);
    }
    echo '</ul></div>'.PHP_EOL;
    
    unset($list_of_accounts);
}


?>
