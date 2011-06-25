<?php

$updates = $this->content->activity;

foreach ($updates['stream'] as $update)
{

    $update = $update['contentarray_origin'];
    // die(print_r($updates, true));

    echo "\t\t".'<item>'.PHP_EOL;

    if (isset($update['http://purl.org/rss/1.0/title']))
        echo "\t\t\t".'<title>'.$update['http://purl.org/rss/1.0/title'][0]['value'].'</title>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/date']))
        echo "\t\t\t".'<dc:date>'.$update['http://purl.org/dc/elements/1.1/date'][0]['value'].'</dc:date>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/identifier']))
        echo "\t\t\t".'<dc:identifier>'.$update['http://purl.org/dc/elements/1.1/identifier'][0]['value'].'</dc:identifier>'.PHP_EOL;

    if (isset($update['http://purl.org/rss/1.0/link']))
        echo "\t\t\t".'<link>'.$update['http://purl.org/rss/1.0/link'][0]['value'].'</link>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/creator']))
        echo "\t\t\t".'<dc:creator>'.$update['http://purl.org/dc/elements/1.1/creator'][0]['value'].'</dc:creator>'.PHP_EOL;

    if (isset($update['http://wellformedweb.org/CommentAPI/commentRss']))
        echo "\t\t\t".'<wfw:commentRss>'.$update['http://wellformedweb.org/CommentAPI/commentRss'][0]['value'].'</wfw:commentRss>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/source']))
        echo "\t\t\t".'<dc:source>'.$update['http://purl.org/dc/elements/1.1/source'][0]['value'].'</dc:source>'.PHP_EOL;

    if (isset($update['http://purl.org/rss/1.0/description']))
        echo "\t\t\t".'<description>'.$update['http://purl.org/rss/1.0/description'][0]['value'].'</description>'.PHP_EOL;

    if (isset($update['http://rdfs.org/sioc/ns#has_creator']))
        echo "\t\t\t".'<sioc:has_creator>'.$update['http://rdfs.org/sioc/ns#has_creator'][0]['value'].'</sioc:has_creator>'.PHP_EOL;

    if (isset($update['http://purl.org/rss/1.0/modules/content/encoded']))
        echo "\t\t\t".'<content:encoded><![CDATA['.PHP_EOL.$update['http://purl.org/rss/1.0/modules/content/encoded'][0]['value'].PHP_EOL']]></content:encoded>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/format']))
        echo "\t\t\t".'<dc:format>'.$update['http://purl.org/dc/elements/1.1/format'][0]['value'].'</dc:format>'.PHP_EOL;

    /*
    if (isset($update['']))
        echo "\t\t\t".'<>'.$update[''][0]['value'].'</>'.PHP_EOL;
    */

    echo "\t\t".'</item>'.PHP_EOL;

}
?>