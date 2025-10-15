// Al cargar el documento, asegúrate de que jQuery esté disponible.
$(document).ready(function() {

    $("#formulario").submit(function(event){
        event.preventDefault();

        // 1. Realizar validación en el cliente
        if (validarCliente()) {
            enviar();
        }
    });

    // Función de validación del lado del cliente
    function validarCliente() {
        let errores = [];
        // Obtener valores y limpiar espacios en blanco
        const nombre = $("#nombre").val().trim();
        const email = $("#email").val().trim();
        const telefono = $("#telefono").val().trim();
        const mensaje = $("#mensaje").val().trim();

        // Regex básicos
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        // Regex para validar 10 dígitos exactamente (asumiendo un formato estándar)
        const telefonoRegex = /^\d{10}$/; 

        // Validar Nombre
        if (nombre === '') {
            errores.push('El nombre es obligatorio.');
        }

        // Validar Email
        if (email === '') {
            errores.push('El email es obligatorio.');
        } else if (!emailRegex.test(email)) {
            errores.push('Ingresa un formato de email válido.');
        }

        // Validar Teléfono
        if (telefono === '') {
            errores.push('El teléfono es obligatorio.');
        } else if (!telefonoRegex.test(telefono)) {
            errores.push('El teléfono debe ser numérico y tener 10 dígitos.');
        }

        // Validar Mensaje
        if (mensaje === '') {
            errores.push('El mensaje es obligatorio.');
        }

        if (errores.length > 0) {
            // Unir todos los errores y mostrarlos
            phperror(errores.join('<br>'));
            return false;
        } else {
            // Limpiar errores si todo está bien
            $("#mensajeError").addClass("d-none").empty();
            return true;
        }
    }


    function enviar(){
        var datos = $("#formulario").serialize();

        $.ajax({
            type: "post",
            url:"formulario.php",
            data: datos,
            success: function(texto){
                // La respuesta del PHP puede ser 'exito' o una cadena de error
                if(texto=="exito"){
                    correcto();
                }else{
                    // Si el PHP devuelve un error, lo muestra
                    phperror(texto);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Manejo de errores de conexión/servidor
                phperror("Error de conexión al servidor: " + textStatus + " - " + errorThrown);
            }
        });
    }

    function correcto(){
        $("#mensajeExito").removeClass("d-none");
        $("#mensajeError").addClass("d-none");
        // Asegúrate de usar el ID del formulario para resetear
        document.getElementById("formulario").reset(); 
    }

    function phperror(texto){
        $("#mensajeError").removeClass("d-none");
        $("#mensajeError").html(texto);
        $("#mensajeExito").addClass("d-none");
    }
});
