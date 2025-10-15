<?php
/**
 * Script de envío de correo de última instancia para máxima compatibilidad.
 * Utiliza la función mail() de la manera más sencilla, incluyendo solo la cabecera Reply-To.
 * Si esto falla, la única solución es usar SMTP (PHPMailer).
 */

// VARIABLES
$nombre = "";
$email = "";
$telefono = "";
$mensaje = "";
$error = "";

// 1. VALIDACIÓN
if (empty($_POST["nombre"])) {
    $error .= 'Ingresa un nombre<br>';
} else {
    $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_STRING);
    if ($nombre == '') $error .= 'Nombre está vacío<br>';
}

if (empty($_POST["email"])) {
    $error .= 'Ingresa un E-mail<br>';
} else {
    $email = trim($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= 'Ingresa un E-mail verdadero<br>';
    } else {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    }
}

if (empty($_POST["telefono"])) {
    $error .= 'Ingresa un teléfono<br>';
} else {
    $telefono = trim($_POST["telefono"]);
    if (!ctype_digit($telefono) || strlen($telefono) != 10) {
        $error .= 'El teléfono debe ser numérico y tener 10 dígitos.<br>';
    }
}

if (empty($_POST["mensaje"])) {
    $error .= 'Ingresa un mensaje<br>';
} else {
    $mensaje = filter_var(trim($_POST["mensaje"]), FILTER_SANITIZE_STRING);
    if ($mensaje == '') $error .= 'Mensaje está vacío<br>';
}

// 2. CUERPO DEL MENSAJE
$cuerpo  = "--- NUEVO CONTACTO DE HULE EVEREST ---\n\n";
$cuerpo .= "Nombre: " . $nombre . "\n";
$cuerpo .= "Email: " . $email . "\n";
$cuerpo .= "Telefono: " . $telefono . "\n\n";
$cuerpo .= "Mensaje:\n" . $mensaje . "\n";

// 3. DATOS DE ENVÍO
$enviarA = 'dsanchez@grupoeverest.com.mx, dperez@grupoeverest.com.mx';
$asunto  = 'NUEVO CONTACTO WEB A HULE EVEREST';

// 4. CABECERAS MÍNIMAS (Solo Reply-To)
// Esto evita la mayoría de los conflictos de seguridad del servidor.
$cabeceras = "Reply-To: " . $email . "\r\n";

// 5. ENVÍO
if ($error == '') {
    // Intentar envío de la forma más sencilla, sin el parámetro de remitente técnico (-f)
    $success = @mail($enviarA, $asunto, $cuerpo, $cabeceras);

    if ($success) {
        echo 'exito';
    } else {
        // Si falla, el problema es 100% de la configuración de envío del hosting.
        echo 'Error de servidor: No se pudo iniciar el envío de correo. Contacta a tu proveedor.';
    }
} else {
    echo $error; // Mostrar errores de validación
}
?>
