<?php include('mdsite.php5') ?>
<!DOCTYPE html>
<html>
    <head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
	<meta name="author" content="Author" />
	<title>MDSITE | <?= page_name(); ?></title>
	<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/font-awesome.min.css" />
	<link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
	<link rel="icon" href="favicon.ico" type="image/x-icon"/>
    </head>
    <body >
          <main>
            <header>
              <h1>
		<img src="img/logo.png" alt="Logo" />
		<a href="index.php5">mdsite</a>
	      </h1>
            </header>
		
		<nav>
<?php menu(); ?>
		</nav>

            <div id="central">
		<div id="content">
<?php content(); ?>
		</div>
            </div>
          </main>

          <footer>
          &copy; 2014 | Built using <a href="https://github.com/alessandropellegrini/mdsite" target="_blank">mdsite</a>
          </footer>
    </body>
</html>

