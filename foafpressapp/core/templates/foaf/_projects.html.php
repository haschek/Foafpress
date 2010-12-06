<?php

    echo '<h2>Projekte</h2>'.PHP_EOL;
    echo '<div class="section" id="projects">'.PHP_EOL;
    echo '<ul>'.PHP_EOL;
    foreach ($projects as $project)
    {
        if (count($project) > 0)
        {
            echo '<li id="'.md5($project['label']).'">'.PHP_EOL;
            echo '<h3>'.$project['label'].'</h3>'.PHP_EOL;
            if (isset($project['description']) || isset($project['link']))
            {
                echo '<p>'.PHP_EOL;
                if (isset($project['description'])) echo $project['description'].'<br/>'.PHP_EOL;
                if (isset($project['link'])) echo '<a class="to-from" href="'.$project['link'].'">'.$project['label'].'</a>'.PHP_EOL;
                echo '</p>'.PHP_EOL;
            }
            echo '</li>'.PHP_EOL;

        }
        
        unset($project);
    }
    echo '</ul></div>'.PHP_EOL;
    
    unset($projects);

?>
