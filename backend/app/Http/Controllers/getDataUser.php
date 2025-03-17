<?php
       header('Access-Control-Allow-Origin: *');
       header('Access-Control-Allow-Methods: GET, POST');
       header('Access-Control-Allow-Headers: Content-Type');
       header('Content-Type: application/json');
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

     $cuenta = $_POST['nro_cuenta'];
     $result = $conn->query("SELECT usuarios.nombre, cuentas.nro_cuenta, SUM(depositos.saldo) AS saldo 
                              FROM usuarios
                              LEFT JOIN cuentas ON usuarios.documento = cuentas.documento
                              LEFT JOIN depositos ON usuarios.documento = depositos.documento
                              WHERE cuentas.nro_cuenta = $cuenta
                              GROUP BY usuarios.documento;
                            ")->fetch_assoc();
      if ($result == NULL) {
        die(json_encode(array("status"=> 0,"data"=>"El número de cuenta no existe")));
      }          
      die(json_encode(array("status"=> 1,"data"=>$result)));

 
 
 
     ?>