<h1>Contact Us</h1>

<?php

if(isset($_POST['email'])) {

	// Cambiando queste due linee qui sotto si configura il modulo di invio
	$email_to = "alessandro@pellegrini.tk";
	$email_subject = "mdsite: Nuovo Messaggio";
 
	function died($error) {
		// your error code can go here
		echo "We're sorry, but we're unable to process your message. ";
		echo "Some errors occurred. We report them below.<br /><br />";
		echo $error."<br /><br />";
		echo '<a href="javascript:history.back()">Go back</a> and try again.';
		die();
	}
 
	 
	// validation expected data exists
	if(!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['message']) || !isset($_POST['captcha'])) {
		died('All fields must be filled!');	   
	 }
 

	$name = $_POST['name']; // required
	$email_from = $_POST['email']; // required
	$message = $_POST['message']; // required
 
	$error_message = "";
 
	// A bunch of sanity checks...
	$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
	if(!preg_match($email_exp, $email_from)) {
		$error_message .= "It looks like your email address is not valid.<br />";
	}
 
	$string_exp = "/^[A-Za-z .'-]+$/";
	if(!preg_match($string_exp, $name)) {
 		$error_message .= 'The name ' . $name . ' doesn\'t seem to be valid.<br />';
 	}
 
	if(strlen($message) < 10) {
 		$error_message .= 'Your message is too short.<br />';
 	}

	if($_POST['captcha'] != $_POST['captcha_solve']) {
 		$error_message .= 'Wrong captcha code: ' . $_POST['captcha'] . '.<br />';
 	}
 
	// If we have encountered any error, show it and die...
	if(strlen($error_message) > 0) {
 		died($error_message);
 	}
 

	$email_message = "A new email has been sent via the mdsite demo page.\n\n";
 
	// Avoid spammers to use this form...
	function clean_string($string) {
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}

 	$email_message .= "Name: ".clean_string($name)."\n";
 	$email_message .= "Email: ".clean_string($email_from)."\n";
 	$email_message .= "Message: ".clean_string($message)."\n";
 
	 // create email headers
	$headers = 'From: '.$email_from."\n" .
		   'Reply-To: '.$email_from."\n" .
 		   'X-Mailer: PHP/' . phpversion();

 	// Send the email
	@mail($email_to, $email_subject, $email_message, $headers);  
 
?>
 
<p>Message correctly received. We'll get back to you as soon as possible!</p>
 
<?php
 
} else { // $_POST['email'] is not set: show form

// Generate super simple captcha code
$a=rand(1,9);
$b=rand(2,10);
$c=$a+$b;

?>

<p>The source of this file is written in PHP. In this way, adding a contact-us form is as easy as reusing code already available around! (You can even just use this template: is quite simple and has some basic security checks!)</p>

<form name="contactform" method="post" action="<?php echo $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>">
<table width="80%">
<tr>
	<td valign="top">
 		<label for="name">Name *</label>
 	</td>
 	<td valign="top">
	 	<input  type="text" name="name" maxlength="50" size="30">
 	</td>
</tr>
<tr>
	<td valign="top">
		<label for="email">Email Address *</label>
	</td>
	<td valign="top">
		<input  type="text" name="email" maxlength="80" size="30">
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="message">Message *</label>
	</td>
	<td valign="top">
		<textarea  name="message" maxlength="1000" cols="25" rows="6"></textarea>
	</td>
</tr>
<tr>
	<td valign="top">
		<label for="captcha">Are you human? *</label>
	</td>
	<td valign="top">
		<?php echo $a; ?> + <?php echo $b; ?> = 
		<input type="text" name="captcha" maxlength="20" size="20" />
		<input type="hidden" name="captcha_solve" value="<?php echo $c; ?>" />
	</td>
</tr>
<tr>
	<td colspan="2" style="text-align:center">
		<input type="submit" value="Submit">
	</td>
</tr>
</table>
</form>

<?php

}
 
?>
