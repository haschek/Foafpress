<?php

// extract all content vars
// extract($this->content->getArray(), EXTR_SKIP);

?>

    <div id="header">
        <div class="page_margins">
            <?php
            echo '<h1>'.($this->content->name_or_nickname?$this->content->name_or_nickname:'No Name/Nickname found').'</h1>'.PHP_EOL;
            echo $this->content->depiction?'<div class="depiction">'.$this->content->depiction.'</div>'.PHP_EOL:null;
            echo $this->content->short_description?'<p class="tagline"><strong>'.$this->content->short_description.'</strong></p>'.PHP_EOL:null;
            if ($this->content->list_of_websites) $this->templatePartial('foaf/_websites.html', array('list_of_websites'=>$this->content->list_of_websites));
            if ($this->content->list_of_accounts) $this->templatePartial('foaf/_accounts.html', array('list_of_accounts'=>$this->content->list_of_accounts));
            ?>
        </div>
    </div> <!-- /#header -->
    <div id="main">
        <div class="page_margins page">
            <div class="subcolumns">
                <div class="c40l">
                    <div class="subcl">
                        <?php
                        if (isset($this->content->activity)) $this->templatePartial('foaf/_feeds.html', array('activity'=>$this->content->activity));
                        unset($this->content->activity);
                        ?>
                    </div>
                </div>
                <div class="c60r">
                    <div class="subcr sidecontent">
                        <div class="subcolumns">
                            <?php
                            
                            $personal_rendered = false;
                            
                            if (count($this->content->list_of_interests) > 0 || count($this->content->list_of_projects) > 0 || count($this->content->list_of_skills) > 0)
                            {
                                if (isset($this->content->list_of_contacts)) echo '<div class="c50l"><div class="subcl">'.PHP_EOL;
                                $this->templatePartial('foaf/_personalstuff.html', array('interests'=>$this->content->list_of_interests,
                                                                                         'projects'=>$this->content->list_of_projects,
                                                                                         'skills'=>$this->content->list_of_skills));
                                if (isset($this->content->list_of_contacts)) echo '</div></div>'.PHP_EOL;
                                
                                $personal_rendered = true;
                            }

                            if (isset($this->content->list_of_contacts))
                            {
                                if ($personal_rendered) echo '<div class="c50r"><div class="subcr">'.PHP_EOL;
                                $this->templatePartial('vcard/VCard.html', array('contacts'=>$this->content->list_of_contacts));
                                if ($personal_rendered) echo '</div></div>'.PHP_EOL;
                            }
                            
                            unset($this->content->list_of_skills);
                            unset($this->content->list_of_contacts);
                            ?>
                        </div>
                        <?php if ($this->content->list_of_known_persons) $this->templatePartial('foaf/_network.html', array('persons'=>$this->content->list_of_known_persons)); ?>
                        <?php unset($this->content->list_of_known_persons); ?>
                        <div class="subcolumns">
                            <div class="c50l">
                                <div class="subcl">
                                    <?php if ($this->content->list_of_interests) $this->templatePartial('foaf/_interests.html', array('interests'=>$this->content->list_of_interests)); ?>
                                    <?php unset($this->content->list_of_interests); ?>
                                </div>
                            </div>
                            <div class="c50r">
                                <div class="subcr">
                                    <?php if ($this->content->list_of_projects) $this->templatePartial('foaf/_projects.html', array('projects'=>$this->content->list_of_projects)); ?>
                                    <?php unset($this->content->list_of_projects); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                </div>
            </div>
        </div>
    </div> <!-- /#main -->

