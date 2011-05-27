default:
	@echo "make targets:"
	@echo " - libraries:        clone all needed libraries"
	@echo " - cache             chmod cachdir"
	@echo " - arc2|rdfto|spcms: clone a specific libraries"
	@echo " - enable-modules:   enable apache modules (sudo needed)"

install: cache libraries

cache:
	chmod 777 cache

libraries: arc2 rdfto spcms

enable-modules:
	a2enmod negotiation
	a2enmod dir
	a2enmod rewrite

arc2:
	rm -rf foafpressapp/libraries/arc2
	git status 2>/dev/null >/dev/null || hg clone http://bitbucket.org/haschek/arc2 foafpressapp/libraries/arc2
	hg status 2>/dev/null >/dev/null || git clone https://github.com/haschek/arc2.git foafpressapp/libraries/arc2

rdfto:
	rm -rf foafpressapp/libraries/rdfto
	git status 2>/dev/null >/dev/null || hg clone http://bitbucket.org/haschek/rdf-template-object foafpressapp/libraries/rdfto
	hg status 2>/dev/null >/dev/null || git clone https://github.com/haschek/RDF-Template-Object.git foafpressapp/libraries/rdfto

spcms:
	rm -rf foafpressapp/libraries/spcms
	git status 2>/dev/null >/dev/null || hg clone https://sandbox-publisher-cms.googlecode.com/hg/ foafpressapp/libraries/spcms
	hg status 2>/dev/null >/dev/null || git clone https://github.com/haschek/Sandbox-Publisher-CMS.git foafpressapp/libraries/spcms
