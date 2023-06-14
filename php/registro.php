<?php
require 'baseDeDatos.php';
class Usuario
{
    private $email;
    private $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function guardarUsuario()
    {
        $bd = new BaseDeDatos();
        
        // Verificar si el usuario ya existe en la base de datos
        $sql = "SELECT * FROM users WHERE email = '" . $this->email . "'";
        $resultado = $bd->query($sql);
        
        if ($resultado && $resultado->num_rows > 0) {
            return false; // El usuario ya existe, no se puede registrar nuevamente
        }

        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO users (email, password) VALUES ('" . $this->email . "', '" . $this->password . "')";
        $resultado = $bd->query($sql);

        if ($resultado) {
            return true; // Usuario registrado exitosamente
        }

        return false; // Error al registrar el usuario
    }
}

class SesionChecker {
    public function __construct()
    {     
        session_start();
    }

    public function validarSesion(){
        if (isset($_SESSION["email"])) {
            header("Location: reservas.php");
            exit();
        }
    }

    public function requestMethod(){

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];
        
            $usuario = new Usuario($email, $password);
        
            if ($usuario->guardarUsuario()) {
                // El usuario se ha registrado correctamente, puedes redirigirlo a otra página o mostrar un mensaje de éxito
                $_SESSION["email"] = $email;
                header("Location: reservas.php");
                exit();
            } else {
                $_SESSION["mensajeError"] = "Error al registrar el usuario. Por favor, inténtalo nuevamente.";
            }
        }
    }

    public function printMensajeError(){
  
        if (isset($_SESSION["mensajeError"])) {
          echo "<p>" . $_SESSION["mensajeError"] . "</p>";
          unset($_SESSION["mensajeError"]); // Limpiar el mensaje de error de la sesión
        }
      

}

}

$sesionChecker = new SesionChecker();
$sesionChecker -> validarSesion();
$sesionChecker -> requestMethod();

?>

<!DOCTYPE html>

<html lang="es">
<head>
<meta charset="UTF-8" />
    <meta name ="author" content = "Pablo Rodríguez Rodríguez" />
	<meta name ="description" content ="Registro Riosa" />
	<meta name ="keywords" content= "Email, contraseña" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
  <title>Registro de Usuario</title>
  <link rel='stylesheet' type='text/css' href='../estilo/estilo.css'/>
  <link rel='stylesheet' type='text/css' href='../estilo/layout.css'/>
</head>
<body>
    <header>
    <h1>Concejo de Riosa</h1>
	</header>
	<nav>
		<a title="Indice" accesskey="i" href="../index.html" tabindex="1">Índice</a>
		<a title="Gastronomía" accesskey="g" href="../gastronomia.html" tabindex="2">Gastronomía</a>
		<a title="Juego" accesskey="j" href="../juego.html" tabindex="3">Juego</a>
		<a title="Meteorología" accesskey="m" href="../meteorologia.html" tabindex="4">Meteorología</a>
		<a title="Rutas" accesskey="r" href="../rutas.html" tabindex="5">Rutas</a>
		<a title="Reservas" accesskey="s" href="reservas.php" tabindex="6">Reservas</a>
        <a title="Login" accesskey="c" href="login.php" tabindex="7">Iniciar sesión</a>
        <a title="Registrarse" accesskey="t" href="registro.php" tabindex="8">Registro</a>
	</nav>
<main>
  
    <section>
    <h2>Introduce tus datos</h2>
    <p>Bienvenido o bienvenida a la pantalla de registro de usuario</p>
    <p>Debes seguir los siguientes pasos:</p>
    <ul>
			<li>Introducir un email válido</li>
			<li>Introducir un email que no esté registrado</li>
			<li>Introducir una contrasña</li>
			<li>Darle al botón</li>
			<li>Esperar los resultados</li>
            <li>Si todo ha salido correcto, se registrará el usuario</li>
            <li>Si todo ha salido correcto, se redirigirá a reservas</li>
            <li>Si no ha salido todo bien, se marcará el error</li>
	</ul>
    <p>Debes introducir tus datos en el formulario de abajo</p>
    <form method="POST" action="#">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Registrar</button>
    </form>
    </section>
    <section>
    <?php $sesionChecker->printMensajeError()?>
    </section>
    </main>
	<footer>
	 <p>Autor : <em>Pablo Rodríguez Rodríguez</em></p>
	</footer>
</body>
</html>
