<?php

require_once "EmailApp.php";



if (isset($_POST['send'])) {
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	$attachment = [];

	// Count # of uploaded files in array
	$total = count($_FILES['upload']['name']);

	// Loop through each file
	for( $i=0 ; $i < $total ; $i++ ) {
		//Get the temp file path
		$tmpFilePath = $_FILES['upload']['tmp_name'][$i];

		//Make sure we have a file path
		if ($tmpFilePath != ""){
			//Setup our new file path
			$newFilePath =  $_FILES['upload']['name'][$i];
			$attachment[] = $_FILES['upload']['name'][$i];
			//Upload the file into the temp dir
			if(move_uploaded_file($tmpFilePath, $newFilePath)) {

			//Handle other code here

			}
  		}
	}
    // naturemyhome@gmail.com
	
	$email = new Email("robertbourton777@gmail.com", $subject, $body, $attachment);
	if ($email->sendEmail()) {
		echo "Email was sent";
		$rb->sendEmail();
	} else {
		echo "Email was not sent";
	}
}

?>

<form action="" method='POST' enctype="multipart/form-data">
	<div>
		<input name='subject' type="text" placeholder="Subject">
	</div>
	
	<div>
		<textarea name="body"  cols="30" rows="10" placeholder="Body of email"></textarea>
	</div>
	<div>
		<input name="upload[]" type="file" multiple />
	</div>
	<div>
		<input name='send' type="submit" value="Send">
	</div>
	
</form>
