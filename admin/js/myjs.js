//cuando este la ventana cargada ejecuta la siguiente funcion
window.onload=function(){    
    //const selectElement = document.querySelector('.list-type');
    
    // selectElement.addEventListener('change', (event) => {
    //   const result = document.querySelector('.result');
    //   result.textContent = `You like ${event.target.value}`;
    // });
    
    // var  e =document.getElementById('list-type');
    // e.addEventListener('change', function() {
    //     alert('You selected: ',  e.options[e.selectedIndex].value);
    // });
    
    "use strict";

    function show_selected() {
        var selector = document.getElementById('list-type');
        var value = selector[selector.selectedIndex].value;
        window.location.replace('index.php?type='+selector[selector.selectedIndex].value);
      
    }
    document.getElementById('list-type').addEventListener('change', show_selected);
    
    /*·······················································································
     *
     *                      D   A   N   G   E   R
     *
     *·······················································································*/
    
    
    $('.view_data_trash').click(function(){        // Capturamos el evento clic del botón con clase view_data y en su caso ejecutamos la función
       var user_id = $(this).attr('id');     // Declaramos la var user_id y le damos el valor del atributo id del boton (que contiene el id del usuario)
       $.ajax({                              // Hacemos la llamada AJAX
          url:"select.php",                  // Especificamos el archivo a ejecutar con la llamada(que va a cargar los datos de la BD)
          method: "post",                    // Especificamos el método de envío de datos (POST)
          data: {user_id:user_id},           // Especificamos los datos que vamos a enviar (son pares identificador:valor)
          success: function(data) {          // En caso  de ÉXITO
              $('#user_details').html(data); // Inyecta los datos del usuario (user_id) en el div user_details
              $('#atencion').modal('show');  // Mostrar la ventana modal cuyo id es atencion
          }
       });
    });
    
    
    
    // -------------------------- Gestionamos el diálogo DANGER--> botón de la ventana modal
    jQuery('#btnborrar').on('click', function(e) {
        // Obtenemos el valor del cuadro de texto de la ventana modal
        var user_id =  $('#duser').text();  
        // cerrar la ventana modal
        $("#atencion").modal("hide");
        // redirigir al script correspondiente mandando el id por la url
        window.location.replace('borrar.php?user_id='+user_id);
    });
    
    
    /*·······················································································
     *
     *                      W   A   R   N   I   N   G
     *
     *·······················································································*/
    
    
     // -------------------------- Gestionamos el diálogo WARNING
     $('.view_data_checking').click(function(){
      
       var user_id = $(this).attr('id');
      
       $.ajax({
          url:"selectfull.php",
          method: "post",
          data: {user_id:user_id},
          success: function(data) {
              $('#warning_details').html(data);
              $('#warning').modal('show'); 
          }
       });
     
    });
    
    
    // -------------------------- Gestionamos el diálogo
    jQuery('#btnwarning').on('click', function(e) {
        // Obtenemos el valor del cuadro de texto de la ventana modal
        var id =  $('#myuser').text();  
        // cerrar la ventana modal
        $("#warning").modal("hide");
        // redirigir al script correspondiente mandando el id por la url
        window.location.replace('admitir.php?id='+id);
    });
    
    /*·······················································································
     *
     *                      E   D   I   T
     *
     *·······················································································*/
         // -------------------------- Gestionamos el diálogo WARNING
     $('.view_data_edit').click(function(){
      
       var user_id = $(this).attr('id');
      
       $.ajax({
          url:"update.php",
          method: "post",
          data: {user_id:user_id},
          success: function(data) {
              $('#editing_details').html(data);
              $('#editing').modal('show'); 
          }
       });
     
    });
    
    
    // -------------------------- Gestionamos el diálogo
    jQuery('#btnediting').on('click', function(e) {
        // Obtenemos el valor del cuadro de texto de la ventana modal
        var id =  $('#myuser').text();  
        $('#myupdateform').submit();
        // cerrar la ventana modal
        $("#editing").modal("hide");
        // redirigir al script correspondiente mandando el id por la url
        //window.location.replace('actualizar.php?id='+id);
        // var a=$('#myupdateform').serialize();
        // $.ajax({
        //     type:'post',
        //     url:'actualizar.php',
        //     data:a,
        //     beforeSend:function(){
        //         //launchpreloader();
        //     },
        //     complete:function(){
        //         //stopPreloader();
        //     },
        //     success:function(result){
        //          alert(result);
        //     }
        // });
        
    });
    
}