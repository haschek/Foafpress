About Foafpress
==========================================================================

[Foafpress][1] is planned as a small presentation engine for your FOAF
profile and other RDF data stored in files. It allows you to aggregate
and publish data from multiple web sources via Linked Data. Currently
a proof of concept implementation is under testing, for now Foafpress
works with files in RDF/XML, Turtle & N-Triple. I want to add some more
standard templates for basic vocabularies like FOAF, DOAP and SIOC.

Please read upcoming announcements on my [Foafpress feed at Identi.ca][2].

[1]: http://foafpress.org/
[2]: http://identi.ca/haschek/tag/foafpress


How it works
--------------------------------------------------------------------------

All requests on RDF files (resources) under the Foafpress root (what
is where the Foafpress .htaccess is located in) will be routed through
Foafpress to deliver exactly what is requested. RDF stuff to RDF
clients, a nice HTML representation to standard web browsers.


Install Foafpress
--------------------------------------------------------------------------

If installation does not work please report bugs, errors and your
suggestions how to improve Foafpress as well as this document, thank
you!

**Requirements:** Apache web server with `mod_negotiation`, `mod_dir` and
`mod_rewrite`, PHP 5.3 or newer. You can enable the required modules by
starting:

    sudo make enable-modules

in your foafpress root directory (after cloning, see next step)

**Note:** All code examples are valid for a standard installation of
Ubuntu Linux. If you use another Operation system or another Linux
distribution please adapt it.

### 1. Copy Foafpress to your web server

Currently we do not offer archive downloads, Pear channels, Phars or
Debian packages. Please clone it via Git:

    $ cd /var/www/
    $ git clone https://github.com/haschek/Foafpress.git foafpress

or Mercurial:

    $ cd /var/www/
    $ hg clone http://bitbucket.org/haschek/foafpress foafpress

### 2. Prepare cache folder, Install libraries

#### Using the makefile

    $ cd foafpress/
    $ make install

That will prepare the cache folder and installing all necessary
libraries. You can go to 'Configure Apache' now. The makefile provides
also some other options, e.g. installing/updating only a specific library.
Call `make` in your Foafpress directory to see more.

#### Manually

Foafpress needs read/write access to the cache folder. You could grant
read/write access to all (anonymous) users but it is recommended to
grant this rights only to your user and the user group of your www user.

    $ cd foafpress/
    $ sudo chown youruser:www-data cache/
    $ sudo chmod 6770 cache/

You need to install some libraries, Foafpress needs [SPCMS][3],
[Arc2][4] and the [RDF Template Object][5] for PHP. Using Git:

    $ cd foafpressapp/libraries
    $ git clone https://github.com/haschek/Sandbox-Publisher-CMS.git spcms
    $ git clone https://github.com/haschek/arc2.git arc2
    $ git clone https://github.com/haschek/RDF-Template-Object.git rdfto

or Mercurial:

    $ cd foafpressapp/libraries
    $ hg clone https://sandbox-publisher-cms.googlecode.com/hg/ spcms
    $ hg clone http://bitbucket.org/haschek/arc2 arc2
    $ hg clone http://bitbucket.org/haschek/rdf-template-object rdfto

[3]: http://eye48.com/go/spcms
[4]: http://github.com/semsol/arc2/wiki
[5]: http://github.com/haschek/RDF-Template-Object

### 3. Configure Apache

Second, you need to configure Apache that all resource requests of RDF
files are answered via Foafpress: use the template for the `.htaccess`
file and please follow the instruction there, basically you need to
replace all occurrences of `/path/to/` with the base URI of your
Foafpress installation. If Foafpress is located in `/var/www/foafpress/`
the replacement would be only `/foafpress/`.

    $ cp .htaccess-example .htaccess
    $ gedit .htaccess

### 4. Configure Foafpress

Foafpress itself comes with several options to configure, you can use
the example template for the user config file, uncomment and edit the
line you want to change. The template is documented extensive.

    $ cp fp-config.php-example fp-config.php
    $ gedit fp-config.php

If you only want to test Foafpress, or do not need any customizations on
templates/layout you can ignore options for templates and plugins. It is
recommended to configure caching and the language stack. If you decide
to use Foafpress on your public server please configure the production
mode.

### 5. Run Foafpress

Now Foafpress should work. Please check out one of the test resources
which are located in the `test` sub folder.

For instance, if you have installed Foafpress on your
local machine under `/var/www/foafpress/` then run
`http://localhost/foafpress/test/desirejeanette` in your web browser. If
Foafpress is working properly you should see something like this:

[Foafpress screenshot: rendering a foaf:Person example (v 0.0)][6]

In case Foafpress does not work please check also the [trouble shooting
page][7].

Now you could copy your own foaf:Person profile to the foafpress folder
and do further testings. Would be nice to hear some feedback, thank you!

[6]: http://www.flickr.com/photos/haschek/5596988319/
[7]: http://bitbucket.org/haschek/foafpress/wiki/TroubleShooting
