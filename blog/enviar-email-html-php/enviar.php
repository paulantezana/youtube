como enviar correo electronico con html en php
<?php
function layoutTemplate($contentHTML = '')
{
    return '<!DOCTYPE html>
              <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <title>Email</title>
                </head>
                <body>
                  <div style=\'background: #FAFAFA; padding: 5rem 0; text-align: center;\'>
                    <div style=\'max-width:590px!important; width:590px; background: white;padding: 1rem;margin: auto;\'>
                       ' . $contentHTML . '
                    </div>
                  </div>
                </body>
              </html>
        ';
}

function sendEmail($to, $subject, $from, $senderName, $message, $files = array())
{
    // Vars
    $semiRand = md5(time());
    $separator = "==Multipart_Boundary_x{$semiRand}x";
    $eol = "\r\n";

    // Header
    $headers = "From: {$senderName} <{$from}>" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$separator}\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;

    // Message
    $body = "--{$separator}" . $eol;
    $body .= "Content-Type: text/html; charset=\"UTF-8\"" . $eol;
    $body .= "Content-Transfer-Encoding: 7bit" . $eol;
    $body .= layoutTemplate($message) . $eol;

    // Attachment
    for ($i = 0; $i < count($files); $i++) {
        if (is_file($files[$i])) {
            $fileName = basename($files[$i]);
            $fileSize = filesize($files[$i]);
            $fileType = mime_content_type($files[$i]);

            $fileStream = fopen($files[$i], "rb");
            $fileContent = fread($fileStream, $fileSize);
            fclose($fileStream);
            $fileContentEncoded = chunk_split(base64_encode($fileContent));

            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: $fileType; name=\"" . $fileName . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment; filename=\"" . $fileName . "\"" . $eol;
            $body .= "X-Attachment-Id: " . rand(1000, 99999) . $eol . $eol;
            $body .= $fileContentEncoded . $eol;
        }
    }

    // $body .= "--{$separator}--";
    // $returnpath = "-f" . $senderEmail;

    return mail($to, $subject, $body, $headers);
}

$respuesta = sendEmail('ana@gmail.com', 'Hola mundo', 'admin@gmail.com', 'admin', 'hola mundo', [__DIR__, '/archivo.txt']);
if ($respuesta) {
    echo 'El correo se envió exitosamente';
} else {
    echo 'No se pudo enviar el correo electrónico.';
}
