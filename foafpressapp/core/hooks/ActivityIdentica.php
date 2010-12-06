<?php

class ActivityIdentica extends SandboxPlugin
{

    protected function init()
    {
        // add Identica activity handler
        $this->pm->subscribe('foafpress_activity_from_identi_ca', $this, 'formatOutput'); // parameters: event name, class name or instance, event handler method
        
        return;
        
    }
    
    public function formatOutput(&$activityItem)
    {
        $activityItem['output'] = $activityItem['content'];
        return $activityItem;
    }

}
