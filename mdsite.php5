<?php
/************
MDsite - A PHP-based minimalistic website engine to display webpage content based on the markdown syntax, with automatic menu generation from the file tree.

Copyright (C) 2015 - Alessandro Pellegrini <alessandro@pellegrini.tk>

In brief:
- files starting with "_" are ignored
- Any file named like "10_Name_of_file" is rendered as "Name of file", so numbers at the beginning can be used for ordering the menu
- Explicit files can be excluded (on a global basis, no control on files in single folders, except for the '_' trick) using the $ignored_files array below

Note that (for simplicity) either "index.php" and/or "index.md" should be in the in the ignored files array, as this is the file which is processed by default
when pointing to a directory in the tree.


****************** DO NOT EDIT BELOW THIS LINE *********************/

require_once("config.php5");
require_once("Parsedown.php5");

mb_http_output("UTF-8");

function get_menu_level($page) {
	global $default_content, $main_folder;

	$page = str_replace($main_folder, "", $page);

	return substr_count($page, '/');
}

function get_actual_content($page) {
	global $main_folder, $error_404_content;

	if(file_exists($page . ".md")) {
		return $page . ".md";
	} else if(file_exists($page . ".php")) {
		return $page . ".php";
	} else if(is_dir($page)) {
		return get_actual_content($page . "/index");
	}

	return $main_folder . '/' . $error_404_content;
}


function apply_menu_icon($entry) {
	global $menu_icons, $default_menu_icon;

	$icon = isset($menu_icons[$entry]) ? $menu_icons[$entry] : $default_menu_icon;

	return "<i  class=\"$icon\"></i> " . $entry;

}


function sanitize_entry($entry) {
	// Remove extension
	$entry = pathinfo($entry, PATHINFO_FILENAME);

	// Remove initial number for ordering entries
	if(is_numeric(substr($entry, 0, 1))) {
		$entry = substr($entry, strpos($entry, "_") + 1);
	}

	// Replace _ with spaces
	$entry = str_replace("_", " ", $entry);

	return $entry;
}


function should_hide($directory, $page, $call_level) {
	global $default_content, $main_folder;

	$directory = str_replace($main_folder, "", $directory	);
	$ret = false;

	if($call_level == 0)
		return false;

	if($call_level > get_menu_level($page) + 1)
		$ret = true;

	$path_parts = explode('/', $directory);
	foreach($path_parts as $part) {
		if($part != "")
			if(!strstr($page, $part))
				$ret = true;
	}
	return $ret;
}


function php_file_tree($directory, $return_link, $whole, $extensions = array(), $ignored_files = array()) {
	// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
	// Remove trailing slash
	if( substr($directory, -1) == "/" )
		$directory = substr($directory, 0, strlen($directory) - 1);
	$code = php_file_tree_dir($directory, $return_link, $whole, $extensions, $ignored_files);
	return $code;
}


function php_file_tree_dir($directory, $return_link, $whole, $extensions = array(), $ignored_files = array(), $call_level = 0) {
	global $main_folder, $default_content;

	$php_file_tree = '';

	$page = (isset($_GET['p']) ? $_GET['p'] : $default_content);

	// Show only entries related to current path (one level more)
	if( $whole == false && should_hide($directory, $page, $call_level) )
		return "";

	// Get and sort directories/files
	if( function_exists("scandir") )
		$file = scandir($directory);
	else
		$file = php4_scandir($directory);
	natcasesort($file);

	// Make directories first
	$files = $dirs = array();
	foreach($file as $this_file) {
		if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file;
		else $files[] = $this_file;
	}
	$file = array_merge($dirs, $files);

	// Purge unwanted / unneeded elements from the scanned array
	foreach( array_keys($file) as $key ) {

		// Filter unwanted extensions
		if( !empty($extensions) ) {
			if( !is_dir("$directory/$file[$key]") ) {
				$ext = substr($file[$key], strrpos($file[$key], ".") + 1); 
				if( !in_array($ext, $extensions) ) {
					unset($file[$key]);
					continue;
				}
			}
		}

		// Filter unwanted files (array)
		if( !empty($ignored_files) ) {
			if(in_array($file[$key], $ignored_files)) {
				unset($file[$key]);
				continue;
			}
		}

		// Filter unwanted files (filename)
		if(substr($file[$key], 0, 1) == "_") {
			unset($file[$key]);
			continue;
		}		
	}

	if(count($file) > 0) {
		$php_file_tree = "<ul>";
		foreach( $file as $this_file ) {

			if( $this_file[0] != ".") {
				$link = str_replace("[link]", "$directory/" . urlencode( pathinfo($this_file, PATHINFO_FILENAME) ), $return_link);
				$link = str_replace($main_folder, "", $link);
				$menu_name = apply_menu_icon(htmlspecialchars(sanitize_entry($this_file)));

				if( is_dir("$directory/$this_file") ) {
					// Directory
					$php_file_tree .= "<li><a href=\"$link\">" . $menu_name . "</a>";
					$php_file_tree .= php_file_tree_dir("$directory/$this_file", $return_link, $whole, $extensions, $ignored_files, $call_level+1);
					$php_file_tree .= "</li>";
				} else {
					// File
					$php_file_tree .= "<li><a href=\"$link\">" . $menu_name . "</a></li>";
				}
			}
		}
		$php_file_tree .= "</ul>";
	}
	return $php_file_tree;
}


// For PHP4 compatibility
function php4_scandir($dir) {
	$dh  = opendir($dir);
	while( false !== ($filename = readdir($dh)) ) {
	    $files[] = $filename;
	}
	sort($files);
	return($files);
}

function menu($whole = false) {
	global $main_folder, $return_link, $allowed_files, $ignored_files;
	echo php_file_tree($main_folder, $return_link, $whole, $allowed_files, $ignored_files);
}

function content() {
	global $error_404_content, $main_folder, $default_content;
	$page = (isset($_GET['p']) ? $_GET['p'] : $default_content);

	$page = get_actual_content($main_folder.'/'.$page);
	$ext = substr($page, strrpos($page, '.', -1), strlen($page)); 

	if($ext == ".php") {
		include($page);
	} else if($ext == ".md") {
		echo Parsedown::instance()->text(file_get_contents(htmlspecialchars($page)));
	}
}

function page_name() {
	global $default_content;
	mb_http_output("UTF-8");
	$page = (isset($_GET['p']) ? $_GET['p'] : $default_content);
	echo sanitize_entry(end(explode('/', $page)));
}
?>
