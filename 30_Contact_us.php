<h1>Contact Us</h1>

<?php
 
if(isset($_POST['email'])) {

	// Cambiando queste due linee qui sotto si configura il modulo di invio
	$email_to = "alessandro@pellegrini.tk";
	$email_subject = "ROMA 132: Nuovo Messaggio";
 
	function died($error) {
		// your error code can go here
		echo "Ci dispiace, ma non siamo in grado di processare il messaggio. ";
		echo "Si sono verificati alcuni errori, che riportiamo qui di seguito.<br /><br />";
		echo $error."<br /><br />";
		die();
	}
 
	 
	// validation expected data exists
	if(!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['message']) || !isset($_POST['captcha'])) {
		died('&Eacute; necessario compilare tutti i campi!');	   
	 }
 

	$name = $_POST['name']; // required
	$email_from = $_POST['email']; // required
	$message = $_POST['message']; // required
 
	$error_message = "";
 
	// A bunch of sanity checks...
	$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
	if(!preg_match($email_exp, $email_from)) {
		$error_message .= "L'indirizzo email inserito non sembra essere valido.<br />";
	}
 
	$string_exp = "/^[A-Za-z .'-]+$/";
	if(!preg_match($string_exp, $name)) {
 		$error_message .= 'Il nome inserito ' . $name . ' non sembra essere valido.<br />';
 	}
 
	if(strlen($message) < 10) {
 		$error_message .= 'Il messaggio inserito non sembra essere valido.<br />';
 	}

	if($_POST['captcha'] != $_POST['captcha_solve']) {
 		$error_message .= 'Codice di controllo ' . $_POST['captcha'] . ' errato.<br />';
 	}
 
	// If we have encountered any error, show it and die...
	if(strlen($error_message) > 0) {
 		died($error_message);
 	}
 

	$email_message = "Hai ricevuto una nuova comunicazione dal sito del Roma 132.\n\n";
 
	// Avoid spammers to use this form...
	function clean_string($string) {
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}

 	$email_message .= "Nome: ".clean_string($name)."\n";
 	$email_message .= "Email: ".clean_string($email_from)."\n";
 	$email_message .= "Messaggio: ".clean_string($message)."\n";
 
	 // create email headers
	$headers = 'From: '.$email_from."\n".
 			'Reply-To: '.$email_from."\n" .
 			'X-Mailer: PHP/' . phpversion();

 	// Send the email
	@mail($email_to, $email_subject, $email_message, $headers);  
 
?>
 
<p>Messaggio inviato con successo. Vi risponderemo quanto prima!</p>
 
<?php
 
} else { // $_POST['email'] is not set: show form

// Generate super simple captcha code
$a=rand(1,9);
$b=rand(2,10);
$c=$a+$b;

?>

<p>The source of this file is written in PHP. In this way, adding a contact-us form is as easy as reusing code already available around!</p>

<form name="contactform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
		<label for="captcha">Sei umano? *</label>
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
