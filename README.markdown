# About Foafpress

[Foafpress][1] is planned as a small presentation engine for your FOAF
profile and other RDF data stored in files. It allows you to aggregate
and publish data from multiple web sources via Linked Data. Currently
a proof of concept implementation is under testing, for now Foafpress
works with files in RDF/XML, Turtle & N-Triple. I want to add some more
standard templates for basic vocabularies like FOAF, DOAP and SIOC.

Please read upcoming announcements on my [Foafpress feed at Identi.ca][2].

[1]: http://foafpress.org/
[2]: http://identi.ca/haschek/tag/foafpress


## How it works

All requests on RDF files (resources) under the Foafpress root (what
is where the Foafpress .htaccess is located in) will be routed through
Foafpress to deliver exactly what is requested. RDF stuff to RDF
clients, a nice HTML representation to standard web browsers.


## Install Foafpress

If installation does not work please report bugs, errors and your
suggestions how to improve Foafpress as well as this document, thank
you!

**Requirements:** Apache web server with `mod_negotiaten`, `mod_dir` and
`mod_rewrite`, PHP 5.3 or newer. You can enable the required modules by
starting:
    sudo make enable-modules
in your foafpress root directory (after cloning, see next step)

**Note:** All code examples are valid for a standard installation of
Ubuntu Linux. If you use another Operation system or another Linux
distribution please adapt it.

### 1. Copy Foafpress to your web server

Currently we do not offer archive downloads, Pear channels, Phars or
Debian packages. Please clone it via Mercurial or git:

    $ cd /var/www/
    $ git clone https://github.com/haschek/Foafpress.git foafpress
or
    $ cd /var/www/
    $ hg clone http://bitbucket.org/haschek/foafpress foafpress

### 2. Configure the server

Foafpress needs read/write access to the cache folder. You could grant
read/write access to all (anonymous) users but it is recommended to
grant this rights only to your user and the user group of your www user.

    $ cd foafpress/
    $ sudo chown youruser:www-data cache/
    $ sudo chmod 6770 cache/

Second, you need to configure Apache that all resource requests of RDF
files are answered via Foafpress: use the template for the `.htaccess`
file and please follow the instruction there, basically you need to
replace all occurrences of `/path/to/` with the base URI of your
Foafpress installation. If Foafpress is located in `/var/www/foafpress/`
the replacement would be only `/foafpress/`.

    $ cp .htaccess-example .htaccess
    $ gedit .htaccess

### 3. Configure Foafpress

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

### 4. Run Foafpress

Now Foafpress should work. Please check out one of the test resources
which are located in the `test` folder. E.g. if you have installed
Foafpress on your local machine under `/var/www/foafpress/` then run
`http://localhost/foafpress/test/desirejeanette` in your web browser. If
Foafpress is working properly you should see something like this:

[Foafpress screenshot: rendering a foaf:Person example (v 0.0)][3]

In case Foafpress does not work please check also the [trouble shooting
page][4].

Now you could copy your own foaf:Person profile to the foafpress folder
and do further testings. Would be nice to hear some feedback, thank you!

[3]: http://www.flickr.com/photos/haschek/5596988319/
[4]: http://bitbucket.org/haschek/foafpress/wiki/TroubleShooting
