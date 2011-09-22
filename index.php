<?php
/**
 * Foafpress Bootstrap
 *
 * @category FOAFPRESS
 * @package  Foafpress-Core
 *
 * @author   Michael Haschke, http://eye48.com/
 * @license  http://www.opensource.org/licenses/gpl-2.0.php The GNU General Public License (GPL)
 *
 * @link     http://foafpress.org/
 *
 * Usually it is not necessary to edit something here. You would lose your
 * changes with the next Foafpress update. Please use fp-config.php for
 * configuration, you can use fp-config.php-example as template.
 *
 **/

// show errors
ini_set('display_errors', 1);

// Is your Sandbox app/website in production use?
$production = false;

// do not use predefined DOCUMENT_ROOT b/c problems on Apache servers what are
// configured with virtual mass hosting.
// Does it harm other server environments?
$_SERVER['DOCUMENT_ROOT'] = str_ireplace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']);

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
$c['plugin']['load'][] = './debug/DebugLog';
$c['plugin']['load'][] = './i18n/LanguageChecker';
$c['plugin']['load'][] = './renderer/FilterBasicLiquid';
$c['plugin']['load'][] = './Foafpress';
$c['plugin']['load'][] = './hooks/ActivityFlickr';
$c['plugin']['load'][] = './hooks/ActivityIdentica';
$c['plugin']['load'][] = './hooks/ActivityTwitter';
$c['plugin']['load'][] = './hooks/ActivityGoogle';

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
