<?php
	

	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/PHPMailer/src/Exception.php';
    require 'PHPMailer/PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/PHPMailer/src/SMTP.php';

	class Email {
		private string $attachment_type;

		public function __construct(
			public string $email,
			public string $subject,
			public string $body,
			public mixed $attachment
		) {
			
			$this->attachment_type = gettype($attachment);
			
		}

		public function sendEmail() {
			$mail = new PHPMailer();
			
			$mail->IsSMTP(); 
			$mail->Host       = "smtp.gmail.com"; 
			$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing) 1 = errors and messages 2 = messages only                        
			$mail->SMTPAuth   = true;                  
			$mail->SMTPSecure = "tls";                 
			$mail->Host       = "smtp.gmail.com";      
			$mail->Port       = 587;                   
			$mail->Username   = "sales@guentherroofing.ca"; 
			$mail->Password   = "nest ipvx sxyf excm";            
	
			$mail->SetFrom('robertbourton777@gmail.com');
			$mail->Subject = $this->subject;
			$mail->MsgHTML($this->body);
			$mail->AddAddress($this->email);
			
			if ($this->attachment_type == 'array') {
				foreach ($this->attachment as $attachment) {
					$mail->addAttachment($attachment);
				}
			} elseif ($this->attachment_type == 'string') {
				$mail->addAttachment($this->attachment);
			}
			
			


			$mail->SMTPDebug = false;
			if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
				return false;
			}
			return true;
		}
	}

	
?>