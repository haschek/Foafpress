<?php

class Foaf_Agent_Controller_Parent extends Foafpress_Controller
{
    public function get_request()
    {
        // -- Layout class -----------------------------------------------------

        $this->content->resource_type_info = $this->get_resource_type_info();

        // -- Document Meta Data (for HTML Head) -------------------------------

        // TODO: put this in its own foaf:Document controller
        $resource_uri = $this->RESOURCE->uri; // save uri of shown resource
        $this->RESOURCE->uri = $this->pm->load('Foafpress')->URI_Document; // use uri of resource container
        // TODO: problems if URI_Document != xml:base in RDF file
        $this->content->META_TITLE = $this->RESOURCE->getLiteral(array('rdfs_label', 'dc_title'));
        $this->content->META_DESCRIPTION = $this->RESOURCE->getLiteral(array('rdfs_comment', 'dc_description'));
        $this->content->CONTENT = $this->RESOURCE->getLiteral(array('content_encoded', 'sioc_content'));
        $languages_user = $this->pm->LanguageChecker->getLanguageStackSimplified();
        $this->content->MAINLANGUAGE = array_shift($languages_user);
        $this->RESOURCE->uri = $resource_uri; // restore uri of resource

        // -- Alternate meta links to RDF data ---------------------------------

        // TODO: move this to a global parent controller
        $alternate_meta_links = array();
        $document_uri = $this->pm->load('Foafpress')->URI_Document;
        $document_extensiontype = $this->pm->load('Foafpress')->extensiontype;
        $application_types = $this->pm->load('Foafpress')->config['types'];
        $this->content->ALLOW_RDF_DOWNLOAD = $this->pm->load('Foafpress')->config['extensiontoexport'];

        foreach ($application_types as $type=>$ext)
        {
            if ($document_extensiontype !== $type)
            {
                $alternate_meta_links[] = array(
                    'type'=> $type,
                    'href'=> substr($document_uri, 0, -1*strlen($application_types[$document_extensiontype])).$ext
                );
            }
            unset($type);
            unset($ext);
        }

        $this->content->META_ALTERNATE_LINKS = $alternate_meta_links;

        unset($alternate_meta_links);
        unset($application_types);
        unset($document_uri);
        unset($document_extensiontype);

        $this->write_data_to_view();

        // -- Debug log --------------------------------------------------------

        // TODO: move this to parent controller
        $this->content->debug_log = $this->RESOURCE->logUsage;

        return;

    }

    public function write_data_to_view()
    {
        // -- Basics - Name, Info, Depiction -----------------------------------

        $this->read_basic_info();

        // -- Websites/Links ---------------------------------------------------

        $this->read_links();

        // -- Online Accounts --------------------------------------------------

        $this->read_accounts();

        // -- Activity / Feeds -------------------------------------------------

        $this->read_activity_stream();

        // -- Interests --------------------------------------------------------

        $this->read_interests();

        // -- Projects ---------------------------------------------------------

        $this->read_contacts();

    }

    public function read_basic_info($resource = null)
    {

        // -- Basics - Name, Info, Depiction -----------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $this->content->name_or_nickname = $resource->getLiteral(array('name', 'nick', 'dc_title'));
        $this->content->depiction = $resource->getImage();
        $this->content->short_description = $resource->getLiteral(array('dc_description', 'bio_olb'));

    }

    public function read_links($resource = null)
    {

        // -- Websites/Links ---------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $list_of_website_objects = array_unique(array_merge($resource->homepage, $resource->weblog, $resource->workplaceHomepage, $resource->workInfoHomepage));
        $list_of_websites = array();

        foreach ($list_of_website_objects as $website_object)
        {
            if (is_object($website_object) && ($label = $website_object->getLiteral(array('rdfs_label', 'dc_title'))))
            {
                $list_of_websites[] = array(
                    'source-icon-class' => $website_object->getIconLayout($website_object->uri, true),
                    'url' => $website_object->uri,
                    'label' => $label
                );
            }
            unset($label);
            unset($website_object);
        }

        $this->content->list_of_websites = $list_of_websites;
        unset($list_of_websites);
        unset($list_of_website_objects);

    }

