  var file_cvs;

$(document).ready(function () {
 
    searchData();
    seeData();
    uploadFile();
    saveData();
    $("#pagos-realizados").fadeOut();
        
});



function uploadFile(){

    $("#subir-archivo").click(function (e) { 
        e.preventDefault();

        var file2 = $('#subir-csv');  
        var archivo = file2[0].files; 

        $.each(archivo, function () { 
             
            file_cvs = this.name;
        });

        if(file_cvs != undefined){

           $("#subir-archivo").attr('disabled', true);
            var elem = document.getElementById("myBar");
            var width = 10;
            var id = setInterval(frame, 100);
        function frame() {
            if (width >= 100) {
                clearInterval(id);
                  
                  $("#subir-archivo").attr('disabled', false);
                 
                  var cero = width - 100;

                  elem.innerHTML = width - 100;
                  elem.style.width = cero; 

                  var inputFileCsv = document.getElementById("subir-csv");
                var file = inputFileCsv.files[0];

                var dataForm = new FormData();
                dataForm.append('archivo', file);
                dataForm.append('option', "upload");

                var url = "../views/includes/pagosVariable.php";

                $.ajax({
                    type: "POST",
                    url: url,
                    data: dataForm,
                    contentType: false,
                    processData: false,
                    cache: false, 
                    success: function (response) {
                        console.log(response);
                        var json = JSON.parse(response); 
                        
                        if(json.upload == true){
        
                            swal({
                                title: 'Satisfactorio',
                                text: "El archivo fue subido correctamente",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: "Aceptar",
                                allowOutsideClick: false
                            })
                        }else if(json.upload == "extension invalida"){

                            swal({
                                title: 'Ocurrio un error',
                                text: "El archivo debe ser de texto con extensión .txt",
                                type: 'error',
                                confirmButtonColor: '#ff0000',
                                confirmButtonText: "Aceptar",
                                allowOutsideClick: false
                            })

                        }
                    
                    }
                });

            }  
            else {
                width++; 
                elem.style.width = width + '%'; 
                elem.innerHTML = width * 1  + '%' + ' Subiendo Archivo';
            }
        }   
        }else{
            swal({
                title: 'Archivo no seleccionado',
                text: 'Selecciona un archivo para poder realizar la subida',
                imageUrl: '../views/img/txt-file.png',
                imageWidth: 400,
                imageHeight: 250,
                confirmButtonText: 'Aceptar',
                animation: false
              })
        }
    });
}

function saveData(){

    $("#save-datos").click(function (e) { 
        e.preventDefault();

        swal({
            title: "¿Estás seguro?",   
            text: "Los datos que se muestran en la tabla seran guardados",   
            type: "question",   
            showCancelButton: true,     
            confirmButtonText: "Si",
            cancelButtonText: "NO"
        }).then(function (){
            console.log(file_cvs);

           $.ajax({
                type: "POST",
                url: "../models/insertModelPagos.php",
                data: {
                    "data": file_cvs
                },
                success: function (response) {
                    console.log(response);

                    if(response == "Guardado"){

                        swal({
                            title: "Proceso Satisfactorio",   
                            text: "Los datos se han guardado correctamente",   
                            type: "success",   
                            confirmButtonText: "Aceptar",
                        })
                    }else{

                    }
                }
            });
        })
    });
}


function seeData(){

    $("#leer-archivo").click(function (e) { 
        e.preventDefault();
        
        var file2 = $('#cvs');  
        var archivo = file2[0].files; 
        
        $.each(archivo, function () { 
             
            file_cvs = this.name;
        });

        console.log(file_cvs);
        if(file_cvs == undefined){

            swal({
                title: 'Archivo no seleccionado',
                text: 'Selecciona un archivo para poder ver los datos',
                imageUrl: '../views/img/txt-file.png',
                imageWidth: 400,
                imageHeight: 250,
                confirmButtonText: 'Aceptar',
                animation: false
              })
        }else{

            $.ajax({
       
                type: "POST",
                data: {
                    "csv": file_cvs,
                    "option" : "read"
                },
                url: "../views/includes/pagosVariable.php",
                dataType: "json",
                success: function (response) {
                    console.log(response);
        
                    var filas = response.csv.length;
        
                    for( i= 0; i < filas; i++){
        
                        var tbody = "<tr><td for='id'>"+response.csv[i].id+"</td>"+
                                        "<td for='id'>"+response.csv[i].date+"</td>"+
                                        "<td for='id'>"+response.csv[i].refe+"</td>"+
                                        "<td for='id'>"+response.csv[i].cve_concepto_pago+"</td>"+
                                        "<td for='id'>"+response.csv[i].clave_matricula+"</td>"+
                                        "<td for='id'>"+response.csv[i].cargo+"</td>"+
                                        "<td for='id'>"+response.csv[i].abono+"</td>"+
                                        "<td for='id'>"+response.csv[i].saldo+"</td>"+
                                        "<td for='id'>"+response.csv[i].referencia+"</td>"+
                                    "</tr>"
                        $("#tbody").append(tbody);
                    }
                   
                }
            });

            $("#pagos-realizados").fadeIn();
        }        
    });
}



function searchData() {

    $("#myInput").keyup(function(){

        _this = this;

        $.each($("#myTable tbody tr"), function() {
            
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1){
                
                $(this).hide();
            }
            else{
                $(this).show();
            }
            
        });
    });
  }

    



