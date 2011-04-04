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
    
    if ($adrparts_counter > 0)
    {
        echo '<h3>'.$cplace.'</h3>'.PHP_EOL;
        echo '<dl>'.PHP_EOL;

        foreach ($cinfo as $type => $content_array)
        {
            if ($type == 'Address' && count($content_array)>0)
            {
                echo '<dt>'.$type.'</dt>'.PHP_EOL;
            }
            elseif (count($content_array)>0)
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
            unset($content_array);
        }

        echo '</dl>'.PHP_EOL;
    }

    unset($cplace);
    unset($cinfo);
}

unset($contacts);

echo '</div> <!-- /#contacts -->'.PHP_EOL;

