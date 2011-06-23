<?php

class Foafpress_Store_Arc2File /*extends Foafpress_Store*/
{

}

class Foafpress_Resource_Arc2File extends ARC2File_Template_Object
{

    public $spcms_pm = null;
    public $spcms_cache = null;
    public $FP_config = array();
    public $logUsage = array();

    public $templateImage = '<img src="##URL##" alt="##DESC##"/>';
    
    public function __construct(Array $environment)
    {
        $this->FP_config = $environment['FP_config'];
        $this->spcms_pm = $environment['spcms_pm'];
        $this->spcms_cache = $environment['spcms_cache'];
        
        $this->levelMax = $this->FP_config['LinkedData']['maxlevel'];
        $this->requestsMax = $this->FP_config['LinkedData']['maxrequests'];
        $this->requestsTimeout = $this->FP_config['LinkedData']['timeout'];
        
        $this->ignoreUris = $this->FP_config['LinkedData']['ignoreResources'];

        $this->cacheTimeActivity = $this->FP_config['Activity']['cachetime'];
        
        return;
    }
    
    /*
    public function __clone()
    {
    }
    */
    
    /* Get data from cache
     */
    public function getCache(Array $vars)
    {
        $name = isset($vars['name'])? $vars['name'] : null;
        $space = isset($vars['space'])? $vars['space'] : 'Foafpress';
        $time = isset($vars['time'])? $vars['time'] : $this->FP_config['LinkedData']['cachetime'];
        
        if ($name === null) return false;
        
        //TODO: $c['LinkedData']['cacheIncrement']
        
        return $this->spcms_cache->getVar($name, $space, $time);
    }
    
    /* Write data to cache
     */
    public function saveCache(Array $vars)
    {
        $data = isset($vars['data'])? $vars['data'] : null;
        $name = isset($vars['name'])? $vars['name'] : null;
        $space = isset($vars['space'])? $vars['space'] : 'Foafpress';
        $time = isset($vars['time'])? $vars['time'] : true;
        
        if ($name === null) return false;
        
        return $this->spcms_cache->saveVar($data, $name, $space, $time);
    }
    
    /*
    public function listBnodes($resource = array())
    {
        $bnodes = array();
        
        foreach ($resource as $predicate)
        {
            if (is_array($predicate))
            {
                foreach($predicate as $value)
                {
                    if (isset($value['type']) && $value['type']=='bnode')
                        $bnodes[] = $value['value'];
                }
            }
        }
        
        return $bnodes;
    }
    
    public function deleteBnodes($resource = array())
    {
        $bnodes = array();
        
        foreach ($resource as $predicate => $values)
        {
            if (is_array($values))
            {
                foreach($values as $vkey => $value)
                {
                    if (isset($value['type']) && $value['type']=='bnode')
                        unset($resource[$predicate][$vkey]);
                }
            }
            
            if (count($resource[$predicate]) == 0)
                unset($resource[$predicate]);
        }
        
        return $resource;
    }
    */
    
    /**
     */
    public function listActivity(Array $check = array(), $numberItems = null)
    {
        $activity = parent::listActivity($check, $numberItems);
        
        foreach ($activity['stream'] as $i => $item)
        {
            $activity['stream'][$i]['cssclass'] = 'to-from from-'.$this->getIconLayout($item['source']).' to-'.$this->getIconLayout($item['link']);
            $this->spcms_pm->publish('foafpress_activity_from_'.$this->getIconLayout($item['source']), $activity['stream'][$i]);
            $this->spcms_pm->publish('foafpress_activity_to_'.$this->getIconLayout($item['link']), $activity['stream'][$i]);
        }
        
        return $activity;
    }
    
    public function getLiteral(Array $predicates = array(), Array $languages = array(), $strict = false)
    {
        if (count($languages) == 0 && isset($this->languages))
        {
            $languages = $this->languages;
        }
        elseif (count($languages) == 0 && $this->spcms_pm->isActive('LanguageChecker'))
        {
            // get preferenced laguage stack from LanguageChecker plugin
            $languages = array_unique(
                            array_merge(
                                $this->spcms_pm->LanguageChecker->getUserPreferences(),
                                $this->spcms_pm->LanguageChecker->getLanguageStack()
                            )
                         );
        }
        
        return parent::getLiteral($predicates, $languages, $strict);
    }
    
    public function getImage(Array $predicates = array(), $useThumbnail = null)
    {
        if (!is_array($predicates) || count($predicates) == 0) $predicates = array('foaf_depiction', 'foaf_img', 'foaf_logo');
        if ($useThumbnail == null) $useThumnail = false;
        
        //die('<pre>'.print_r(parent::getImage($predicates, $useThumbnail), true).'</pre>');
        
        return parent::getImage($predicates, $useThumbnail);
    }
    
    public function getIconLayout($uri)
    {
        return str_replace('.', '_', implode('.', array_slice(explode('.', parse_url($uri, PHP_URL_HOST)), -2)));
    }

    /* Write message to log
     *
     * you may implement it for debugging reasons
     */
    protected function addLogMessage($msg)
    {
        $this->spcms_pm->publish('sandbox_add_log_message', $msg);
        return true;
    }
    
}

