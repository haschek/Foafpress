<?php

if (isset($activity['stream']) && is_array($activity['stream']) && count($activity['stream']) > 0)
{
    echo '<h2>Activity</h2>'.PHP_EOL;
    echo '<div class="section" id="activity">'.PHP_EOL;
    echo '<ul class="big-icons">'.PHP_EOL;

    foreach($activity['stream'] as $item)
    {
        echo '<li class="'.$item['cssclass'].'">';
        echo '<em class="hideme">'.$activity['feeds'][$item['source']].':</em> ';
        //echo $item['output'].' ('.date('Y-m-d', $item['date']).')';
        echo $item['output'].' ('.$this->content->filterVar($item['date'], array('date_to_long_string', 'trim')).')';
        echo '</li>'.PHP_EOL;
        
        unset($item);
    }
    echo '</ul></div>'.PHP_EOL;
    
    unset($activity);
}

?>
