<?php

// VARIABLES
$nombre = "";
$email = "";
$telefono = "";
$mensaje = "";
$error = "";

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

$cuerpo  = "--- FORMULARIO A HULE EVEREST, DESDE HULE FALDON ---\n";
$cuerpo .= "Url: https://www.huleeverest.com.mx/rollos-y-placas/hule-armorite-faldon/\n\n";
$cuerpo .= "Nombre: " . $nombre . "\n";
$cuerpo .= "Email: " . $email . "\n";
$cuerpo .= "Telefono: " . $telefono . "\n\n";
$cuerpo .= "Mensaje:\n" . $mensaje . "\n";

$enviarA = 'dsanchez@grupoeverest.com.mx, dperez@grupoeverest.com.mx';
$asunto  = 'NUEVO CONTACTO A HULE EVEREST';

$cabeceras = "Reply-To: " . $email . "\r\n";

if ($error == '') {
    $success = @mail($enviarA, $asunto, $cuerpo, $cabeceras);

    if ($success) {
        echo 'exito';
    } else {
        echo 'Error de servidor: No se pudo iniciar el envío de correo. Contacta a tu proveedor.';
    }
} else {
    echo $error; // Mostrar errores de validación
}
?>
