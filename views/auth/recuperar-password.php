<h1 class="nombre-pagina">Recuperar contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuacion </p>

<?php 
include_once __DIR__."/../templates/alertas.php";
?>

<?php if ($error) return ; ?>

<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu nuevo password">
    </div>
    <input type="submit" class="boton" value="Guardar Nuevo password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>

</div>