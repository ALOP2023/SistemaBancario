<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

    $servidor = "localhost";
    $usuario = "root";
    $password = "";
    $base_datos = "cajeroecobank";

    $conn = new mysqli($servidor, $usuario, $password, $base_datos);

    if ($conn->connect_error) {
        die(json_encode(array("status"=> 0,"data"=>"Conexión fallida - ERROR de conexión: " . $conn->connect_error)));
    }         


    function retirarDinero($monto) {
        $billetes = [10000, 20000, 50000, 100000]; 
        $billetesEntregados = [10000 => 0, 20000 => 0, 50000 => 0, 100000 => 0];
        $filas = [];
        $montoRestante = $monto;
        $numFila = 1;
        $posicionEliminar = 0;
    
        if ($monto % 10000 != 0) {
            return "El monto debe ser múltiplo de 10,000.";
        }
    
        while ($montoRestante > 0) {
            $filaActual = [];
            $montoFila = 0;
    
            foreach ($billetes as $index => $billete) {
                if ($numFila > 1 && $index < $posicionEliminar) {
                    $filaActual[$billete] = 0;
                } else {
                    if ($montoRestante >= $billete && ($montoFila + $billete) <= $montoRestante) {
                        $filaActual[$billete] = $billete;
                        $montoFila += $billete;
                        $billetesEntregados[$billete]++;
                    } else {
                        $filaActual[$billete] = 0;
                    }
                }
            }
    
            $filas[$numFila] = $filaActual;
            $montoRestante -= $montoFila;
            $numFila++;
            $posicionEliminar++;
    
            if ($posicionEliminar >= count($billetes) && $montoRestante > 0) {
                $posicionEliminar = 0;
            }
        }
    
        if ($montoRestante > 0) {
            return "No se pudo retirar exactamente el monto solicitado.";
        }
    
        return [
            "filas" => $filas,
            "billetes_entregados" => $billetesEntregados,
            "valor_retirado" => $monto
        ];
    }

    $datos = $_POST;
    // QUERY VALIDAR CLAVE
    $validarClave = $conn->query("SELECT cuentas.documento, claves_dinamicas.clave 
                                FROM cuentas
                                JOIN claves_dinamicas ON claves_dinamicas.documento = cuentas.documento
                                WHERE cuentas.nro_cuenta =" .$datos['nro_cuenta'])->fetch_all(MYSQLI_ASSOC);
    $clavebd = $validarClave[0]['clave'];
    $clave = $datos['clave'];
    if ($clave != $clavebd) {
        die(json_encode(array("status"=> 0,"data"=>"La clave es incorrecta")));
    }
    // QUERY CONSULTAR SALDO POR NRO DE CUENTA
    $result = $conn->query("SELECT cuentas.documento, depositos.saldo, depositos.id 
                            FROM cuentas
                            JOIN depositos ON depositos.documento = cuentas.documento
                            WHERE nro_cuenta =" .$datos['nro_cuenta'])->fetch_all(MYSQLI_ASSOC);

    $saldo = array_sum(array_column($result,'saldo'));
    
    if ($saldo >= $datos['valorRetiro']) {
        $resultado = retirarDinero($datos['valorRetiro']);
        if (is_string($resultado)) {
            die(json_encode(array("status"=> 0, "data"=>$resultado)));
        }
        
        foreach ($result as $key => $value) {
            if ($value['saldo'] > $datos['valorRetiro']) {
                $saldoTotal = $value['saldo'] - $datos['valorRetiro'];
                
                $conn->query("UPDATE depositos
                                SET saldo = $saldoTotal
                                WHERE id =".$value['id']);
                break;
            }
        }
        $resultado["saldoTotal"]= $saldo - $datos['valorRetiro'];
        // var_export($saldoTotal);
        // die();
    }
    die(json_encode(array("status"=> 1,"data"=>$resultado)));



