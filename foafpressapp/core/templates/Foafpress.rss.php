<?php

// header('Content-Type: application/rss+xml; charset=UTF-8', true);
$this->cache->recordOutput();
echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;

?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sioc="http://rdfs.org/sioc/ns#"
>

    <channel>
        <title><?php echo ($this->content->META_TITLE)?$this->content->META_TITLE('xml_escape', 'trim'):'No Title'; ?></title>
        <link><?php echo $this->content->filterVar(substr($this->pm->Foafpress->URI_Document, 0, -1*strlen($this->pm->Foafpress->config['types'][$this->pm->Foafpress->extensiontype])), array('xml_escape', 'trim')); ?></link>
        <atom:link href="<?php echo $this->content->filterVar($this->pm->Foafpress->URI_Document, array('xml_escape', 'trim')); ?>" rel="self" type="<?php echo $this->content->filterVar($this->pm->Foafpress->extensiontype, array('xml_escape', 'trim')); ?>" />
        <description><?php echo ($this->content->META_DESCRIPTION)?$this->content->META_DESCRIPTION('xml_escape', 'trim'):''; ?></description>
        <!-- pubDate></pubDate -->
        <generator>http://foafpress.org/</generator>
        <!-- language></language -->

        <?php $this->output(); ?>

    </channel>
</rss>

<?php
$this->cache->saveOutput($this->pm->Foafpress->filename, 'FoafpressOutput_'.str_replace(',', '_', $this->pm->Foafpress->languageStackPreferences));
?>