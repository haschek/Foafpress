<?php

header('Content-Type: application/rss+xml; charset=UTF-8', true);
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
        <title><?php echo ($this->content->META_TITLE)?htmlspecialchars(trim($this->content->META_TITLE), ENT_COMPAT, 'UTF-8'):'No Title'; ?></title>
        <!-- link></link -->
        <description><?php echo ($this->content->META_DESCRIPTION)?htmlspecialchars(trim($this->content->META_DESCRIPTION), ENT_QUOTES, 'UTF-8'):''; ?></description>
        <!-- pubDate></pubDate -->
        <generator>http://foafpress.org/</generator>
        <!-- language></language -->

        <?php $this->output(); ?>

    </channel>
</rss>

<?php
$this->cache->saveOutput($this->file.serialize($this->pm->Foafpress->languageStackPreferences).$this->pm->Foafpress->extensiontype);
?>