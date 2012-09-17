PHPCR and Magnolia examples
===========================

Some simple examples using PHPCR to read/write from/to directly into Magnolia's Jackrabbit repositories.

You will need:

  * Git 1.6+
  * PHP 5.3.3+
  * composer (see below)
  * Magnolia CE (see below)

Getting PHPCR
-------------

    curl -s http://getcomposer.org/installer | php --
    php composer.phar install

Setting up Magnolia with Davex support
--------------------------------------

  * Download [Magnolia CE](http://sourceforge.net/projects/magnolia/files/)
  * Download the [Magnolia Jackrabbit-Davex Module](http://ci.magnolia-cms.com/job/forge_magnolia-module-jackrabbit-davex/info.magnolia.davex%24magnolia-module-jackrabbit-davex/)
  * Drop all JARs from this archive into ``apache-tomcat-6.0.32/webapps/magnoliaAuthor/WEB-INF/lib/``
  * Remove open file limitations; f.e. in bash, this will be ``ulimit -n 5000``
  * Start Magnolia with ``./magnolia_control.sh start && tail -f ../logs/catalina.out``
  * Check that the installation is complete by logging into the admin and frontend downloading any relevant modules
  * Ensure the following is set in the configuration under ``server->IPConfig->allow-all->methods`` the following ``GET,POST,PROPFIND,PUT,DELETE,REPORT,HEAD``
  * See also the [official Magnolia documentation](http://documentation.magnolia-cms.com)