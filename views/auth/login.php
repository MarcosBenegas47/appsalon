<h1 class="nombre-pagina">login</h1>
<p class="descripcion-pagina">inicia sesion con tus datos</p>

<form action="/" class="formulario" method="POST" >
    <div class="campo">
    <label for="email">Email</label>
    <input type="email" id="name" placeholder="Tu Email" name="email">
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" placeholder="Tu contraseña" name="password">
    </div>
    <input type="submit" class="boton" value="Iniciar Sesión ">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿olvidaste tu contraseña?</a>
</div>