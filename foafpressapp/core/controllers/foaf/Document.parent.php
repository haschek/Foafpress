<?php

class Foaf_Document_Controller_Parent extends Foafpress_Controller
{
    public function get_request()
    {
        // -- Prepare output ---------------------------------------------------

        $this->write_mainlanguage_to_view();
        $this->write_rdfmetalinks_to_view();
        $this->set_resource_type_template();

        // -- Layout class -----------------------------------------------------

        $this->content->resource_type_info = $this->get_resource_type_info();

        // -- Resource content -------------------------------------------------

        $this->write_data_to_view();

        // -- Route to topic controller ----------------------------------------

        $topic = $this->RESOURCE->foaf_primaryTopic;

        if ($topic[0] && is_object($topic[0]))
        {
            $concept = $topic[0]->updateNamespacePrefix();

            if ($concept !== false && $topic[0]->ns_prefix)
            {
                try
                {
                    $action_controller_class_path = $this->pm->need($topic[0]->ns_prefix.DIRECTORY_SEPARATOR.$concept);
                    $action_controller_class_name = ucfirst($topic[0]->ns_prefix.'_'.$concept.'_Controller');
                    // execute controller request action with resource
                    $action_controller = new $action_controller_class_name($this->sandbox, $action_controller_class_path);
                    $action_controller->add_resource_object($topic[0]);
                    $action_controller->set_template_extension($this->template_file_extension);
                    $action_controller->get_request();
                }
                catch(Exception $e)
                {
                    throw $e;
                }

            }

        }

    }

    public function write_data_to_view()
    {
        // -- Basics - Name, Info, Depiction -----------------------------------

        $this->read_basic_info();
    }

    public function read_basic_info($resource = null)
    {

        // -- Basics - Name, Info, Depiction -----------------------------------

        $this->content->META_TITLE = $this->RESOURCE->getLiteral(array('rdfs_label', 'dc_title'));
        $this->content->META_DESCRIPTION = $this->RESOURCE->getLiteral(array('rdfs_comment', 'dc_description'));
        $this->content->CONTENT = $this->RESOURCE->getLiteral(array('content_encoded', 'sioc_content'));

    }

}