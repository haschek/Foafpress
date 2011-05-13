<?php

class Foafpress_Store
{



}

class Foafpress_Controller extends SandboxPlugin
{
    // Foafpress Resource Object (RDF Template Object)
    public $RESOURCE = null;

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
        
        if (isset($this->RESOURCE->resource->index[$this->RESOURCE->uri]['http://www.w3.org/1999/02/22-rdf-syntax-ns#type']))
        {
            $type['uri'] = $this->RESOURCE->resource->index[$this->RESOURCE->uri]['http://www.w3.org/1999/02/22-rdf-syntax-ns#type'][0]['value'];
            $add_colon = create_function('$namespace', 'return $namespace.":";');
            $type['short'] = str_replace($this->RESOURCE->resource->ns, array_map($add_colon, array_keys($this->RESOURCE->resource->ns)), $type['uri']);
            $type['css'] = str_replace(':', '_', $type['short']);
        }
        
        return $type;
        
    }

}
