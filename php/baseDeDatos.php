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
            mysqli_ssl_set($con, NULL, NULL,"./DigiCertGlobalRootCA.crt.pem", NULL, NULL);
            mysqli_real_connect($con, "sewextraordinaria.mysql.database.azure.com", "reservas", "sewExtraordinaria23", "riosa", 3306, MYSQLI_CLIENT_SSL);
            if ($con->connect_error) {
                die("Error de conexión: " . $con->connect_error);
            }
            $this->conexion = $con;
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
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
        $this->conexion->query($crearBdSQL); } 
        else {
            $resultado = mysqli_query($conexion, $crearBdSQL);
        }
    }

    private function seleccionarBd($bd)
    {
        $this->conexion->select_db($bd);
    }

    public function query($sql)
    {   if ($_SERVER['HTTP_HOST'] === 'localhost') {
        return $this->conexion->query($sql);
    } else {
        mysqli_query($conexion, $sql);
    }
    }

    private function crearTablas()
    { 
        $crearTablaUsuarios = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(50),
            password VARCHAR(50)
        )";
        
        $crearTablaCategories = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150),
            description VARCHAR(150)
        )";
       
        $crearTablaRecursos = "CREATE TABLE IF NOT EXISTS resources (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150),
            description VARCHAR(150),
            category_id INT,
            available INT,
            price DECIMAL(10,2),
            FOREIGN KEY (category_id) REFERENCES categories (id)
        )";
       
        $crearTablaBookings = "CREATE TABLE IF NOT EXISTS bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            resource_id INT,
            initDate DATETIME,
            endDate DATETIME,
            FOREIGN KEY (user_id) REFERENCES users (id),
            FOREIGN KEY (resource_id) REFERENCES resources (id)
        );";
        
        $crearTablaBills = "CREATE TABLE IF NOT EXISTS bills (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            total DECIMAL(10,2),
            FOREIGN KEY (user_id) REFERENCES users (id)
        );";
         if ($_SERVER['HTTP_HOST'] === 'localhost') {
            $this->conexion->query($crearTablaUsuarios);
            $this->conexion->query($crearTablaCategories);
            $this->conexion->query($crearTablaRecursos);
            $this->conexion->query($crearTablaBookings);    
            $this->conexion->query($crearTablaBills);
    
    } else {
        mysqli_query($conexion,$crearTablaUsuarios);
        mysqli_query($conexion,$crearTablaCategories);
        mysqli_query($conexion,$crearTablaRecursos);
        mysqli_query($conexion,$crearTablaBookings);    
        mysqli_query($conexion,$crearTablaBills);


    }
    }

    private function crearDatos()
        //TABLA CATEGORIES
    {   //Id 1
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (1,'Senderismo', 'Ruta de senderismo')";
        
        //Id 2
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (2,'Restaurante', 'Ir a un restaurante')";
        
        //Id 3
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (3, 'Hotel', 'Ir a un hotel')";
        
        //Id 4
        $crearCategoria = "INSERT IGNORE INTO categories (id, name, description) VALUES (4, 'Museo', 'Ir a un museo')";
        
        //TABLA RESOURCES
        //Id 1
        $crearRecurso = "INSERT IGNORE INTO resources (id, name, description, category_id, available, price) 
        VALUES (1,'Subida al Angliru', 'Ruta de subida hasta la cima del Angliru', 1, 5, 10)";
        
        if($_SERVER['HTTP_HOST'] === 'localhost') {
            $this->conexion->query($crearCategoria);   
            $this->conexion->query($crearCategoria);
            $this->conexion->query($crearCategoria);
            $this->conexion->query($crearCategoria);
            $this->conexion->query($crearRecurso);
        } else {
            mysqli_query($conexion,$crearCategoria);   
            mysqli_query($conexion,$crearCategoria);
            mysqli_query($conexion,$crearCategoria);
            mysqli_query($conexion,$crearCategoria);
            mysqli_query($conexion,$crearRecurso);
        }   

    }

    public function cerrarConexion()
    {
        $this->conexion->close();
    }
}
?>
