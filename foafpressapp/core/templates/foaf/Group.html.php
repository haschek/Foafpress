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
                            
                            if (isset($this->content->list_of_contacts))
                            {
                                $this->templatePartial('vcard/VCard.html', array('contacts'=>$this->content->list_of_contacts));
                                unset($this->content->list_of_contacts);
                            }
                            
                            ?>
                        </div>
                        <?php
                            if ($this->content->list_of_members)
                            {
                                // TODO
                                // $this->templatePartial('foaf/_network.html', array('persons'=>$this->content->list_of_known_persons));
                                // unset($this->content->list_of_members);
                            }
                        ?>
                        <div class="subcolumns">
                            <?php
                                if ($this->content->list_of_interests)
                                {
                                    $this->templatePartial('foaf/_interests.html', array('interests'=>$this->content->list_of_interests));
                                    unset($this->content->list_of_interests);
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="content">
                </div>
            </div>
        </div>
    </div> <!-- /#main -->

