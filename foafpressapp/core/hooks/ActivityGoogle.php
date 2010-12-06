<?php

class ActivityGoogle extends SandboxPlugin
{

    protected function init()
    {
        // add Identica activity handler
        $this->pm->subscribe('foafpress_activity_from_google_com', $this, 'formatOutput'); // parameters: event name, class name or instance, event handler method
        
        return;
        
    }
    
    public function formatOutput(&$activityItem)
    {
        // Google Code
        if (strpos($activityItem['link'], 'code.google.com') !== false)
            $activityItem['output'] = $activityItem['title'];
        return $activityItem;
    }

}
