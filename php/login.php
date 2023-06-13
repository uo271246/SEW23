<?php
require 'baseDeDatos.php';
// Clase Usuario
class Usuario
{
  private $email;
  private $password;

  // Constructor
  public function __construct($email, $password)
  {
    $this->email = $email;
    $this->password = $password;
  }

  // Función para validar las credenciales del usuario
  public function validarCredenciales()
  {
    $bd = new BaseDeDatos();
  	$sql = "SELECT * FROM users WHERE email = '" . $this->email . "' and password = '" . $this->password . "'";
	  $resultado = $bd->query($sql);	
	  if($resultado && $resultado->num_rows > 0){
	  	return true;
  	}
	  return false;
  }
}

class SesionChecker{
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
public function requestMethod() {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $usuario = new Usuario($email, $password);

  // Validar las credenciales del usuario
  if ($usuario->validarCredenciales()) {
    // Iniciar sesión y redirigir al usuario a la página de reservas
    $_SESSION["email"] = $email;
    header("Location: reservas.php");
    exit();
  } else {
    // Mostrar un mensaje de error si las credenciales no son válidas
    $_SESSION["mensajeError"] = "<p>Credenciales inválidas. Por favor, inténtalo nuevamente</p>";
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
<html>
<head>
  <title>Iniciar Sesión</title>
  <link rel='stylesheet' type='text/css' href='../estilo/estilo.css' />
  <link rel='stylesheet' type='text/css' href='../estilo/layout.css' />
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
    <a title="Registrarse" accesskey="i" href="registro.php" tabindex="8">Registro</a>
	</nav>
<main>
<section>
  <form method='POST' action='#'>
    <label for='email'>Email:</label>
    <input type='email' id='email' name='email' required>
    <label for='password'>Contraseña:</label>
    <input type='password' id='password' name='password' required>
    <button type='submit'>Iniciar Sesión</button>
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

