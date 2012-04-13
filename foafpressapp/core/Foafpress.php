<?php

class Foafpress extends SandboxPlugin
{
    public $URI_Request = null;
    public $URI_Document = null;

    public $extensiontype = null;

    public $config = array();

    public $base = null;

    public $arc2_resource = null;

    public $foaf_primaryTopic = null;

    public $arc2_exportfunctions = array(
                                            'application/rdf+xml' => 'toRDFXML',
                                            'text/turtle' => 'toTurtle',
                                            'text/plain' => 'toNtriples'
                                        );

    public $languageStackPreferences = null;

    protected function init()
    {
        $this->addLogMessage('Init Foafpress plugin');

        $this->LoadConfiguration();

        // add foafpress templates to template configuration
        $this->sandbox->templateAddFolder($this->path.'templates/');

        // add foafpress controllers to plugin configuration
        $this->sandbox->pm->addFolder($this->path.'controllers/');

        $this->SubscribeEventHandlers();

        $this->LoadLibrariesAndIncludes();

        // write libraries folder to view
        $this->content->FPLIBURL = str_replace(BASEDIR, BASEURL, dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libraries');

        return;
    }

    protected function LoadConfiguration()
    {
        $this->addLogMessage('Load Foafpress configuration');

        // check user configuration of namespaces
        if (!isset($this->config['ns']))
        {
            $this->config['ns'] = array();
        }

        // load standard configuration and add missing vars to user config
        if (is_readable($this->path.'Foafpress.config.php')) include_once($this->path.'Foafpress.config.php');
        if (isset($c))
        {
            foreach($c as $c_key => $c_value)
            {
                if (isset($this->config[$c_key]) && is_array($this->config[$c_key]))
                {
                    $this->config[$c_key] = array_merge($c[$c_key], $this->config[$c_key]);
                }
                elseif (!isset($this->config[$c_key]))
                {
                    $this->config[$c_key] = $c_value;
                }
            }
        }

        return;
    }

    // Foafpress event handlers for SPCMS
    protected function SubscribeEventHandlers()
    {
        $this->addLogMessage('Subscribe Foafpress event handlers');

        $this->pm->subscribe('sandbox_parse_failed', $this, 'FindResource');
        if (isset($this->config['enableOutputPostprocessing']) && $this->config['enableOutputPostprocessing'] === true)
        {
            $this->pm->subscribe('sandbox_parse_start', $this, 'CheckCache');
        }
        $this->pm->subscribe('sandbox_parse_end', $this, 'LoadResourceFromFile');
        $this->pm->subscribe('sandbox_flush_start', $this, 'sendHttpHeaders'); // called manually here

        return;
    }

    protected function LoadLibrariesAndIncludes()
    {
        $this->addLogMessage('Load Arc2, RDFTO, Foafpress includes/adapters');

        // load ARC2
        $this->pm->need('./arc2/ARC2');

        // load ARC2 Template Object
        $this->pm->need('./rdfto/rdfto.arc2');

        // load Foafpress includes
        $this->pm->need(dirname(__FILE__).'/Foafpress.inc');
        $this->pm->need(dirname(__FILE__).'/store-adapters/Foafpress.Arc2File');

        return;
    }

    // event listener for "sandbox_parse_start"
    public function CheckCache($filename)
    {
        // only do this, if the requested apptype is know
        if (!isset($this->extensiontype)) return;

        $apptypes_with_store_exporters = $this->arc2_exportfunctions;
        // do not doing this cache before post processing stuff, if the triple
        // store api is doing its own output serialization
        if (isset($apptypes_with_store_exporters[$this->extensiontype])) return;

        $this->addLogMessage('Check cache for '.$filename);

        // get preferenced laguage stack from LanguageChecker plugin
        if ($this->pm->isActive('LanguageChecker'))
        {
            $this->languageStackPreferences = $this->pm->LanguageChecker->getLanguageStackSimplified(true);
        }
        else
        {
            $this->languageStackPreferences = null;
        }

        $this->filename = $filename.$this->extensiontype;

        $cachedOutput = false;

        // check cache before doing anything
        if ($validCachedOutput = $this->cache->getVar($this->filename, 'FoafpressOutput_'.str_replace(',', '_', $this->languageStackPreferences)))
        {
            $cachedOutput = $validCachedOutput;
            $this->addLogMessage('Found valid cache.');
            $this->pm->subscribe('sandbox_flush_start', $this, 'PreventDoubleOutput'); // only to be safe not to echo two times "the same"
        }
        elseif (defined('IS_PRODUCTION_INSTANCE') && IS_PRODUCTION_INSTANCE === true)
        {
            $cachedOutput = $this->cache->getVar($this->filename, 'FoafpressOutput_'.str_replace(',', '_', $this->languageStackPreferences), -1);
            if ($cachedOutput)
            {
                $this->addLogMessage('Use invalid cache.');
            }
        }

        if ($cachedOutput)
        {
            $this->pm->unsubscribe('sandbox_flush_start', $this, 'sendHttpHeaders'); // called manually here

            /*
                experimental enabling of post output processing
                why: aggregating feeds and linked data is a performance issue
                     because the app needs to wait for a response to all the
                     http requests. To overcome this problem we could echo an
                     old output cache and process then the data aggregation to
                     create an updated cache for the next request.
                @see http://www.brandonchecketts.com/archives/performing-post-output-script-processing-in-php
                @see http://de2.php.net/manual/en/features.connection-handling.php#93441
            */
            ob_end_clean();
            header("Connection: close");
            header("Content-Encoding: none");
            // header('Content-Type: '.$this->extensiontype.'; charset=UTF-8');
            ignore_user_abort(true); // optional
            ob_start();
            echo $cachedOutput;
            $size = ob_get_length();
            header("Content-Length: $size");
            $this->sendHttpHeaders();
            ob_end_flush();     // Strange behaviour, will not work
            flush();            // Unless both are called !
            ob_end_clean();

            //do post output processing here
            $this->addLogMessage('Start post output processing.');
        }

        return;

    }

    // event listener for "sandbox_flush_start"
    public function PreventDoubleOutput()
    {
        die();
    }

    public function FindResource($file)
    {
        $this->addLogMessage('Try to find something for '.$file);

        $extensions = $this->config['types'];

        $file = $this->FindFileByDirectoryIndexCheck($file);

        $this->URI_Request = 'http://'.$_SERVER['SERVER_NAME'].str_replace(BASEDIR, BASEURL, $file);
        $this->addLogMessage('Update var URI_Request='.$this->URI_Request);

        $file = $this->CheckForApplicationTypeRequestByFileExtension($file);
        $this->addLogMessage('Found request for: '.($this->extensiontype?$this->extensiontype:'unresolved!'));

        $this->RedirectToUrlByContentNegotiation();

        if ($file_rdf = $this->GetAppropriateRdfFile($file))
        {
                $this->addLogMessage('Found appropriate RDF file: '.$file_rdf);
                $this->set_URI_Document($file_rdf);
                $this->sandbox->parse($file_rdf);
                // LoadResourceFromFile method will be triggered automatically by event dispatcher
                return;
        }

        $this->dieWithHttpErrorCode($this->URI_Request.' is not found', 404);

        return;
    }

    public function FindFileByDirectoryIndexCheck($file)
    {
        $extensions = $this->config['types'];

        if (is_dir($file) && isset($this->config['DirectoryIndex']) && $this->config['DirectoryIndex'])
        {
            $possible_index_files = explode(' ', $this->config['DirectoryIndex']);

            foreach ($possible_index_files as $index_file)
            {
                foreach ($extensions as $type=>$ext)
                {
                    $filename_to_test = realpath($file).DIRECTORY_SEPARATOR.$index_file.$ext;
                    if (file_exists($filename_to_test) && is_file($filename_to_test))
                    {
                        $file_new_name = realpath($file).DIRECTORY_SEPARATOR.$index_file; // without extension
                        break;
                    }
                }

                if (isset($file_new_name))
                {
                    $file = $file_new_name;
                    break;
                }
            }
        }

        return $file;

    }

    public function CheckForApplicationTypeRequestByFileExtension($file)
    {
        $this->addLogMessage('Check for requested application type by file extension.');

        $extensions = $this->config['types'];

        foreach ($extensions as $type=>$ext)
        {
            if (substr($file, -1*strlen($ext)) == $ext)
            {
                $file = substr($file, 0, -1 * strlen($ext));
                $this->extensiontype = $type;
                break;
            }
        }

        return $file;
    }

    public function GetAppropriateRdfFile($file)
    {
        $this->addLogMessage('Check for an existing RDF file.');

        $extensions = $this->config['types'];

        foreach ($extensions as $ext)
        {
            if (is_readable($file.$ext))
            {
                return $file.$ext;
            }
        }

        return false;
    }

    public function LoadResourceFromFile($file)
    {
        $this->set_URI_Document($file);

        if (false === ($index = $this->cache->getVar($this->URI_Document, 'Foafpress', time()-filectime($file), 0)))
        {
            // load arc2 parser
            $parser = ARC2::getRDFParser();
            // parse rdf document
            $parser->parse($this->URI_Document, $this->content->SANDBOX);
            // get rdf content as multi-indexed array
            $index = $parser->getSimpleIndex(0);
            $this->cache->saveVar($index, $this->URI_Document, 'Foafpress', true);
        }

        // load namespaces from config
        $namespaces = array();
        if (isset($this->config['ns'])) $namespaces = $this->config['ns'];

        // load rdf content as arc2 resource
        $this->arc2_resource = ARC2::getResource(array('ns'=>$namespaces));
        $this->arc2_resource->setIndex($index);

        $uri = $this->ResolveResourceRequest();

        //die($uri);

        // set shown resource
        $this->arc2_resource->setURI($uri);

        //*
        if ($exporttype = $this->isExportRequest())
        {
            if (isset($this->arc2_exportfunctions[$exporttype]))
            {
                // $this->exportRdfData($exporttype);
                $this->exportRdfData();
            }
            else
            {
                $template_type = $this->config['types'][$exporttype];
            }
        }
        //*/

        if (!isset($template_type))
        {
            $template_type = $this->config['types'][$this->config['typefallback']];
        }

        // load Foafpress wrapper for arc2 resource
        $FP = new Foafpress_Resource_Arc2File(array('FP_config' => &$this->config,
                                           'spcms_cache' => &$this->cache,
                                           'spcms_pm' => &$this->pm
                                          )
                                    );

        $FP->initResource($this->arc2_resource); //$FP->initResource(&$this->arc2_resource);

        // set shown resource
        $FP->uri = $uri;

        // add sameAs resources
        if ($this->config['LinkedData']['followSameas'] == true)
            $FP->includeSameAs();

        // default namespace in Foafpress wrapper
        $concept = $FP->updateNamespacePrefix();

        // use ns:concept to set controller, and fallbacks for layout and template

        if ($concept !== false && $FP->ns_prefix)
        {

            // try to set controller

            try
            {
                $action_controller_class_path = $this->pm->need($FP->ns_prefix.DIRECTORY_SEPARATOR.$concept);

                if (!isset($_SERVER['REQUEST_METHOD']) || !$_SERVER['REQUEST_METHOD'])
                {
                    $this->dieWithHttpErrorCode('Empty request method!', 503); // TODO is 503 right?
                }
                elseif (in_array($_SERVER['REQUEST_METHOD'], $this->config['supportedmethods']))
                {
                    $action_controller_class_name = ucfirst($FP->ns_prefix.'_'.$concept.'_Controller');
                    $action_controller_use_method = strtolower($_SERVER['REQUEST_METHOD']).'_request';
                    $action_controller = new $action_controller_class_name($this->sandbox, $action_controller_class_path);

                    // execute controller request action with resource
                    $action_controller->add_resource_object($FP);
                    $action_controller->set_template_extension($template_type);
                    $action_controller->$action_controller_use_method();
                }
                else
                {
                    $this->dieWithHttpErrorCode($_SERVER['REQUEST_METHOD'].' is not supported here!', 503); // TODO is 503 right?
                }
            }
            catch(Exception $e)
            {
                throw $e;
            }

            // set layout

            if (isset($this->config['layout']))
            {
                $layoutfile = $this->config['layout'];
            }
            elseif ($this->sandbox->layoutname)
            {
                $layoutfile = $this->sandbox->layoutname;
            }
            else
            {
                $layoutfile = 'Foafpress';
            }

            if ($this->sandbox->templateSearch($layoutfile.$template_type))
            {
                // change sandbox layout which was configured before
                $this->sandbox->templateSetLayout($layoutfile.$template_type);
            }
            else
            {
                // TODO: line?
                $this->dieWithException($layoutfile.$template_type.'.php not found!');
            }

            // try to set template

            if (!$this->sandbox->templatename)
            {
                // if not set then its name is namespace/concept.tpl
                $templatefile = $FP->ns_prefix.DIRECTORY_SEPARATOR.$concept.$template_type;

                if ($this->sandbox->templateSearch($templatefile))
                {
                    // change sandbox template which was configured before
                    $this->sandbox->templateSetName($templatefile);
                }
                else
                {
                    // TODO: line?
                    $this->dieWithException($templatefile.'.php not found!');
                }
            }

        }

        return;
    }

    public function set_URI_Document($file)
    {
        // get url of rdf document
        $this->URI_Document = 'http://'.$_SERVER['SERVER_NAME'].str_replace(BASEDIR, BASEURL, $file);

        if ($this->URI_Request && $this->extensiontype)
        {
            $this->URI_Document = $this->URI_Request;
        }

        $this->addLogMessage('Set URI for document to: '.$this->URI_Document);

        return;
    }

    /**
     * Resolve best resource from request
     *
     * Checks if the requested URI is drescribed in document and if not -- make
     * a best guess (check foaf:document about the described resource)
     */
    protected function ResolveResourceRequest()
    {
        /* Create a stack of possible URIs what may used to describe
         * a resource in the file. Use the invers algorithm from method creating
         * the file name. Check the file resource index for that URIs and return
         * the first match.
         *
         * Example:
         *
         * If http://example.org/me is requested:
         * - what leads to http://example.org/me.html (URI_Document) by content negotiation
         * - what may be dscribed in var/www/example.org/me.nt (http://example.org/me.nt)
         * all those URIs will be tested for availability in file resource index.
         */

        $stackOfUris = array();

        $stackOfUris[] = $this->URI_Document; // cannot be null

        foreach ($this->config['types'] as $fileExtension)
        {
            if (substr($this->URI_Document, -1 * strlen($fileExtension)) == $fileExtension)
            {
                $URI_Document_without_extension = substr($this->URI_Document, 0, -1 * strlen($fileExtension));
                $stackOfUris[] = $URI_Document_without_extension;
            }
        }

        foreach ($this->config['types'] as $fileExtension)
        {
            $stackOfUris[] = $URI_Document_without_extension . $fileExtension;
        }

        $DirectoryIndexes = explode(' ', $this->config['DirectoryIndex']);

        foreach ($DirectoryIndexes as $directoryIndex)
        {
            if (substr($URI_Document_without_extension, -1 * strlen($directoryIndex)) == $directoryIndex)
            {
                $URI_Document_without_index = substr($URI_Document_without_extension, 0, -1 * strlen($directoryIndex));
                $stackOfUris[] = $URI_Document_without_index;
            }
        }

        if ($this->URI_Request !== null && !$this->extensiontype)
        {
            // initially requested resource
            $stackOfUris[] = $this->URI_Request;
        }

        if ($xmlbase = stripos($this->content->SANDBOX, 'xml:base="'))
        {
            // no statement in index, check for xml:base definition

            $baseStart = substr($this->content->SANDBOX, $xmlbase+10);
            $xmlbase = substr($baseStart, 0, strpos($baseStart, '"'));

            $stackOfUris[] = $xmlbase;
        }

        // add first uri in index stack
        $uriStack = array_keys($this->arc2_resource->index);
        $stackOfUris[] = array_shift($uriStack);

        $stackOfUris = array_unique($stackOfUris);

        foreach ($stackOfUris as $resource)
        {
            if (isset($this->arc2_resource->index[$resource]))
            {
                return $resource;
            }
        }
    }

    protected function isExportRequest()
    {
        // if one of the application types is requested with q=1 then forward location to file
        $this->RedirectToUrlByContentNegotiation();

        // if RDF file is requested directly and the client can work with the
        // application type (or file extensions imply export, see Foafpress
        // config) then set export=true

        // virtuell existing RDF files
        if ($this->URI_Document && $this->URI_Document == $this->URI_Request &&
                                   (($this->config['extensiontoexport']) ||
                                    ($this->extensiontype && $this->isRequestType(array($this->extensiontype), true))
                                   )
           )
        {
            //die('export virtual file '.$this->extensiontype);
            return $this->extensiontype;
        }

        // for real existing RDF files
        if ($this->URI_Document && !$this->URI_Request)
        {
            foreach ($this->config['types'] as $type=>$ext)
            {
                if (substr($this->URI_Document, -1*strlen($ext)) == $ext)
                {
                    $extensiontype = $type;
                    break;
                }
            }

            if (isset($extensiontype) && ($this->config['extensiontoexport'] || $type = $this->isRequestType(array($extensiontype), true)))
            {
                //die('export real file '.$type);
                return $type;
            }

        }

        return false;

    }

    public function RedirectToUrlByContentNegotiation()
    {
        // if one of the application types is requested with q=1 then forward location to file
        if ($this->URI_Request && !$this->extensiontype && $type = $this->isRequestType(array_keys($this->config['types'])))
        {
            // change from 301 to 303, @see http://www4.wiwiss.fu-berlin.de/bizer/pub/LinkedDataTutorial/#Terminology
            header('Content-Type: '.$type, true, 303);
            // using the vary header because Foafpress can deliver different stuff for a resource
            // @see http://www4.wiwiss.fu-berlin.de/bizer/pub/LinkedDataTutorial/#ExampleHTTP
            header('Vary: Accept', true, 303);
            // set the location where the redirect leads to
            header('Location: '.$this->URI_Request.$this->config['types'][$type], true, 303); exit();
        }

        return;
    }

    protected function isRequestType(Array $types, $soft = false)
    {

        if (!isset($this->possibleTypes))
        {
            $this->possibleTypes = array();

            // get accepted types
            $http_accept = trim($_SERVER['HTTP_ACCEPT']);

            // save accepted types in array
            $accepted = explode(',', $http_accept);

            if (count($accepted)>0)
            {
                // extract accepting ratio
                $test_accept = array();
                foreach($accepted as $format)
                {
                    $formatspec = explode(';',$format);
                    $k = trim($formatspec[0]);
                    if (count($formatspec)==2)
                    {
                        $test_accept[$k] = trim($formatspec[1]);
                    }
                    else
                    {
                        $test_accept[$k] = 'q=1.0';
                    }
                }

                // sort by ratio
                arsort($test_accept);

                $this->possibleTypes = $test_accept;
            }
        }

        //$this->print_r($test_accept);

        if ($this->possibleTypes)
        {
            $accepted_order = array_keys($this->possibleTypes);

            foreach ($types as $type)
            {
                if (isset($this->possibleTypes[$type]))
                {
                    // client says it can process the file type

                    // softcheck
                    if ($soft === true) //die('softexport');
                        return $type;

                    // hardcheck
                    if ($accepted_order[0] == $type || $this->possibleTypes[$type] == 'q=1.0')
                        return $type;
                }

            }

        }

        return false;

    }

    public function sendHttpHeaders()
    {
        $requesttype = false;

        if ($this->extensiontype)
        {
            $requesttype = $this->extensiontype;
        }
        else
        {
            foreach ($this->config['types'] as $type=>$ext)
            {
                if (substr($this->URI_Document, -1*strlen($ext)) == $ext)
                {
                    $requesttype = $type;
                    break;
                }
            }
        }

        if ($requesttype && isset($this->config['http_headers'][$requesttype]))
        {
            foreach ($this->config['http_headers'][$requesttype] as $header_key=>$header_value)
            {
                header($header_key.': '.$header_value, true);
            }
        }

        return $requesttype;
    }

    public function exportRdfData()
    {
        $exportfunction = $this->arc2_exportfunctions;

        // header('Content-Type: '.$requesttype, true, 200);
        $requesttype = $this->sendHttpHeaders();
        echo $this->arc2_resource->$exportfunction[$requesttype]($this->arc2_resource->index);
        exit();

    }

    public function dieWithHttpErrorCode($message = null, $http_error_code = 0)
    {
        if (!$message || !$http_error_code)
        {
            throw new Exception('Need status message and HTTP error code! Message: '.$message.' / Code: '.$http_error_code);
        }

        header("HTTP/1.0 ".$http_error_code, true, $http_error_code);
        die('<h1>'.$message.'</h1>');

    }

    public function dieWithException($message = null)
    {
        if (!$message)
        {
            throw new Exception('Need error message!');
        }

        throw new Exception($message); // TODO insert line where the exception was triggered

    }

    public function print_r($array)
    {
        echo '<pre>';
        print_r($array);
        die('</pre>');
    }
}

