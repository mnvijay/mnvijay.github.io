<?php
 require_once 'PHPMailerAutoload.php';

  $email=$retmsg="";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = test_input($_POST["newsEmail"]);
  insert($email);
  echo $retmsg;
  
  }
  
  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function insert($email){
$servername = "localhost";
$username = "thejamculture";
$password = "h?aD1lEI]+yx";
$dbname = "thejamculture_db";

global $retmsg;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$stmt_select = "SELECT email_addr FROM email_subscription where email_addr='".$email."'";
//$stmt_select->bind_param("s", $email);
$exist=$conn->query($stmt_select);
// $stmt_select->close();
if ($exist->num_rows > 0){
	$retmsg="Wow! You are already subscribed. :)";
}
else{
	$retmsg="You have subscribed successfully!";
// prepare and bind
$stmt_insert = $conn->prepare("INSERT INTO `email_subscription`(`email_addr`) VALUES (?)");
$stmt_insert->bind_param("s", $email);
$stmt_insert->execute();
$stmt_insert->close();
sendmail($email);

}
$conn->close();

}

function sendmail($email){

 //Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
$mail->Host = localhost;
// $mail->Port = 465;
// $mail->SMTPSecure = 'tls';
// $mail->SMTPAuth = true;
// $mail->Username = "reachus@thejamculture.com";
// $mail->Password = "Sargpas123!";
$mail->setFrom('reachus@thejamculture.com', 'JamCulture');
$mail->addReplyTo('reachus@thejamculture.com', 'JamCulture');
$mail->addAddress($email);
$mail->Subject = 'Welcome!';
$mail->msgHTML(file_get_contents('emailContent.txt'));
if (!$mail->send()) {
   //  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
   //  echo "Message sent!";
}

}
  ?>