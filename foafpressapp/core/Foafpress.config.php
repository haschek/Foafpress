<?php

// -- Namespaces ---------------------------------------------------------------

$c['ns'] = array(
	'admin'=>'http://webns.net/mvcb/',
	'air'=>'http://www.daml.org/2001/10/html/airport-ont#',
	'bio'=>'http://purl.org/vocab/bio/0.1/',
	'cert'=>'http://www.w3.org/ns/auth/cert#',
	'con'=>'http://www.w3.org/2000/10/swap/pim/contact#',
	'dc'=>'http://purl.org/dc/elements/1.1/',
	'doap'=>'http://usefulinc.com/ns/doap#',
    'foaf'=>'http://xmlns.com/foaf/0.1/',
	'geo'=>'http://www.w3.org/2003/01/geo/wgs84_pos#',
	'owl'=>'http://www.w3.org/2002/07/owl#',
	'rdf'=>'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
	'rdfs'=>'http://www.w3.org/2000/01/rdf-schema#',
	'rel'=>'http://purl.org/vocab/relationship/',
	'rsa'=>'http://www.w3.org/ns/auth/rsa#',
	'rss'=>'http://purl.org/rss/1.0/',
	'sioc'=>'http://rdfs.org/sioc/ns#',
	'vcard'=>'http://www.w3.org/2006/vcard/ns#',
	'cv'=>'http://kaste.lv/~captsolo/semweb/resume/cv.rdfs#',
	'ov'=>'http://open.vocab.org/terms/'
);

// -- Supported HTTP Methods ---------------------------------------------------

$c['supportedmethods'] = array('GET', 'POST', 'HEAD', 'PUT', 'DELETE', 'TRACE', 'OPTIONS');

// -- Supported File Types -----------------------------------------------------

$c['types'] = array(
        'application/rdf+xml' => '.rdf',
        'text/turtle' => '.tt',
        /*'text/n3' => '.n3',*/
        'text/plain' => '.nt',
        'application/xhtml+xml' => '.html'
);

$c['typefallback'] = 'application/xhtml+xml';

$c['extensiontoexport'] = true;

// -- Linked Data --------------------------------------------------------------

$c['LinkedData']['cachetime'] = 7 * 24 * 60 * 60; // 7 days
$c['LinkedData']['timeout'] = 10; // 3 sec per feed
$c['LinkedData']['maxlevel'] = 1; // maximum of how deep should we follow resource uris
$c['LinkedData']['maxrequests'] = 50; // maximum of how many uris should be requested
$c['LinkedData']['useBnodes'] = false;
$c['LinkedData']['ignoreResources'] = array(
                                            'http://example.org/resource'
                                           );
$c['LinkedData']['cacheIncrement'] = 1; // factor x for every level l: maxage = cachetime + x*l*cachetime
$c['LinkedData']['followSameas'] = true;

// -- Activity Streams ---------------------------------------------------------

$c['Activity']['cachetime'] = 6 * 60 * 60; // 6 hours
$c['Activity']['timeout'] = 2; // 3 sec per feed

// -- TODO: Future Plans -------------------------------------------------------


