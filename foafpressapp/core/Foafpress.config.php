<?php

// -- Namespaces ---------------------------------------------------------------

$c['ns']      = array(
    'admin'   => 'http://webns.net/mvcb/',
    'atom'    =>  'http://www.w3.org/2005/Atom',
    'air'     => 'http://www.daml.org/2001/10/html/airport-ont#',
    'bio'     => 'http://purl.org/vocab/bio/0.1/',
    'cert'    => 'http://www.w3.org/ns/auth/cert#',
    'con'     => 'http://www.w3.org/2000/10/swap/pim/contact#',
    'dc'      => 'http://purl.org/dc/elements/1.1/',
    'doap'    => 'http://usefulinc.com/ns/doap#',
    'foaf'    => 'http://xmlns.com/foaf/0.1/',
    'geo'     => 'http://www.w3.org/2003/01/geo/wgs84_pos#',
    'owl'     => 'http://www.w3.org/2002/07/owl#',
    'rdf'     => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
    'rdfs'    => 'http://www.w3.org/2000/01/rdf-schema#',
    'rel'     => 'http://purl.org/vocab/relationship/',
    'rsa'     => 'http://www.w3.org/ns/auth/rsa#',
    'rss'     => 'http://purl.org/rss/1.0/',
    'sioc'    => 'http://rdfs.org/sioc/ns#',
    'vcard'   => 'http://www.w3.org/2006/vcard/ns#',
    'cv'      => 'http://kaste.lv/~captsolo/semweb/resume/cv.rdfs#',
    'ov'      => 'http://open.vocab.org/terms/',
    'content' => 'http://purl.org/rss/1.0/modules/content/',
    'dbpedia' => 'http://dbpedia.org/resource/'
);

// -- Supported HTTP Methods ---------------------------------------------------

$c['supportedmethods'] = array('GET'); // array('GET', 'POST', 'HEAD', 'PUT', 'DELETE', 'TRACE', 'OPTIONS');

// -- Supported File Types -----------------------------------------------------

$c['types'] = array(
    'text/html' => '.html',
    // 'application/xhtml+xml' => '.html',
    'application/rss+xml' => '.rss',
    'application/rdf+xml' => '.rdf',
    'text/turtle' => '.tt',
    //'text/n3' => '.n3',
    'text/plain' => '.nt',
);

$c['typefallback'] = 'text/html'; // application/xhtml+xml

$c['extensiontoexport'] = true;

// -- HTTP Headers in answer ---------------------------------------------------

$c['http_headers'] = array(
    'text/html' => array(
        'Content-Type' => 'text/html; charset=UTF-8'
    ),
    'application/xhtml+xml' => array(
        'Content-Type' => 'application/xhtml+xml; charset=UTF-8'
    ),
    'application/rss+xml' => array(
        'Content-Type' => 'application/rss+xml; charset=UTF-8',
        'X-Robots-Tag' => 'noindex'
    ),
    'application/rdf+xml' => array(
        'Content-Type' => 'application/rdf+xml; charset=UTF-8',
        'X-Robots-Tag' => 'noindex'
    ),
    'text/turtle' => array(
        'Content-Type' => 'text/turtle; charset=UTF-8',
        'X-Robots-Tag' => 'noindex'
    ),
    'text/plain' => array(
        'Content-Type' => 'text/plain; charset=UTF-8',
        'X-Robots-Tag' => 'noindex'
    ),
);

// -- Store --------------------------------------------------------------------

// TODO
$c['store'] = 'Arc2File';
$c['Arc2File']['publicdata'] = '.';

// -- DirectoryIndex -----------------------------------------------------------

/* Apache's DirextoryIndex only works with existing files, so we have to fake it */
$c['DirectoryIndex'] = 'index'; // extension will be added from supported file types

// -- Linked Data --------------------------------------------------------------

$c['LinkedData']['cachetime'] = 7 * 24 * 60 * 60; // 7 days
$c['LinkedData']['timeout'] = 10; // 3 sec per feed
$c['LinkedData']['maxlevel'] = 1; // maximum of how deep should we follow resource uris
$c['LinkedData']['maxrequests'] = 50; // maximum of how many uris should be requested
$c['LinkedData']['useBnodes'] = false; // not used right now (TODO)
$c['LinkedData']['ignoreResources'] = array(
                                            'http://example.org/resource'
                                           );
$c['LinkedData']['cacheIncrement'] = 1; // factor x for every level l: maxage = cachetime + x*l*cachetime
$c['LinkedData']['followSameas'] = true;

// -- Activity Streams ---------------------------------------------------------

$c['Activity']['cachetime'] = 6 * 60 * 60; // 6 hours
$c['Activity']['timeout'] = 2; // 3 sec per feed

// -- Support Ribbon -----------------------------------------------------------

$c['Ribbon'] = true;

// -- Quickfix: Enable Postprocessing ------------------------------------------

/* as long as the problem caching/postprocessing/contentnegotiation is not fixed
   postprocessing is turned off default */

$c['enableOutputPostprocessing'] = true;

// -- TODO: Future Plans -------------------------------------------------------


