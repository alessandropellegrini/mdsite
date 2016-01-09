mdsite: an on-the-fly Markdown website generator
================================================

How this is done
----------------

This is generated on the fly starting from some markdown files. But's that not everything! You can:

* Use the full MD syntaxt
* you can use as well directly php files

In practice, you can organize a tree of markdown sites, and this gets converted on the fly into a perfectly working webpage.  
If you need some dinamicity, some pages can be written directly in php.

There is no need to recompile your site: just edit markdown files, upload, everything is rendered immediately!

Markdown parsing is based on the excellent [Parsedown](https://github.com/erusev/parsedown) class.

---------------------------------------

Perfect decoupling
------------------

The main goal behind mdsite is to keep everything as simple as possible:

* You concentrate on the content when you write markdown files.
* You concentrate on the design when you setup your layout.

You can use directly HTML & CSS code when you design your site. Try the [demo](http://www.pellegrini.tk/mdsite): it's **responsive**!

The API to integrate mdsite with your template is extremely simple: just 3 functions!

---------------------------------------

How to use
--------------------------------------

File extensions matter when you organize your tree:

* .php files are included in the template, so you can generate dynamic pages as old-school PHP pages
* .md files are converted on the fly to static HTML
* All other files are simply ignored, so you can use for example .php5 file extensions to create utilities or the main template.
* Any file starting with a '\_' is ignored in the menu. This allows to create pages which can be linked, but which do not show in the menu tree.

There is a working demo [here](http://www.pellegrini.tk/mdsite). It's actually just a clone of the github repository, so you can start experimenting with mdsite in minutes!

The ideal workflow is:

* Create index.php5, which will keep the HTML template
* Include at the beginning `mdsite.php5`: `<?php include('mdsite.php5'); ?>`
* Call `page_name()` whenever you want to print the name of the page being currently visited (e.g., in the `<title>` tag)
* Call `menu()` when you want to print the menu. The menu is organized as an unordered list, so you can use CSS to display it in any fancy way you like
* Call `content()` in the place where you want to display the content.

There is the configuration file `config.php5` where few variables should be set, like the server path where the main folder of the site is found. It additionally allows you to manually specify some files which should not appear in the menu.

If you find mdsite useful, buy us a beer!

<p>
<center>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="SGEL9Z2J25BTQ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>
</center>
</p>


Let us know
-----------

If you use mdsite for your project, let us know! We'll try to keep a list on the [project's page](http://www.pellegrini.tk/mdsite).
