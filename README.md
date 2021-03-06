HTML5Wiki
=========

Introduction
------------
HTML5Wiki is an intuitive wiki-system. Maintaining articles in different versions is a breeze using the simple user interface.

This application was built as part of a software engineering project at the HSR in Switzerland. The complete source code is open and interested developers can contribute their additions.


Requirements
------------
For running HTML5Wiki in your environment, it has to fullfil the following requriements:

- Apache with mod_rewrite available (or a comparable HTTP server)
- PHP 5.2 or better
- MySQL 5 or better

Installation
------------
1. Copy all relevant files delivered from the distribution ZIP archive to your target web server.
2. Once all files are in place, run the installation wizard: Open http://yourserver/yourfolder/web/install.php in your browser (make sure you replace ''yoursever'' and ''yourfolder'' with your own values)
3. Follow the wizards instructions which will guide you through the database and basic configuration.
4. After finishing the wizard, HTML5Wiki should be up and running.

License
-------
For HTML5Wikis own license and any information about used third-party libraries, please refer to LICENSE for more information.


Contributors
------------
The HTML5Wiki core team:

- Alexandre Joly
- Manuel Alabor
- Michael Weibel
- Nicolas Karrer
- Roman Brechbühl

If you are interested into enhance and develop HTML5Wiki, please know you are welcome! Have a look at our git repository at https://github.com/HTML5Wiki/HTML5Wiki.

Tests
-----
To start the tests, first copy test/TestConfiguration.php.sample to test/TestConfiguration.php and modify the configuration according to your needs.
If you want to run the functional tests, configure in your hosts-file the "selenium.local"-host to point to your selenium server.

After that, your ready to run:

    $ phpunit -c test/testsuite.xml
