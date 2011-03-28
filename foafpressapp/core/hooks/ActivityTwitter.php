<?php

class ActivityTwitter extends SandboxPlugin
{

    protected function init()
    {
        // add Twitter activity handler
        $this->pm->subscribe('foafpress_activity_from_twitter_com', $this, 'formatOutput'); // parameters: event name, class name or instance, event handler method
        
        return;
        
    }
    
    public function formatOutput(&$activityItem)
    {
        // delete user name prefix
        $msg = " ".substr(strstr($activityItem['title'],': '), 2)." ";
        // $msg = $activityItem['description'];
        
		// link stuff
		$msg = $this->findlinks($msg);
		
        $activityItem['output'] = $msg;

        return $activityItem;
    }

    private function findlinks($text) {

        // using code from "Twitter for Wordpress" plugin by Ricardo Gonzáles,
        // released under GPL, http://rick.jinlabs.com/code/twitter

        // match protocol://address/path/file.extension?some=variable&another=asf%
        $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\">$1</a>", $text);

        // match www.something.domain/path/file.extension?some=variable&another=asf%
        $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\">$1</a>", $text);    

        // match name@address
        $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\">$1</a>", $text);

        // match #hashtags
        $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1#<a href=\"http://twitter.com/#search?q=$2\">$2</a>$3 ", $text);

        // match @user
        $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1@<a href=\"http://twitter.com/$2\">$2</a>$3 ", $text);

        return $text;
    }

}
