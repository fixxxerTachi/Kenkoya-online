<?php
function sendMail($to, $subject, $body, $from_email,$from_name)
{
	mb_language("ja");
	mb_internal_encoding('UTF-8');
	$headers  = "MIME-Version: 1.0 \n" ;
	$headers .= "From: " . mb_encode_mimeheader($from_name,'ISO-2022-JP') ."<".$from_email."> \n";
	$headers .= "Reply-To: " ."".mb_encode_mimeheader($from_name,"ISO-2022-JP") ."" ."<".$from_email."> \n";
	$headers .= "Content-Type: text/plain;charset=ISO-2022-JP \n";
	$body = mb_convert_encoding($body, "ISO-2022-JP","AUTO");
	$sendmail_params  = "-f$from_email";
	$subject = mb_convert_encoding($subject,'ISO-2022-JP','AUTO');
	$subject = mb_encode_mimeheader($subject);	
	$result = mail($to, $subject, $body, $headers, $sendmail_params);
	return $result;
}
//	$result=sendMail('fixxxer_tachi@yahoo.co.jp','こんにちわ','こんにちわ内容です','mail@fixxxertachi.asia','たちばな');
//	var_dump($result);
