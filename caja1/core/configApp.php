<?php

    //Constantes para conexion local
    $user = "sergio";
    $pass = "ingsergiomarquez";
    $server = "localhost";
    $database = "administracion";
  /* const USER = "sa";
   const PASS = "Pachuc@2019";
   const SERVER = "200.10.10.3";
   const DATABASE = "administracion";*/

    //Constantes para conexion al servidor remoto del saiiut
    /*const SERVER_SAIIUT = '200.10.10.3';
    const USER_SAIIUT = "sitemas";
    const DATABASE_SAIIUT = "saiiut";
    const PASS_SAIIUT = "UtecAreaSistemas";*/

    //Linea de codigo para conectar con SQL Server 2017
    $conne = "Driver={SQL Server Native Client 10.0};Server=$server;Database=$database;";

    
    //Constante para almacenar la informacion de la base de datos
    //$conne = "Driver={SQL Server}; Server=$server; Database=$database; Integrated Security=SSPI;Persist Security Info=False;";
   $connection = odbc_connect($conne, $user, $pass);

  
    //Para ir cambiando la incriptacion despues de insertar un registro no se debe cambiar
    const METHOD = "AES-256-CBC";
    const SECRET_KEY = '$SERGIO@2019';
    const SECRET_IV = '192604';
 
?>