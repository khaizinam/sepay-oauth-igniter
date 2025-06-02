
After the Installation

Once the installation is complete, it is strongly recommended that you take the following steps to protect the current installation and the CS-Cart source code.

1.	Remove the directory install/.
2.	Change the default administrator password.
3.	Remove the distribution package from the web accessible directory on your server.
4.	Change the access permissions for the files as advised below.
chmod 644 config.local.php

chmod 644 design/.htaccess images/.htaccess

chmod 664 var/.htaccess var/themes_repository/.htaccess

chmod 644 design/index.php images/index.php

chmod 664 var/index.php var/themes_repository/index.php