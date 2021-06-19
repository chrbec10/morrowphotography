<?php

$to = '';
$subject = '';
$message = '';
$return = '';
$headers = "From: cmphotography@cmp.com"

$message = wordwrap($message, 70, "\r\n");

mail($to, $subject, $message, $headers);

?>