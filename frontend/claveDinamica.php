
<?php
    // header('Content-Type: application/json');
    $servidor = "localhost";
    $usuario = "root";
    $password = "";
    $base_datos = "cajeroecobank";
    
    // Crear conexión
    $conn = new mysqli($servidor, $usuario, $password, $base_datos);
    
    // Verificar conexión
    if ($conn->connect_error) {

        die(json_encode(array("status"=> 0,"data"=>"Conexión fallida - ERROR de conexión: " . $conn->connect_error)));
    }

    if (!$conn->query("INSERT INTO claves_dinamicas (documento, clave) 
        VALUES (1193546589, LPAD(FLOOR(RAND() * 1000000), 6, '0'))
        ON DUPLICATE KEY UPDATE 
        clave = LPAD(FLOOR(RAND() * 1000000), 6, '0'),
        fecha_modificacion = CURRENT_TIMESTAMP")
    ){


        die(json_encode(array("status"=> 0,"data"=> "FALLÓ LA INSERCIÓN DE LA CLAVE DINÁMICA  " . $conn->errno . ") " . $conn->error)));
    }
    
    $result = $conn->query("SELECT clave FROM claves_dinamicas WHERE documento = 1193546589")->fetch_assoc();

    die(json_encode(array("status"=> 1,"data"=>$result["clave"])));



    ?>