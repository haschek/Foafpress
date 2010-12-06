<?php

class Foafpress_Store
{



}

class Foafpress_Controller extends SandboxPlugin
{
    // Foafpress Resource Object (RDF Template Object)
    public $RESOURCE = null;

    public function add_resource_object($resource_object)
    {
        // TODO: test object type
        $this->RESOURCE = $resource_object;
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

}
