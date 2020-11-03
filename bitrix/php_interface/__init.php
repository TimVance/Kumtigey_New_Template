<?php function custom_mail($to,$subject,$body,$headers) {
$f=fopen($_SERVER["DOCUMENT_ROOT"]."/maillog.txt", "a+");
fwrite($f, print_r(array('TO' => $to, 'SUBJECT' => $subject, 'BODY' => $body, 'HEADERS' => $headers),1)."\n========\n");
fclose($f);
return mail($to,$subject,$body,$headers);
}
?>