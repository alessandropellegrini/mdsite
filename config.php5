<?php

/* Configuration Stuff */

$main_folder = '/var/www/html/mdsite'; // You need to include server's absolute path here. NO TRAILING SLASH AT THE END, otherwise the menu generation breaks
$allowed_files = array("php", "md"); // Currently only php and md files are supported
$ignored_files = array("index.md", "index.php", "css", "fonts", "img", "js", "README.md"); // List all files/folders which should not appear in the main menu
$return_link = $_SERVER['PHP_SELF'] . "?p=[link]"; // use [link] as a placeholder for the file name. If this is included in a template, it should work without any modification
$default_content = "README"; // This is the content to be shown in the homepage of the site. No .md nor .php extension here! Should be placed in $main_folder.
$error_404_content = "_error_404.md"; // Content to be shown if requested content is not found. Should be placed in $main_folder.


/* Menu Icons (for font awesome) */

$default_menu_icon = "fa-fw fa fa-circle ";
$menu_icons = array(
	"Subfolder" => "fa-fw fa fa-folder-open",
	"Some content" => "fa-fw fa fa-file",
	"Contact us" => "fa-fw fa fa fa-envelope ",
	"Credits" => "fa-fw fa fa-users",
);

?>
