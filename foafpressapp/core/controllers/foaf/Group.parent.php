<?php

require_once 'Agent.parent.php';

class Foaf_Group_Controller_Parent extends Foaf_Agent_Controller_Parent
{
    public function write_data_to_view()
    {
        parent::write_data_to_view();

        // -- Activity / Feeds -------------------------------------------------

        $this->read_activity_stream();

        // -- Network ----------------------------------------------------------

        $this->read_members();

    }

    public function read_activity_stream($resource = null)
    {

        // -- Activity / Feeds -------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $activity = $resource->listActivity();
        if (isset($activity['stream'])) $this->content->activity = $activity;
        unset($activity);

    }

    public function read_members($resource = null)
    {
        // TODO
    }

}
