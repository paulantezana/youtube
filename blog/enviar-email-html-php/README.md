# Como enviar correo electronico con html y archivos en php
PHP tiene una función nativa denominada `mail` que nos permite enviar correos electrónicos de una manera muy sencilla. en este apartado veremos cómo enviar un correo electrónico con archivos adjuntos e incluir una estructura HTML que será útil para agregar algun tipo de presentación.

## Plantilla HTML
Crearemos nuestra plantilla HTML con todos los medios necesarios, cabe mencionar que los estilos CSS deben ser inline (estilos en línea), por otro parte se recomienda usar tablas para maquetar la estructura HTML, ya que los servicios de correo electrónico actuales aun no so portan las ultimas especificaciones de CSS
```php
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
```

## Enviar correo electrónico
Crearemos una funcion reutilizable para enviar correos electrónicos desde cualquier parte de nuestro codigo PHP.
```php
function sendEmail($to, $subject, $from, $senderName, $message, $files = array())
```
* `$to`: destinatario del correo electrónico.
* `$subject`: asunto del correo electrónico.
* `$from`: remitente del correo electrónico.
* `$senderName`: nombre del remitente
* `$message`: mensaje o contenido HTML del correo electrónico.
* `$files`: matris de las rutas absolutas de los archivos a enviar (parámetro opcional).

Esta función se divide en cinco partes principales que detallaremos a continuación.

### Declaracion de variables
Declararemos algunas variables que nos seran de utilidad mas adelante
```php
$semiRand = md5(time());
$separator = "==Multipart_Boundary_x{$semiRand}x";
$eol = "\r\n";
```
* `$separator`: separador con un hash que es muy necesario para enviar contenido mixto.
* `$eol`: retorno de carro de tipo (RFC).

### Encabezados
En los encabezados del correo electrónico podremos indicar el origen, destinatario, copia y el tipo de contenido que se enviara al destinatario.
```php
$headers = "From: {$senderName} <{$from}>" . $eol;
$headers .= "MIME-Version: 1.0" . $eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"{$separator}\"" . $eol;
$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
```
### Mensaje y/o contenido HTML
Añadiremos el contenido html de nuestro mensaje al destinatario, usando la plantilla html que creamos anteriormente.
```php
$body = "--{$separator}" . $eol;
$body .= "Content-Type: text/html; charset=\"UTF-8\"" . $eol;
$body .= "Content-Transfer-Encoding: 7bit" . $eol;
$body .= layoutTemplate($message) . $eol;
```
### Archivos
Recorremos todos los archivos que deseamos enviar de cada archivo extraemos el tipo de archivo, tamaño y nombre y lo codificamos en base64, de esta manera podremos enviar el archivo como una cadena de texto en base64 
```php
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
```
### Enviar correo
Finalmente llamaremos la función nativa `mail` de php al cual le pasaremos los parámetros necesarios que construimos anteriormente.
```php
mail($to, $subject, $body, $headers);
```

### Enviar correo de ejemplo
Este es un ejemplo de como usar la funcion que creamos para el envio de correo electronico.
```php
$respuesta = sendEmail('ana@gmail.com', 'Hola mundo', 'admin@gmail.com', 'admin', 'hola mundo', [__DIR__, '/archivo.txt']);
if ($respuesta) {
    echo 'El correo se envió exitosamente';
} else {
    echo 'No se pudo enviar el correo electrónico.';
}
```

## Version final
```php
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
```
