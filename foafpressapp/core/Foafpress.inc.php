<?php

class Foafpress_Store
{

}

class Foafpress_Controller extends SandboxPlugin
{
    // Foafpress Resource Object (RDF Template Object)
    public $RESOURCE = null;

    // template file extension
    public $template_file_extension = '.html';

    public function add_resource_object($resource_object = null)
    {
        // TODO: test object type
        if ($resource_object && is_object($resource_object))
        {
            $this->RESOURCE = $resource_object;
        }
        else
        {
            // TODO: throw exception
        }
    }

    public function set_template_extension($extension)
    {
        $this->template_file_extension = $extension;
        return;
    }

    // Action for HTTP GET request on resource
    public function get_request()
    {
        // TODO: write error code to http header
        throw new Exception("Action for GET request is not defined!");
    }

    // Action for HTTP POST request on resource
    public function post_request()
    {
        // TODO: write error code to http header
        throw new Exception("Action for POST request is not defined!");
    }

    // Action for HTTP HEAD request on resource
    public function head_request()
    {
        // TODO: write error code to http header
        throw new Exception("Action for HEAD request is not defined!");
    }

    // Action for HTTP PUT request on resource
    public function put_request()
    {
        // TODO: write error code to http header
        throw new Exception("Action for PUT request is not defined!");
    }

    // Action for HTTP DELETE request on resource
    public function delete_request()
    {
        // TODO: write error code to http header
        throw new Exception("Action for DELETE request is not defined!");
    }

    // Action for HTTP TRACE request on resource
    public function trace_request()
    {
        // TODO: write error code to http header
        throw new Exception("Action for TRACE request is not defined!");
    }

    // Action for HTTP OPTIONS request on resource
    public function options_request()
    {
        // TODO: write error code to http header
        throw new Exception("Action for OPTIONS request is not defined!");
    }
    
    /**
     * Get Resource Type
     *
     * evaluate resource type and return array with various formats
     *
     * @return array $type [uri] => resource uri, [short] => modelns:concept, [css] => modelns_concept
     */
    public function get_resource_type_info($resource = null)
    {
        if (!$resource) $resource = $this->RESOURCE;
        
        // TODO: use owl:thing as fallback for unknown resource type
        $type = array(
            'uri' => 'http://example.com/unknown#unknown',
            'short' => 'unknown:Unknown',
            'css' => 'unknown_Unknown'
        );
        
        if (isset($resource->resource->index[$resource->uri]['http://www.w3.org/1999/02/22-rdf-syntax-ns#type']))
        {
            $type['uri'] = $resource->resource->index[$resource->uri]['http://www.w3.org/1999/02/22-rdf-syntax-ns#type'][0]['value'];
            $add_colon = create_function('$namespace', 'return $namespace.":";');
            $type['short'] = str_replace($resource->resource->ns, array_map($add_colon, array_keys($resource->resource->ns)), $type['uri']);
            $type['css'] = str_replace(':', '_', $type['short']);
        }
        
        return $type;
        
    }

    /**
     * Set sandbox template for resource type
     */
    public function set_resource_type_template($resource = null)
    {
        if (!$resource) $resource = $this->RESOURCE;

        $typeformats = $this->get_resource_type_info($resource);
        $templatefile = str_replace(':', DIRECTORY_SEPARATOR, $typeformats['short']).$this->template_file_extension;
        
        if ($this->sandbox->templateSearch($templatefile))
        {
            // change sandbox template which was configured before
            $this->sandbox->templateSetName($templatefile);
        }

        return;
    }

    /**
     * Write requested main language to view content
     */
    public function write_mainlanguage_to_view()
    {
        $languages_user = $this->pm->LanguageChecker->getLanguageStackSimplified();
        $this->content->MAINLANGUAGE = array_shift($languages_user);

        return;
    }

    /**
     * Write requested main language to view content
     */
    public function write_rdfmetalinks_to_view()
    {
        // -- Alternate meta links to RDF data ---------------------------------

        // TODO: move this to a global parent controller
        $alternate_meta_links = array();
        $document_uri = $this->pm->load('Foafpress')->URI_Document;
        $document_extensiontype = $this->pm->load('Foafpress')->extensiontype;
        $application_types = $this->pm->load('Foafpress')->config['types'];
        $this->content->ALLOW_RDF_DOWNLOAD = $this->pm->load('Foafpress')->config['extensiontoexport'];

        foreach ($application_types as $type=>$ext)
        {
            if ($document_extensiontype !== $type)
            {
                $alternate_meta_links[] = array(
                    'type'=> $type,
                    'href'=> substr($document_uri, 0, -1*strlen($application_types[$document_extensiontype])).$ext
                );
            }
            unset($type);
            unset($ext);
        }

        $this->content->META_ALTERNATE_LINKS = $alternate_meta_links;

        unset($alternate_meta_links);
        unset($application_types);
        unset($document_uri);
        unset($document_extensiontype);

        return;
    }
}
