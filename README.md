# Script and Codes

This plugin adds a section to the Wordpress Customizer allowing you to add scripts and codes to the header, footer and after the opening `<body>` tag.

By default the body hook is `wp_body_open` but not all themes have that hook in their header.php since it is a new hook. If you do not see that hook available in your theme's `header.php` file, and you see another hook being fired, you can set that hook in the Customizer settings for the body hook.  

To add a hook after the opening `<body>` tag in your template, use:

```php
<?php do_action( 'wp_body_open' ); ?>
```