    public function read_accounts($resource = null)
    {

        // -- Online Accounts --------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $list_of_account_objects = array_unique(array_merge($resource->account, $resource->holdsAccount));
        $list_of_accounts = array();

        foreach ($list_of_account_objects as $account_object)
        {
            if (is_object($account_object) &&
                ($account_page = array_merge($account_object->homepage, $account_object->accountProfilePage)) &&
                is_object($account_page[0]) &&
                ($account_label = $account_page[0]->getLiteral(array('rdfs_label', 'dc_title'))))
            {
                $list_of_accounts[] = array(
                    'source-icon-class' => $account_object->getIconLayout($account_page[0]->uri, true),
                    'homepage-url' => $account_page[0]->uri,
                    'homepage-label' => $account_label
                );
            }
            unset($account_label);
            unset($account_object);
        }

        $this->content->list_of_accounts = $list_of_accounts;
        unset($list_of_accounts);
        unset($list_of_account_objects);

    }

    public function read_activity_stream($resource = null)
    {

        // -- Activity / Feeds -------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $activity = $resource->listActivity();
        if (isset($activity['stream'])) $this->content->activity = $activity;
        unset($activity);

    }

    public function read_interests($resource = null)
    {
        // -- Interests --------------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $list_of_interest_objects = $resource->interest; // TODO: merge with foaf:topic_interest
        $list_of_interests = array();

        foreach ($list_of_interest_objects as $interest_object)
        {
            if (is_object($interest_object) && $interest_label = $interest_object->getLiteral(array('dc_title', 'rdfs_label'), array(), true))
            {
                $interest_details = array();

                $interest_details['label'] = $interest_label;

                if ($description = $interest_object->getLiteral(array('dc_description', 'rdfs_comment'), array(), true))
                {
                    $interest_details['description'] = $description;
                }
                unset($description);

                if ($homepage = array_merge($interest_object->foaf_homepage, $interest_object->foaf_page, $interest_object->foaf_primaryTopic))
                {
                    if (isset($homepage[0])) $homepage = $homepage[0];
                    if (is_object($homepage))
                    {
                        $interest_details['link'] = $homepage->uri;
                    }
                    else
                    {
                        $interest_details['link'] = $homepage;
                    }
                }
                elseif (substr($interest_object->uri, 0, 1) != '_')
                {
                    $interest_details['link'] = $interest_object->uri;
                }
                unset($homepage);

                $list_of_interests[] = $interest_details;

                unset($interest_details);
            }
            elseif (!is_object($interest_object) && @parse_url($interest_object) === false)
            {
                $list_of_interests[] = array(
                    'label' => $interest_object
                );
            }
            unset($interest_object);
        }

        $this->content->list_of_interests = $list_of_interests;
        unset($list_of_interests);

    }

