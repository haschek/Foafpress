<?php

echo '<div id="contacts">'.PHP_EOL;
echo '<h2>Contacts</h2>'.PHP_EOL;

foreach ($contacts as $cplace => $cinfo)
{
    $adrparts_counter = 0;
    foreach($cinfo as $info_elements)
    {
        $adrparts_counter = $adrparts_counter + count($info_elements);
    }
    if ($adrparts_counter == 0) break;

    echo '<h3>'.$cplace.'</h3>'.PHP_EOL;
    echo '<dl>'.PHP_EOL;

    foreach ($cinfo as $type => $content_array)
    {
        if ($type == 'Address')
        {
            echo '<dt>'.$type.'</dt>'.PHP_EOL;
        }
        else
        {
            echo '<dt class="inline">'.$type.'</dt>'.PHP_EOL;
        }

        foreach ($content_array as $content_item)
        {
            if ($type == 'Address')
            {
                echo '<dd>'.$content_item.'</dd>'.PHP_EOL;
            }
            else // Tel, Fax, Email
            {
                echo '<dd class="inline"><a href="'.$content_item['link'].'">'.$content_item['label'].'</a></dd>'.PHP_EOL;
            }
            unset($content_item);
        }

        unset($type);
        unset($content);
    }

    unset($cplace);
    unset($cinfo);

    echo '</dl>'.PHP_EOL;
}

echo '</div> <!-- /#contacts -->'.PHP_EOL;

