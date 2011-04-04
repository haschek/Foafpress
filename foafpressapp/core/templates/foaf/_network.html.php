<?php

    echo '<h2>Network</h2>'.PHP_EOL;
    echo '<div class="section" id="network">';
    echo '<ul>'.PHP_EOL;
    foreach ($persons as $person)
    {
        echo '<li><div>'.PHP_EOL;
        
        $person_link = array_merge(array_diff(array_unique(array($person['homepage_link'], $person['weblog_link'], $person['resource_link'])), array(null)));
        
        if (isset($person_link[0]) && $person_link[0])
        {
            echo '<a href="'.$person_link[0].'">'.$person['name_or_nick'].' <span class="depiction"><span>'.$person['depiction'].'</span></span></a><br/>'.PHP_EOL;
        }
        else
        {
            echo $person['name_or_nick'].' <span class="depiction">'.$person['depiction'].'</span><br/>'.PHP_EOL;
        }
        
        unset($person_link);
        
        if ($person['homepage_link'])
            echo '<a href="'.$person['homepage_link'].'" title="Homepage"><img src="'.$this->content->FPTPLURL.'/default/images/icon-homepage.png" alt="'.htmlspecialchars($person['homepage_label'], ENT_QUOTES, 'UTF-8').'"/></a>'.PHP_EOL;
        
        if ($person['weblog_link'])
            echo '<a href="'.$person['weblog_link'].'" title="Weblog"><img src="'.$this->content->FPTPLURL.'/default/images/icon-weblog.png" alt="'.htmlspecialchars($person['weblog_label'], ENT_QUOTES, 'UTF-8').'"/></a>'.PHP_EOL;
        
        if ($person['resource_link'])
            echo '<a href="'.$person['resource_link'].'" title="Resource"><img src="'.$this->content->FPTPLURL.'/default/images/icon-resource.png" alt="'.htmlspecialchars($person['weblog_label'], ENT_QUOTES, 'UTF-8').'"/></a>'.PHP_EOL;
        
        echo '&nbsp;</div></li>'.PHP_EOL;
        
        unset($person);
    }
    
    echo '</ul></div> <!-- /#network -->'.PHP_EOL;
        

?>
