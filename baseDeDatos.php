<?php
class BaseDeDatos
{
    private $conexion;

    public function __construct()
    {
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
        $this->conexion = new mysqli("localhost", "reservas", "sewExtraordinaria23", "riosa");
        } else {
            $con = mysqli_init();
            mysqli_ssl_set($con, NULL, NULL, __DIR__ . "/DigiCertGlobalRootCA.crt.pem", NULL, NULL);
            mysqli_real_connect($con, "sewextraordinaria.mysql.database.azure.com", "reservas", "sewExtraordinaria23", "riosa", 3306, MYSQLI_CLIENT_SSL);
            if ($con->connect_error) {
                die("Error de conexión: " . $con->connect_error);
            }
            if (mysqli_query($con,  "CREATE DATABASE IF NOT EXISTS $bd"
            )) {
            printf("Table created\n");
            }
        }
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        $this->crearBd("riosa");
        $this->seleccionarBd("riosa");
        $this->crearTablas();
        $this->crearDatos();
    }

    private function crearBd($bd)
    {
        $crearBdSQL = "CREATE DATABASE IF NOT EXISTS $bd";
        $this->conexion->query($crearBdSQL);
    }

    private function seleccionarBd($bd)
    {
        $this->conexion->select_db($bd);
    }

    public function query($sql)
    {
        return $this->conexion->query($sql);
    }

    private function crearTablas()
    {
        $crearTablaUsuarios = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(50),
            password VARCHAR(50)
        )";
        $this->conexion->query($crearTablaUsuarios);
        
        $crearTablaCategories = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150),
            description VARCHAR(150)
        )";
        $this->conexion->query($crearTablaCategories);
        $crearTablaRecursos = "CREATE TABLE IF NOT EXISTS resources (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150),
            description VARCHAR(150),
            category_id INT,
            available INT,
            price DECIMAL(10,2),
            FOREIGN KEY (category_id) REFERENCES categories (id)
        )";
        $this->conexion->query($crearTablaRecursos);
        $crearTablaBookings = "CREATE TABLE IF NOT EXISTS bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            resource_id INT,
            initDate DATETIME,
            endDate DATETIME,
            FOREIGN KEY (user_id) REFERENCES users (id),
            FOREIGN KEY (resource_id) REFERENCES resources (id)
        );";
        $this->conexion->query($crearTablaBookings);
        $crearTablaBills = "CREATE TABLE IF NOT EXISTS bills (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            total DECIMAL(10,2),
            FOREIGN KEY (user_id) REFERENCES users (id)
        );";
        $this->conexion->query($crearTablaBills);
    }

    private function crearDatos()
        //TABLA CATEGORIES
    {   //Id 1
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (1,'Senderismo', 'Ruta de senderismo')";
        $this->conexion->query($crearCategoria);
        //Id 2
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (2,'Restaurante', 'Ir a un restaurante')";
        $this->conexion->query($crearCategoria);
        //Id 3
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (3, 'Hotel', 'Ir a un hotel')";
        $this->conexion->query($crearCategoria);
        //Id 4
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (4, 'Museo', 'Ir a un museo')";
        $this->conexion->query($crearCategoria);
        //TABLA RESOURCES
        //Id 1
        $crearRecurso = "INSERT IGNORE INTO resources (id, name, description, category_id, available, price) 
        VALUES (1,'Subida al Angliru', 'Ruta de subida hasta la cima del Angliru', 1, 5, 10)";
        $this->conexion->query($crearRecurso);
    }

    public function cerrarConexion()
    {
        $this->conexion->close();
    }
}
?>
