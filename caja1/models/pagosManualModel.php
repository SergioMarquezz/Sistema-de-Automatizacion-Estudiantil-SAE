<?php

    require_once "mainModel.php";
    require_once "../views/includes/fecha.php";
    require_once "../views/includes/referencia.php";

    $option = $_POST['options'];

    if($option == 'students'){

        $sql = "SELECT a.matricula, p.nombre, p.apellido_pat, p.apellido_mat, c.nombre AS carrera, a.grado_actual, p.cve_persona
        FROM saiiut.saiiut.alumnos a
        INNER JOIN saiiut.saiiut.personas p ON p.cve_persona = a.cve_alumno
        INNER JOIN saiiut.saiiut.carreras_cgut c ON c.cve_carrera = a.cve_carrera
        WHERE a.cve_periodo_actual = (SELECT TOP 1 cve_periodo FROM saiiut.saiiut.periodos WHERE activo = 1 ORDER BY cve_periodo DESC )AND a.cve_unidad_academica = 1 AND a.cve_status = 1
        ORDER BY c.nombre";
    
        $query_students = executeQuery($sql);
    
        while($row = odbc_fetch_array($query_students)){
    
            $array_students["students"][] = array_map("utf8_encode", $row);  
        
            $json_students = json_encode($array_students);
        }
    
        echo $json_students;
    }

    else if($option == 'enrollments'){

        $enrollment = $_POST['enrollment'];

        $key_query = "SELECT al.cve_alumno
        FROM saiiut.saiiut.alumnos al
        WHERE al.matricula = '$enrollment'";

        $result_query = executeQuery($key_query);

        $key_student = odbc_result($result_query,"cve_alumno");

        $key['key_student'] = $key_student;

        print json_encode($key);
    }

    else if($option == "payment-manual"){

        $matricula = $_POST['matricula'];
        $date_save = $fecha;
        $type_people = 2;
        $key_people = $_POST['key_people'];
        $fertilizer = $_POST['fertilizer'];//Variable para genrar la referencia 70000
        $fertilizer_bd = $_POST['fertilizer_bd'];//Variable para guardar en base de datos 700
        $abono = $_POST['abonos']; //$fertilizer_bd * $quantity
        $key_period = periodoActivo();
        $key_payment = $_POST['key_concept']; //85
        $payment = 1;
        $reference = referenceToday($matricula,$key_payment,$fertilizer);
        $quantity = $_POST['quantity'];
        $identificador_payment = "CAJA";

        $insert_payment = "INSERT INTO saiiut.saiiut.pagos(cve_persona,cve_tipo_persona,cve_periodo,cve_concepto_pago,fecha,
        referencia_completa,cantidad,costo_unitario,abono,pago_realizado,fecha_guardado,lugar_pago)
        VALUES('$key_people','$type_people','$key_period','$key_payment','$date_save','$reference','$quantity',
        '$fertilizer_bd','$abono','$payment','$date_save','$identificador_payment')";

        $result_save = executeQuery($insert_payment);

        
        if($result_save){

            echo "save payment";
        }
    }
    else if($option == 'subject'){

        subjects();
    }


    TODO://Verificar dos periodos activos y ver que sale de resultado
    function periodoActivo(){
        
        $sql =  executeQuery("SELECT cve_periodo FROM saiiut.saiiut.periodos WHERE activo = 1");
  
        $num = odbc_num_rows($sql);
  
        if($num == 1){
  
          $periodo = odbc_result($sql,"cve_periodo");
  
          return $periodo;
        }
      }

    function subjects(){

        
        $matricula = $_POST['matricula'];

        $query_subjects = "SELECT a.grado_actual, (CASE WHEN g.id_grupo = 'A' THEN 1 WHEN g.id_grupo = 'B' THEN 2 
        WHEN g.id_grupo = 'C' THEN 3 WHEN g.id_grupo = 'D' THEN 4 WHEN g.id_grupo = 'E' THEN 5 WHEN g.id_grupo = 'F' THEN 6 END ) AS grupo, 
        c1.cve_grupo,c1.cve_periodo,c1.cve_materia,UPPER(m.nombre) as materia,c2.cve_maestro,(UPPER(rtrim(p.nombre))+' '+UPPER(rtrim(p.apellido_pat))+' '+
        UPPER(rtrim(p.apellido_mat))) as nombrecompleto,mf.cal_materia,mf.estado_cal
        from saiiut.saiiut.calificaciones_alumno as c1
        INNER JOIN saiiut.saiiut.grupo_materia c2 ON c2.cve_grupo = c1.cve_grupo and c2.cve_materia=c1.cve_materia
        INNER JOIN saiiut.saiiut.alumnos a ON a.cve_alumno = c1.cve_alumno
        INNER JOIN saiiut.saiiut.personas p ON p.cve_persona = c2.cve_maestro
        INNER JOIN saiiut.saiiut.grupos g ON a.cve_grupo = g.cve_grupo
        LEFT JOIN saiiut.saiiut.materias m ON m.cve_materia = c1.cve_materia
        LEFT JOIN sice.dbo.es_materia_final mf ON mf.matricula = a.matricula and mf.cve_periodo = c1.cve_periodo and mf.cve_materia = c1.cve_materia
        where c1.cve_periodo = a.cve_periodo_actual and c1.valida = 1 and a.matricula = '$matricula'";

        $result_query = executeQuery($query_subjects);
        

        while($data = odbc_fetch_array($result_query)){

            $array_subject["subjects"][] = array_map("utf8_encode", $data);  
    
            $json_subject = json_encode($array_subject);
        }

        echo $json_subject;
    }

?>