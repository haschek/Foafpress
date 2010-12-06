<?php

// extract all content vars
extract($this->content->getArray(), EXTR_SKIP);

echo '<h1>'.$FP->getImage().' '.$FP->name[0].'</h1>'.PHP_EOL;

echo '<h2>Feeds/Activity</h2><ul>';

$activity = $FP->listFeedActivity();
$activity['stream'] = array_slice($activity['stream'], 0, 50);

if ($activity['stream'])
foreach($activity['stream'] as $item)
{
    echo '<li class="activity-item from-'.str_replace('.', '_', parse_url($item['source'], PHP_URL_HOST)).' to-'.str_replace('.', '_', parse_url($item['link'], PHP_URL_HOST)).'">';
    echo '<em>'.$activity['feeds'][$item['source']].':</em> ';
    echo $item['title'].' ('.date('Y-m-d', $item['date']).')';
    echo '</li>'.PHP_EOL;
}
echo '</ul>'.PHP_EOL;

?>
