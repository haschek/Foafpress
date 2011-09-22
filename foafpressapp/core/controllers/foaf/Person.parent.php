<?php

require_once 'Agent.parent.php';

class Foaf_Person_Controller_Parent extends Foaf_Agent_Controller_Parent
{
    public function write_data_to_view()
    {
        parent::write_data_to_view();

        // -- Activity / Feeds -------------------------------------------------

        $this->read_activity_stream();

        // -- Projects ---------------------------------------------------------

        $this->read_projects();

        // -- Skills -----------------------------------------------------------

        $this->read_skills();

        // -- Network ----------------------------------------------------------

        $this->read_network();

    }

    public function read_activity_stream($resource = null)
    {

        // -- Activity / Feeds -------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $activity = $resource->listActivity();
        if (isset($activity['stream'])) $this->content->activity = $activity;
        unset($activity);

    }

    public function read_projects($resource = null)
    {

        // -- Projects ---------------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $list_of_project_objects = array_unique(array_merge($resource->currentProject, $resource->pastProject)/*, SORT_REGULAR*/);;
        $list_of_projects = array();

        foreach ($list_of_project_objects as $project_object)
        {
            if (is_object($project_object) && $project_label = $project_object->getLiteral(array('doap_name', 'dc_title', 'rdfs_label', 'foaf_name')))
            {
                $project_details = array();

                $project_details['label'] = $project_label;

                if ($description = $project_object->getLiteral(array('doap_description', 'dc_description', 'rdfs_comment')))
                {
                    $project_details['description'] = $description;
                }
                unset($description);

                if ($homepage = array_merge($project_object->doap_homepage, $project_object->foaf_homepage/* ERROR wegen '-' TODO, $project_object->rdfohloh_ohloh-page*/))
                {
                    if (isset($homepage[0])) $homepage = $homepage[0];
                    if (is_object($homepage))
                    {
                        $project_details['link'] = $homepage->uri;
                    }
                    else
                    {
                        $project_details['link'] = $homepage;
                    }
                }
                elseif (substr($project_object->uri, 0, 1) != '_')
                {
                    $project_details['link'] = $project_object->uri;
                }
                unset($homepage);

                $list_of_projects[] = $project_details;

                unset($project_details);
            }
            elseif (!is_object($project_object) && @parse_url($project_object) === false)
            {
                $list_of_projects[] = array(
                    'label' => $project_object
                );
            }
            unset($project_object);
        }

        $this->content->list_of_projects = $list_of_projects;
        unset($list_of_projects);

    }

    public function read_skills($resource = null)
    {

        // -- Skills -----------------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $list_of_resume_objects = $resource->rdfs_seeAlso('cv:CV');
        $list_of_skill_objects = array();

        if (is_array($list_of_resume_objects))
        {
            foreach ($list_of_resume_objects as $resume_object)
            {
                $list_of_skill_objects = array_unique(array_merge($list_of_skill_objects, $resume_object->cv_hasSkill));
            }
        }

        $list_of_skills = array();

        foreach ($list_of_skill_objects as $skill_object)
        {
            if (is_object($skill_object) && $skill_label = $skill_object->getLiteral(array('cv_skillName')))
            {
                $skill_level = $skill_object->cv_skillLevel;
                $list_of_skills[] = array(
                    'label' => $skill_label,
                    'level' => $skill_level?$skill_level[0]:false
                );
                unset($skill_level);
                unset($skill_label);
            }

            unset($skill_object);
        }

        $this->content->list_of_skills = $list_of_skills;
        unset($list_of_skill_objects);
        unset($list_of_skills);

    }

    public function read_network($resource = null)
    {

        // -- Network ----------------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $list_of_known_persons = array();
        $list_of_person_objects = array_unique(array_merge(
                                    $resource->rel_closeFriendOf,
                                    $resource->rel_acquaintanceOf,
                                    $resource->rel_collaboratesWith,
                                    $resource->rel_colleagueOf,
                                    $resource->rel_worksWith,
                                    $resource->foaf_knows)/*, SORT_REGULAR*/);


        if ($list_of_person_objects)
        {
            foreach ($list_of_person_objects as $person_object)
            {
                if (is_object($person_object) && $person_name = $person_object->getLiteral(array('foaf_name', 'foaf_nick', 'rdfs_label', 'dc_title')))
                {
                    $person_info = array(
                        'name_or_nick' => $person_name,
                        'homepage_link' => null, 'homepage_label' => null,
                        'weblog_link' => null, 'weblog_label' => null,
                        'resource_link' => null, 'resource_label' => $person_name.' (FOAF)',
                        'depiction' =>'<span class="nodepiction"> </span>'
                    );

                    if (($person_homepage = $person_object->foaf_homepage) && isset($person_homepage[0]))
                    {
                        $person_info['homepage_link'] = is_object($person_homepage[0])?$person_homepage[0]->uri:$person_homepage[0];
                        $person_info['homepage_label'] = (is_object($person_homepage[0]) && $homepage_label=$person_homepage[0]->getLiteral(array('rdfs_label', 'dc_title')))?$homepage_label:'Homepage';

                        unset($person_homepage);
                    }

                    if (($person_weblog = $person_object->foaf_weblog) && isset($person_weblog[0]))
                    {
                        $person_info['weblog_link'] = is_object($person_weblog[0])?$person_weblog[0]->uri:$person_weblog[0];
                        $person_info['weblog_name'] = (is_object($person_weblog[0]) && $weblog_label=$person_weblog[0]->getLiteral(array('rdfs_label', 'dc_title')))?$weblog_label:'Weblog';

                        unset($person_weblog);
                    }

                    if (substr($person_object->uri, 0, 7) == 'http://')
                    {
                        $person_info['resource_link'] = $person_object->uri;
                    }

                    if ($person_depiction = $person_object->getImage())
                    {
                        $person_info['depiction'] = $person_depiction;
                        unset($person_depiction);
                    }

                    $list_of_known_persons[] = $person_info;

                    unset($person_name);
                    unset($person_info);
                }
                unset($person_object);
            }
        }

        unset($list_of_person_objects);

        $this->content->list_of_known_persons = $list_of_known_persons;
        unset($list_of_known_persons);

    }
}
