<!DOCTYPE html>
<html lang="es-ES">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Por favor, verifica tu dirección de correo</h2>

        <div>
            Gracias por creaer tu cuenta en Minegamers. <br/>
            Sigue el siguiente enlace para verificar tu correo electrónico <br/>
            <br/>
            {{ URL::to('register/verify/' . $confirmationCode) }}.<br/>
            <br/>
            Si tienes problemas, pega la URL en tu navegador.
        </div>

    </body>
</html>
