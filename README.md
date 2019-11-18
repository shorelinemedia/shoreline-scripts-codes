# Script and Codes

This plugin adds a section to the Wordpress Customizer allowing you to add scripts and codes to the header, footer and after the opening `<body>` tag.

By default the body hook is `wp_body_open` but not all themes have that hook in their header.php since it is a new hook. If you do not see that hook available in your theme's `header.php` file, and you see another hook being fired, you can set that hook in the Customizer settings for the body hook.  

To add a hook after the opening `<body>` tag in your template, use:

````php
<?php do_action( 'wp_body_open' ); ?>
````

### Installation

Install the [Github Updater](https://github.com/afragen/github-updater/archive/master.zip) plugin by uploading the ZIP file and activating on your wordpress install.

Install the plugin through the Github Updater's Install Plugin section by using the full github URL of `https://github.com/shorelinemedia/shoreline-scripts-codes` and then activate the plugin under Plugins section
