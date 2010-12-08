<?php
/**
 * Sandbox Publisher - Example Bootstrap
 *
 * @category SPCMS
 * @package  Sandbox-Core
 *
 * @author   Michael Haschke, eye48.com
 * @license  http://www.opensource.org/licenses/gpl-2.0.php The GNU General Public License (GPL)
 *
 * @version  SVN: $Id: $
 *
 * @link     http://code.google.com/p/sandbox-publisher-cms Dev Website and Issue tracker
 *
 * You may configure everything here, check the examples down below and
 * uncomment/extend it.
 **/
 
ini_set('display_errors', 1);

// Is your Sandbox app/website in production use?
$production = false;

// include user configuration
if (file_exists(dirname(__FILE__).'/fp-config.php') && is_readable(dirname(__FILE__).'/fp-config.php')) {
    include_once dirname(__FILE__).'/fp-config.php';
}

// plugin folders
// absolute directory names or relative to sandbox.php path
$c['plugin']['folder'][] = './../plugins/';
$c['plugin']['folder'][] = './../../../libraries/';
$c['plugin']['folder'][] = './../../../core/';

// load plugins at start
$c['plugin']['load'][] = './Foafpress';
$c['plugin']['load'][] = './hooks/ActivityIdentica';
$c['plugin']['load'][] = './hooks/ActivityGoogle';
$c['plugin']['load'][] = './i18n/LanguageChecker';

// caching
if ($production) if (!@$c['cache']['age']) $c['cache']['age'] = 60 * 60; // cache time in seconds
if (!@$c['cache']['displacement']) $c['cache']['displacement'] = 0.5; // factor for randomized timeshift
if (!@$c['cache']['folder']) $c['cache']['folder'] = dirname(__FILE__).'/cache/';

// base dir/url may be important for use in plugins and templates
define('BASEURL',
       str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR),
                   '',
                   rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR)).DIRECTORY_SEPARATOR);
define('BASEDIR', rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR).BASEURL);

// include sandbox loader
include_once('foafpressapp/libraries/spcms/spcms/sandbox.php');

?>