    public function read_contacts($resource = null)
    {

        // -- VCards -----------------------------------------------------------

        if (!$resource) $resource = $this->RESOURCE;

        $list_of_VCard_objects = array_unique(array_merge($resource->ov_businessCard, $resource->foaf_businessCard));

        $list_of_contact_objects = array(
            'Work' => array(
                'adr' => array(), 'tel' => array(), 'fax' => array(), 'email' => array()
            ),
            'Home' => array(
                'adr' => array(), 'tel' => array(), 'fax' => array(), 'email' => array()
            )
        );

        $list_of_contacts = array(
            'Work' => array(
                'Address' => array(), 'Phone' => array(), 'Fax' => array(), 'Email' => array()
            ),
            'Home' => array(
                'Address' => array(), 'Phone' => array(), 'Fax' => array(), 'Email' => array()
            )
        );

        $list_of_contacts_empty = $list_of_contacts;

        foreach ($list_of_VCard_objects as $VCard_object_id => $VCard_object)
        {
            if (!is_object($VCard_object))
            {
                unset($list_of_VCard_objects[$VCard_object_id]);
            }
            else
            {
                $list_of_contact_objects['Work']['adr'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Work']['adr'],
                                                            $VCard_object->vcard_adr('vcard:Work')));
                $list_of_contact_objects['Work']['tel'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Work']['tel'],
                                                            $VCard_object->vcard_tel('vcard:Work', '-vcard:Fax')));
                $list_of_contact_objects['Work']['fax'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Work']['fax'],
                                                            $VCard_object->vcard_tel('vcard:Work', 'vcard:Fax', true)));
                $list_of_contact_objects['Work']['email'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Work']['email'],
                                                            $VCard_object->vcard_email('vcard:Work', 'vcard:Email', true) /* not valid by current Vcard model */,
                                                            $VCard_object->vcard_workEmail));
                $list_of_contact_objects['Home']['adr'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Home']['adr'],
                                                            $VCard_object->vcard_adr('vcard:Home')));
                $list_of_contact_objects['Home']['tel'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Home']['tel'],
                                                            $VCard_object->vcard_tel('vcard:Home', '-vcard:Fax')));
                $list_of_contact_objects['Home']['fax'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Home']['fax'],
                                                            $VCard_object->vcard_tel('vcard:Home', 'vcard:Fax', true)));
                $list_of_contact_objects['Home']['email'] = array_unique(array_merge(
                                                            $list_of_contact_objects['Home']['email'],
                                                            $VCard_object->vcard_email('vcard:Home', 'vcard:Email', true) /* not valid by current Vcard model */,
                                                            $VCard_object->vcard_personalEmail));
            }

            unset($VCard_object);
        }

        $this->content->number_of_contacts = count($list_of_VCard_objects);
        unset($list_of_VCard_objects);

        $list_of_VCard_places = array('Home','Work');
        $list_of_contact_attributes = array('tel'=>'Phone', 'fax'=>'Fax', 'email'=>'Email');

        foreach ($list_of_VCard_places as $VCard_place)
        {
            foreach ($list_of_contact_objects[$VCard_place]['adr'] as $adr_object)
            {

                $adrparts = array();

                // addressparts	= PO Box, Extended Addr, Street, Locality, Region, Postal Code, Country Name
                // @see http://www.imc.org/pdi/vcard-21.txt

                if ($pobox = $adr_object->getLiteral(array('vcard_post-office-box'))) $adrparts[] = 'P.O.Box '.$pobox;
                if ($extended_address = $adr_object->getLiteral(array('vcard_extended-address'))) $adrparts[] = $extended_address;
                if ($street = $adr_object->getLiteral(array('vcard_street-address'))) $adrparts[] = $street;
                if ($locality = $adr_object->getLiteral(array('vcard_locality'))) $adrparts[] = $locality;
                if ($region = $adr_object->getLiteral(array('vcard_region'))) $adrparts[] = $region;
                if ($postalcode = $adr_object->getLiteral(array('vcard_postal-code'))) $adrparts[] = $postalcode;
                if ($country = $adr_object->getLiteral(array('vcard_country-name'))) $adrparts[] = $country;

                if (count($adrparts) > 0)
                {
                    $list_of_contacts[$VCard_place]['Address'][] = implode(', ', $adrparts);
                }

                unset($adr_object);
            }

            foreach($list_of_contact_attributes as $contact_attribute => $contact_attribute_label)
            {
                foreach ($list_of_contact_objects[$VCard_place][$contact_attribute] as $$contact_attribute)
                {
                    if (is_object($$contact_attribute))
                    {
                        $list_of_contacts[$VCard_place][$contact_attribute_label][] = array(
                            'link' => $$contact_attribute->uri,
                            'label' =>($label_temp = $$contact_attribute->getLiteral(array('rdf_value', 'rdfs_label')))?$label_temp:$$contact_attribute->uri
                        );
                        unset($label_temp);
                    }
                    unset($$contact_attribute);
                }

                unset($contact_attribute);
                unset($contact_attribute_label);
            }

            unset($VCard_place);

        }

        unset($list_of_VCard_places);
        unset($list_of_contact_attributes);
        unset($list_of_contact_objects);

        if ($list_of_contacts_empty != $list_of_contacts)
        {
            $this->content->list_of_contacts = $list_of_contacts;
        }

        unset($list_of_contacts);

    }

}
