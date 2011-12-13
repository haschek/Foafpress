<?php

$updates = $this->content->activity;
// die(print_r($updates, true));

foreach ($updates['stream'] as $update)
{

    $update = $update['contentarray_origin'];

    echo "\t\t".'<item>'.PHP_EOL;

    if (isset($update['http://purl.org/rss/1.0/title']))
        echo "\t\t\t".'<title>'.$this->content->filterVar($update['http://purl.org/rss/1.0/title'][0]['value'], array('xml_escape', 'trim')).'</title>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/date']))
    {
        //echo "\t\t\t".'<dc:date>'.$this->content->filterVar($update['http://purl.org/dc/elements/1.1/date'][0]['value'], array('date_to_w3c', 'trim')).'</dc:date>'.PHP_EOL;
        echo "\t\t\t".'<pubDate>'.$this->content->filterVar($update['http://purl.org/dc/elements/1.1/date'][0]['value'], array('date_to_rss', 'trim')).'</pubDate>'.PHP_EOL;
    }

    if (isset($update['http://purl.org/dc/elements/1.1/identifier']))
        echo "\t\t\t".'<dc:identifier>'.$this->content->filterVar($update['http://purl.org/dc/elements/1.1/identifier'][0]['value'], array('xml_escape', 'trim')).'</dc:identifier>'.PHP_EOL;

    if (isset($update['http://purl.org/rss/1.0/link']))
        echo "\t\t\t".'<link>'.$this->content->filterVar($update['http://purl.org/rss/1.0/link'][0]['value'], array('xml_escape', 'trim')).'</link>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/creator']))
        echo "\t\t\t".'<dc:creator>'.$this->content->filterVar($update['http://purl.org/dc/elements/1.1/creator'][0]['value'], array('xml_escape', 'trim')).'</dc:creator>'.PHP_EOL;

    if (isset($update['http://wellformedweb.org/CommentAPI/commentRss']))
        echo "\t\t\t".'<wfw:commentRss>'.$this->content->filterVar($update['http://wellformedweb.org/CommentAPI/commentRss'][0]['value'], array('xml_escape', 'trim')).'</wfw:commentRss>'.PHP_EOL;

    if (isset($update['http://purl.org/dc/elements/1.1/source']))
        echo "\t\t\t".'<dc:source>'.$this->content->filterVar($update['http://purl.org/dc/elements/1.1/source'][0]['value'], array('xml_escape', 'trim')).'</dc:source>'.PHP_EOL;

    if (isset($update['http://rdfs.org/sioc/ns#has_creator']))
        echo "\t\t\t".'<sioc:has_creator>'.$this->content->filterVar($update['http://rdfs.org/sioc/ns#has_creator'][0]['value'], array('xml_escape', 'trim')).'</sioc:has_creator>'.PHP_EOL;

    if (isset($update['http://purl.org/rss/1.0/description']))
    {
        echo "\t\t\t".'<description><![CDATA['.PHP_EOL.$this->content->filterVar($update['http://purl.org/rss/1.0/description'][0]['value'], array('strip_html', 'xml_escape', 'trim')).']]></description>'.PHP_EOL;
    }
    elseif (isset($update['http://purl.org/rss/1.0/modules/content/encoded']))
    {
        echo "\t\t\t".'<description><![CDATA['.PHP_EOL.$this->content->filterVar($update['http://purl.org/rss/1.0/modules/content/encoded'][0]['value'], array('strip_html', 'xml_escape', 'trim')).']]></description>'.PHP_EOL;
    }

    if (isset($update['http://purl.org/rss/1.0/modules/content/encoded']))
    {
        echo "\t\t\t".'<content:encoded><![CDATA['.PHP_EOL.$this->content->filterVar($update['http://purl.org/rss/1.0/modules/content/encoded'][0]['value'], array('trim')).PHP_EOL.']]></content:encoded>'.PHP_EOL;
    }
    elseif (isset($update['http://purl.org/rss/1.0/description']))
    {
        echo "\t\t\t".'<content:encoded><![CDATA['.PHP_EOL.$this->content->filterVar($update['http://purl.org/rss/1.0/description'][0]['value'], array('trim')).PHP_EOL.']]></content:encoded>'.PHP_EOL;
    }

    if (isset($update['http://purl.org/dc/elements/1.1/format']))
        echo "\t\t\t".'<dc:format>'.$this->content->filterVar($update['http://purl.org/dc/elements/1.1/format'][0]['value'], array('xml_escape', 'trim')).'</dc:format>'.PHP_EOL;

    /*
    if (isset($update['']))
        echo "\t\t\t".'<>'.$update[''][0]['value'].'</>'.PHP_EOL;
    */

    if (isset($update['http://purl.org/rss/1.0/guid']))
    {
        echo "\t\t\t".'<guid isPermaLink="false">'.$this->content->filterVar($update['http://purl.org/rss/1.0/guid'][0]['value'], array('xml_escape', 'trim')).'</guid>'.PHP_EOL;
    }
    else
    {
        echo "\t\t\t".'<guid isPermaLink="false">'.$this->content->filterVar(md5(serialize($update)), array('xml_escape', 'trim')).'</guid>'.PHP_EOL;
    }

    echo "\t\t".'</item>'.PHP_EOL;

}
?>