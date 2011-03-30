<?php

class ActivityFlickr extends SandboxPlugin
{

    protected function init()
    {
        // add Flickr activity handler
        $this->pm->subscribe('foafpress_activity_from_flickr_com', $this, 'formatOutput'); // parameters: event name, class name or instance, event handler method
        
        return;
        
    }
    
    public function formatOutput(&$activityItem)
    {
        $content = htmlspecialchars(strip_tags($activityItem['title']), ENT_COMPAT, 'UTF-8');
        
        if (isset($activityItem['contentarray_origin']['http://search.yahoo.com/mrss/thumbnail']))
        {
            $image = str_replace('_s.', '_t.', $activityItem['contentarray_origin']['http://search.yahoo.com/mrss/thumbnail'][0]['value']);
            $content = '<img class="mediathumb" src="'.$image.'" alt="" height="75"/> '.$content;
        }
        
        
        
        $activityItem['output'] = '<a href="'.$activityItem['link'].'">'.$content.'</a>';

        return $activityItem;
    }

}
