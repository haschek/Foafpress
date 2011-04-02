<?php

    echo '<h2>Interests</h2>'.PHP_EOL;
    echo '<div class="section" id="interests">'.PHP_EOL;
    echo '<ul>'.PHP_EOL;
    foreach ($interests as $interest)
    {
        if (count($interest) > 0 && isset($interest['description']))
        {
            echo '<li id="'.md5($interest['label']).'">'.PHP_EOL;
            echo '<h3>'.$interest['label'].'</h3>'.PHP_EOL;
            if (isset($interest['description']) || isset($interest['link']))
            {
                echo '<p>'.PHP_EOL;
                if (isset($interest['description'])) echo $interest['description'].'<br/>'.PHP_EOL;
                if (isset($interest['link'])) echo '<a class="to-from" href="'.$interest['link'].'">'.$interest['label'].'</a>'.PHP_EOL;
                echo '</p>'.PHP_EOL;
            }
            echo '</li>'.PHP_EOL;

        }
        
        unset($interest);
    }
    echo '</ul></div>'.PHP_EOL;
    
    unset($interests);

?>
