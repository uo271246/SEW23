<?php
require 'baseDeDatos.php';

class ReservaTuristica {
    private $bd;
    private $mensajeExito;
    private $mensajeError;

    public function __construct() {
        $this->bd = new BaseDeDatos();
    }

    public function verificarSesion() {
        session_start();
        if (!isset($_SESSION["email"])) {
            header("Location: login.php");
            exit();
        }
    }

    public function procesarReserva() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $idRecurso = $_POST["idRecurso"];
            $fechaInicio = $_POST["fechaInicio"];
            $fechaFin = $_POST["fechaFin"];
            //primero buscamos el id del usuario
            $sql = "SELECT id FROM users WHERE email = '" . $_SESSION["email"] . "'";
            $resultado = $this->bd->query($sql);
            $filaUsuario = $resultado->fetch_assoc();
            $idUsuario = $filaUsuario["id"];
    
             //comprobacion 1: fechas mal (inicio mas tarde que final)
            if ($fechaInicio >= $fechaFin) {
                $this->mensajeError = "La fecha de inicio debe ser menor que la fecha de fin.";
                return;
            }
            //no puede ser antes que ahora la reserva
            $today = date("Y-m-d H:i:s");
            if ($fechaInicio < $today) {
                $this->mensajeError = "La fecha de inicio no puede ser anterior a la fecha actual";
                return;
            }

            //comporobacion 2: intervalo ya reservado ( una fecha inicio no puede estar entre una inicio y una final)
            $sql = "SELECT * FROM bookings WHERE (('$fechaInicio' BETWEEN initDate AND endDate) OR ('$fechaFin' BETWEEN initDate AND endDate)) AND (resource_id = '$idRecurso')";
            $resultado = $this->bd->query($sql);

            if ($resultado->num_rows > 0) {
            $this->mensajeError = "La fecha de inicio está dentro del período de otra reserva. Por favor, elige otra fecha.";
            return;
            }

            //comprobacion 3: mirar si hay 
            $sql = "SELECT available FROM resources WHERE id = '$idRecurso' FOR UPDATE";
            $resultado = $this->bd->query($sql);
            
            // Comprobación 3: recurso con available > 0
            if ($resultado->num_rows > 0) {
                $filaRecurso = $resultado->fetch_assoc();
                $available = $filaRecurso["available"];
        
                if ($available <= 0) {
                    $this->mensajeError = "No hay plazas disponibles";
                    return;
                }
        
            //En consecuencia restamos una a available de ese recurso 
            $updatedAvailable = $available - 1;
        
            // Actualizar el campo available en la base de datos
            $sql = "UPDATE resources SET available = '$updatedAvailable' WHERE id = '$idRecurso'";
            $this->bd->query($sql);
            }

            //Ahora insertamos LA RESERVA ya habiendo hecho comprobaciones
            $sql = "INSERT INTO bookings (user_id, resource_id, initDate, endDate) VALUES ('$idUsuario', '$idRecurso', '$fechaInicio', '$fechaFin')";
            $resultado = $this->bd->query($sql);

            //Finalmente realizamos la FACTURA. será una para cada reserva.
            $sql = "SELECT price FROM resources WHERE id = '$idRecurso'";
            $resultado = $this->bd->query($sql);

            if ($resultado->num_rows > 0) {
            $filaRecurso = $resultado->fetch_assoc();
            $price = $filaRecurso["price"];

            // Diferencia entre fechaInicio y fechaFin
            $diferenciaDias = (strtotime($fechaFin) - strtotime($fechaInicio)) / (60 * 60 * 24);
            //Como minimo ha de ser 1
            if($diferenciaDias < 1){
                $diferenciaDias = 1;
            }
            // Calcular el total
            $total = $price * intval($diferenciaDias);

            // Insertar en la tabla bills
            $sql = "INSERT INTO bills (user_id, total) VALUES ('$idUsuario', '$total')";
            $resultado = $this->bd->query($sql);
         }

            if ($resultado) {
                $this->mensajeExito = "<p>Reserva realizada exitosamente.</p>";
                $this->mensajeExito .= $this->mostrarBills($idUsuario);
            } else {
                $this->mensajeError = "Error al realizar la reserva. Por favor, inténtalo nuevamente.";
            }
        }
    }

    public function mostrarBills($idUsuario) {
        $sql = "SELECT * FROM bills WHERE user_id = '$idUsuario'";
        $resultado = $this->bd->query($sql);
        $acumulador = 0;
        $bills = "";
        if ($resultado->num_rows > 0) {
            $bills .= "<h2>Tus facturas:</h2>";
            $bills .= "<ul>";
            while ($fila = $resultado->fetch_assoc()) {
                $idBill = $fila['id'];
                $total = $fila['total'];
                $acumulador += $total;
                $bills .= "<li>Factura id: $idBill de precio total: $total</li>";
            }
            $bills .= "</ul>";
            $bills .= "<p>El total de todas las facturas es $acumulador</p>";
        }
        return $bills;
    }

    public function obtenerRecursosTuristicos() {
        $sql = "SELECT id, name FROM resources";
        $resultado = $this->bd->query($sql);
        $recursosTuristicos = $resultado->fetch_all(MYSQLI_ASSOC);
        return $recursosTuristicos;
    }

    public function mostrarMensajes() {
        if (isset($this->mensajeExito)) {
            echo $this->mensajeExito;
        }

        if (isset($this->mensajeError)) {
            echo "<p>{$this->mensajeError}</p>";
        }
    }

    public function showOptions(){
        foreach ($this->obtenerRecursosTuristicos() as $recurso) { 
            echo '<option value="' . $recurso['id'] . '">' . $recurso['name'] . '</option>';        
    }
}
}

$reservaTuristica = new ReservaTuristica();
$reservaTuristica->verificarSesion();
$reservaTuristica->procesarReserva();
?>

<!DOCTYPE html>

<html lang="es">
<head>
<meta charset="UTF-8" />
	<meta name ="author" content = "Pablo Rodríguez Rodríguez" />
	<meta name ="description" content ="Reservas Riosa" />
	<meta name ="keywords" content= "Recurso turístico" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    <title>Reservar Recurso Turístico</title>
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
    <h2>Introduce una fecha y un recurso</h2>
    <p>Bienvenido o bienvenida a la pantalla de reserva de recursos</p>
    <p>Debes de elegir un recurso y dos fechas para poder hacer la reserva</p> 
    <p>Dicha reserva solo podrá ser realizada si hay hueco</p>
    <p>Dicha reserva solo podrá ser realizada si hay las fechas son correctas</p>
    <p>Dicha reserva solo podrá ser realizada si el recurso es correcto</p>
    <p>Si todo sale bien, podrás ver todas tus facturas y el total de dinero gastado en Euros</p>
    <p>Si algo sale mal se notificará el error</p>
    <form method="POST" action="#">
        <label for="idRecurso">Recurso Turístico:</label>
        <select id="idRecurso" name="idRecurso" required>
           <?php $reservaTuristica->showOptions();?>
        </select>
        <label for="fechaInicio">Fecha y hora de inicio:</label>
        <input type="datetime-local" id=fechaInicio name="fechaInicio" required>
        <label for="fechaFin">Fecha y hora de final:</label>
        <input type="datetime-local"  id="fechaFin" name="fechaFin" required>
        <button type="submit">Reservar</button>
    </form>
</section>
<section>  
    <?php $reservaTuristica->mostrarMensajes(); ?>
</section>
    </main>
	<footer>
	 <p>Autor : <em>Pablo Rodríguez Rodríguez</em></p>
	</footer>
</body>
</html>