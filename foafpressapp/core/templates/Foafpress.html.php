<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
    <?php
        // add some folder uris
        $this->content->FPTPLURL = str_replace(BASEDIR, BASEURL, realpath(dirname(__FILE__)).'/../../styles');

    ?>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <!-- meta http-equiv="content-language" content="de" / -->
    <meta http-equiv="content-style-type" content="text/css" />
    <title><?php echo ($this->content->META_TITLE)?htmlspecialchars(trim($this->content->META_TITLE), ENT_COMPAT, 'UTF-8'):'No Title'; ?></title>
    <meta name="description" content="<?php echo ($this->content->META_DESCRIPTION)?htmlspecialchars(trim($this->content->META_DESCRIPTION), ENT_QUOTES, 'UTF-8'):''; ?>" />
    <!-- meta name="keywords" content="" / -->
    <!-- meta name="geo.region" content="XX-XX" / -->
    <!-- meta name="geo.placename" content="Xxxxxxxx" / -->
    <!-- meta name="geo.position" content="##.###;##.###" / -->
    <!-- meta name="ICBM" content="##.###, ##.###" / -->
    <meta name="date" content="<?php echo $this->content->META_DATE; ?>" />
    <!-- meta name="robots" content="index,follow" / -->
    <!-- meta http-equiv="cache-control" content="max-age=7" / -->
    <!-- link rel="shortcut icon" href="<?php echo BASEURL; ?>favicon.ico" / -->

    <link href="<?php echo $this->content->FPLIBURL; ?>/yaml/core/slim_base.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo $this->content->FPTPLURL; ?>/default/screen.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo $this->content->FPTPLURL; ?>/default/icons.css" rel="stylesheet" type="text/css" media="all" />

    <!--[if lte IE 7]>
        <link href="<?php echo BASEURL; ?>app/layout/patches/ie7.css" rel="stylesheet" type="text/css" media="all" />
    <![endif]-->
    <!--[if lte IE 6]>
        <link href="<?php echo BASEURL; ?>app/layout/patches/ie6.css" rel="stylesheet" type="text/css" media="all" />
    <![endif]-->

    <!-- link rel="alternate" type="application/rss+xml" title="ttt" href="#" / -->
    
    <!-- script type="text/javascript" src="<?php echo $this->content->FPLIBURL; ?>/jquery/jquery-1.4.min.js"></script -->
    <!-- script type="text/javascript" src="<?php echo BASEURL; ?>app/libs/jquery/index.js"></script -->

</head>
<body class="<?php echo $this->content->body_css_class; ?>">
    <?php

    if (!$this->cache->getOutput($this->file))
    {
        $this->output();
        $this->cache->saveOutput($this->file);
    }

    ?>
    <div id="footer">
        <div class="page_margins">
            <p><strong>powered by <a href="http://foafpress.org/">Foafpress</a></strong>,
            using <a href="http://code.google.com/p/sandbox-publisher-cms/">SPCMS</a>,
            <a href="http://arc.semsol.org/">ARC 2</a>,
            <a href="http://www.yaml.de/">YAML</a>,
            <a href="http://jquery.com/">jQuery</a>
            and <a href="http://www.komodomedia.com/download/#social-network-icon-pack">Komodo SNIP</a>.</p>
        </div>
    </div> <!-- /#footer -->
            <?php
            if (true) // TODO: debug mode
            {
                echo '<div id="FP_debug"><strong>DEBUGINFO</strong>'.PHP_EOL;
                echo('<pre>'.print_r($this->content->debug_log, true).'</pre>').PHP_EOL;
                echo '</div>'.PHP_EOL;
            }
            ?>
</body>
</html>
