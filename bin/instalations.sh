#! bash bin
chmod 666 config.local.php

chmod -R 777 design images var

find design -type f -print0 | xargs -0 chmod 666

find images -type f -print0 | xargs -0 chmod 666

find var -type f -print0 | xargs -0 chmod 666

chmod 644 config.local.php

chmod 644 design/.htaccess images/.htaccess

chmod 664 var/.htaccess var/themes_repository/.htaccess

chmod 644 design/index.php images/index.php

chmod 664 var/index.php var/themes_repository/index.php