<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Rul Ware</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../../lib/css/menu.css"> 
  <script src="../../lib/js/mask.js"> </script>
  <!-- Font Awesome JS -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  <link rel="icon" href="../../img/icono.ico" type="image/x-icon"/>
</head>
<style>
.modal-footer {
  padding: 2px;
}
.modal-header {
  padding: 2px;
}
.modal-title{
  color:#000;
  font-size: 18px;
  font-weight: normal;
  margin-left: 10px;
}
.menu_icon {
    width: 30px;
    margin-left: 5px;
}
label{
  font-size: 15px;
}
</style>
<body>
  <div class="wrapper">
    <!-- Sidebar Holder -->
    <nav id="sidebar">
      <div class="sidebar-header">        
        <a href="./"><img src="../../img/stockfar.png" width="180px;"></a>
      </div>
      <ul class="list-unstyled components">   
        <li >
          <a href="#menuUsuario" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-users"></i> Usuario</a>
          <ul class="collapse list-unstyled" id="menuUsuario">
            <li>
              <a onclick="cargar_vista('../../view/usuario/usuario_registrar.html')">Nuevo
                 Usuario</a>
            </li>
            <li>
              <a onclick="cargar_vista('../../view/usuario/usuario_lista.html')">Lista de Usuarios</a>
            </li> 
          </ul>
        </li>
        <li >
          <a href="#menuPropietario" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-child"></i> Propietario</a>
          <ul class="collapse list-unstyled" id="menuPropietario">
            <li>
              <a onclick="cargar_vista('../../view/propietario/propietario_registrar.html')">Nuevo Propietario</a>
            </li>
            <li>
              <a onclick="cargar_vista('../../view/propietario/propietario_administrar.html')">Adminstrar Propietarios</a>
            </li>                        
          </ul>
        </li>             
        <li >
          <a href="#menuDepartamento" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class=" menu_icon fas fa-building"></i> Departamento</a>
          <ul class="collapse list-unstyled" id="menuDepartamento">
            <li>
              <a onclick="cargar_vista('../../view/departamento/departamento_registrar.html')">Nuevo Departamento</a>
            </li>            
            <li>
              <a onclick="cargar_vista('../../view/departamento/departamento_administrar.html')">Adminstrar Departamentos</a>
            </li>                        
          </ul>
        </li>
        <li>
          <a href="#menuGasto" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-bus"></i> Gastos</a>
          <ul class="collapse list-unstyled" id="menuGasto">
            <li>
              <a onclick="cargar_vista('../../view/gasto/gasto_registrar.html')">Nuevo Gasto</a>
            </li>            
            <li>
              <a onclick="cargar_vista('../../view/gasto/gasto_administrar.html')">Administrar Gastos</a>
            </li>
          </ul>
        </li>
        <li>
          <a href="#menuCuota" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-bus"></i> Cuotas</a>
          <ul class="collapse list-unstyled" id="menuCuota">
            <li>
              <a onclick="cargar_vista('../../view/cuota/cuota_registrar.html')">Calcular Cuota</a>
            </li>            
            <li>
              <a onclick="cargar_vista('../../view/cuota/cuota_administrar.html')">Administrar Cuotas</a>
            </li>
          </ul>
        </li>
        <li>
          <a href="#homeCaja" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-notes-medical"></i> Cajas</a>
          <ul class="collapse list-unstyled" id="homeCaja">
            <li>
              <a onclick="cargar_vista('../../view/caja/caja_registrar.html')">Registrar Caja</a>
            </li>
            <li>
              <a onclick="cargar_vista('../../view/caja/caja_administrar.html')">Administrar Cajas</a>
            </li>                    
          </ul>
        </li>
        <li>
          <a href="#homeRecibo" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-notes-medical"></i> Recibo</a>
          <ul class="collapse list-unstyled" id="homeRecibo">
            <li>
              <a onclick="cargar_vista('../../view/recibo/recibo_registrar.html')">Registrar Recibo</a>
            </li>
            <li>
              <a onclick="cargar_vista('../../view/recibo/recibo_administrar.html')">Administrar Recibos</a>
            </li>                    
          </ul>
        </li>
        <li>
          <a href="#homeMovimientos" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-notes-medical"></i> Movimientos</a>
          <ul class="collapse list-unstyled" id="homeMovimientos">
            <li>
              <a onclick="cargar_vista('../../view/movimiento/ingreso_registrar.html')">Registrar Ingreso</a>
            </li> 
            <li>
              <a onclick="cargar_vista('../../view/movimiento/ingreso_administrar.html')">Administrar Ingresos</a>
            </li>
            <li>
              <a onclick="cargar_vista('../../view/movimiento/salida_registrar.html')">Registrar Salida</a>
            </li> 
            <li>
              <a onclick="cargar_vista('../../view/movimiento/salida_administrar.html')">Administrar Salidas</a>
            </li>           
          </ul>
        </li>
        <li>
            <a href="#homeVentas" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="menu_icon fas fa-chart-line"></i> Ventas</a>
            <ul class="collapse list-unstyled" id="homeVentas">
              <li>
                  <a onclick="cargar_vista('../../view/venta/venta_resumen.html')">Resumen de Venta</a>
              </li> 
              <li>
                  <a onclick="cargar_vista('../../view/venta/ganacia_resumen.html')">Resumen de Ganancia</a>
              </li>                       
            </ul>
          </li>
      </ul>
    </nav>
    <!-- Menu Superior -->
    <div id="content" >
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <button type="button" id="sidebarCollapse" class="navbar-btn">
            <span></span>
            <span></span>
            <span></span>
          </button>
          <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-align-justify"></i>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
              <div class="dropdown dropleft float-right">
                  <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
                      <i class="fas fa-user"></i> <span id="tk_user">zzzz</span>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0)" onclick='usuarioResetearClave()'><i class="fas fa-key"></i> Cambiar de Clave</a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick='SalirApp()'><i class="fas fa-sign-out-alt"></i> Salir</a>
                  </div>
                </div>
            </ul>
          </div>
        </div>
      </nav>
      <!-- Menu Superior -->  
      <style>
        p,h6{
          color:#FFF;
        }
      </style>
      <div class="container-fluid" id="contenido">
        <center><h3 id="mjsBienvenida"></h3></center>
        <div class="container">          
          <div class="card-columns">
            <div class="card bg-danger"> 
              <div class="card-header text-center" style="color:#FFF">Productos a Vencer</div>               
              <div class="card-body" id="card_vence">                
                <p class="card-text">20/05/2019 - Paracetamol 500mg</p>
                <p class="card-text">23/05/2019 - Paracetamol 300mg</p>
                <p class="card-text">25/05/2019 - Paracetamol 100mg</p>
                <a href="#" class="btn btn-warning">Ver Mas</a>
              </div>              
            </div>
            <div class="card bg-info">
              <div class="card-header text-center" style="color:#FFF">Ingresos</div>               
              <div class="card-body" id="card_ingreso">                 
                <p class="card-text">Paracetamol 500mg - 10 und</p>
                <p class="card-text">Paracetamol 300mg - 10 und</p>
                <p class="card-text">Paracetamol 100mg - 10 und</p>
                <a href="#" class="btn btn-warning">Ver Mas</a>
              </div>              
            </div>
            <div class="card bg-success">
              <div class="card-header text-center" style="color:#FFF">Salidas</div>               
              <div class="card-body" id="card_salida">                
                <p class="card-text">Paracetamol 500mg - 5 und</p>
                <p class="card-text">Paracetamol 300mg - 8 und</p>
                <p class="card-text">Paracetamol 100mg - 1 und</p>
                <a href="#" class="btn btn-warning">Ver Mas</a>
              </div>              
            </div>
          </div>
        </div>
      </div>  
      <!-- Menu Superior -->
    </div>
  
  </div>
  <!-- The Modal -->
    <!-- Modal para Acciones 
    <div class="modal" id="modal_formulario" role="dialog"   data-backdrop="false" >
    </div> -->
    <div class="modal" id="modal_formulario">
    </div>   
    <!-- Modal para Mensajes -->
    <div class="modal fade" id="modal_mensaje">      
    </div> 
    <!-- Modal para Cargando -->
    <div class="modal" id="modal_cargando" role="dialog" data-backdrop="false" style="background: #343a4052;">
    </div>
    
</body>

</html>

<script type="text/javascript">

function userActive(){
  if(parseInt(sessionStorage.getItem("perfil"))!=2){
    SalirApp();
  }
}
userActive();

document.getElementById("mjsBienvenida").innerHTML="BIENVENIDO : "+sessionStorage.getItem("colaborador");
document.getElementById("tk_user").innerHTML=sessionStorage.getItem("usuario");
//var baseUrl="http://159.65.165.175/stockfar/ws/";
var baseUrl="http://localhost/condominio/ws/";
$(document).ready(function () {
  $('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
    $(this).toggleClass('active');
  });
  $('#sidebar ul li ul li a').on('click', function () {
    $('#sidebar ul li ul li a').removeClass('a_activo')
    $(this).addClass('a_activo');
  });
  $.ajaxSetup(    
    {
    cache: false,  
    headers: {'Authorization':sessionStorage.getItem("tk")}
    }
  );
});

function SalirApp(){  
  sessionStorage.clear();
  window.location.href = "../../";
}

function cargar_vista(pagina)
{
  document.getElementById("modal_formulario").innerHTML="";
  document.getElementById("modal_mensaje").innerHTML="";
  if ($('#modal_formulario').hasClass('in')) {
    $('#modal_formulario').modal('toggle');   
  }
  if ($('#modal_mensaje').hasClass('in')) {
    $('#modal_mensaje').modal('toggle');   
  }
  //document.getElementById("contenido").innerHTML="<center><img src='../img/cargando.gif'></center>";
  $.get(pagina, function(htmlexterno){ $("#contenido").html(htmlexterno); });
}

function formValido(formId){
  let valido=true;
  let form=document.getElementById(formId);
  for(let i=0; i < form.elements.length; i++){
    form.elements[i].style.borderColor=''; 
    form.elements[i].title='';        
    if(form.elements[i].value === '' && form.elements[i].hasAttribute('required')){
      form.elements[i].style.borderColor='red';   
      let msj=form.elements[i].placeholder;     
      form.elements[i].title='*COMPLETAR : '+msj;
      form.elements[i].placeholder='*COMPLETAR : '+msj;        
      valido=false; 
    }
  }
  return valido;
}

function modalRespuestaOk(header,content,long){
  modal=`<div class="modal-dialog `+long+`">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">`+header+`</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">`+content+`
    </div>
    </div>
    </div>
  </div>`;
  return modal;
}

function modalCargando(){  
  console.log("Carga");          
  modal=`
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div style="text-align: center;color: blue;">  
      <img src="../../img/ajax-loader.gif">  
      <h5>Cargando..!</h5>              
    </div>
  </div>`;
  $('#modal_cargando').html(modal);
  $('#modal_cargando').modal('toggle');  
}

function modalRespuestaError(header,content,long){
  modal=`<div class="modal-dialog `+long+`">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">`+header+`</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">`+content+`
    </div>
    </div>
    </div>
  </div>`;
  return modal;
}

function screenAltura(id,reducir)
{
  var altura=window.screen.height-reducir;
  document.getElementById(id).style.height =altura+"px";
}

function BuscarFilaTabla(tablaId,inputId){
  let tableReg = document.getElementById(tablaId);
  let searchText = document.getElementById(inputId).value.toLowerCase();
  let cellsOfRow="";
  let found=false;
  let compareWith="";
  // Recorremos todas las filas con contenido de la tabla
  for (let i = 1; i < tableReg.rows.length; i++)
  {
    cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
    found = false;
    // Recorremos todas las celdas
    for (let j = 0; j < cellsOfRow.length && !found; j++)
    {
      compareWith = cellsOfRow[j].innerHTML.toLowerCase();
      // Buscamos el texto en el contenido de la celda
      if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1))
      {
        found = true;
      }
    }
    if(found)
    {
      tableReg.rows[i].style.display = '';
    } else {
      // si no ha encontrado ninguna coincidencia, esconde la
      // fila de la tabla
      tableReg.rows[i].style.display = 'none';
    }
  }
}

function fechaActual(){
	let d = new Date();    
  let dia=(d.getDate()<10?'0':'') + d.getDate();   
  let mes=(d.getMonth()<9?'0':'') + (d.getMonth()+1);   
  let anio=d.getFullYear();       
  let fecha=dia+'/'+mes+'/'+anio;    
  return fecha;
} 

function fechaHoraActual(){
	let d = new Date();    
    let dia=(d.getDate()<10?'0':'') + d.getDate();   
    let mes=(d.getMonth()<9?'0':'') + (d.getMonth()+1);   
    let anio=d.getFullYear();   
    let hora=(d.getHours()<10?'0':'') + d.getHours();   
    let minuto=(d.getMinutes()<10?'0':'') + d.getMinutes();   
    let fechaHora=dia+'/'+mes+'/'+anio+' '+hora+":"+minuto;    
    return fechaHora;
}

function clearInputsForm(idInput){
  inputs=$("#"+idInput);
  for (x in inputs)
  {
    inputs[x].value="";
  }
}

function dataLocalEdit(local,obj){
  Object.keys(obj).forEach(function(key) {    
    local[obj['id']][key.toUpperCase()]=obj[key];
  }); 
}

function datatableRowEdit(id,arreglo){
  let fila=document.getElementById("f-"+id);       
  let columna=fila.querySelectorAll("td");  
  for (var i = 0; i < arreglo.length; i++) {
    columna[i].innerHTML=arreglo[i];
  }
}

function arrayToObj(datos){
  let data=[];
  for(x in datos){        
    data[datos[x].ID]=datos[x];
  }
  return data;
}

function objForm(id)
{ 
  let data_json={}
  let data=$("#"+id).serializeArray();  
  for (x in data){    
    data_json[data[x].name]=data[x].value
  }
  let selects=$("#"+id+" select");
  for (var i = 0; i < selects.length; i++) {      
    data_json[selects[i].getAttribute('nameSelect')]=selects[i].selectedOptions[0].text
  }
  return data_json;
}

function usuarioResetearClave(){  
  let id=sessionStorage.getItem("id");
  let modal=modalUsuarioCambiarClave(id);  
  $('#modal_formulario').html(modal);
  $('#modal_formulario').modal('toggle'); 
}

function modalUsuarioCambiarClave(id){
  let modal=`<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cambiar Clave</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="form_usuario_cambiar_clave">
          <input type="hidden" name="id" value="`+id+`">          
          <div class="form-group">
            <label for="password">Nueva Contraseña *</label>
            <input required type="password" class="form-control" id="password" 
              name="password">
          </div>
          <div class="form-group">
            <label for="passwordnew">Nueva Contraseña *</label>
            <input required type="password" class="form-control" id="passwordnew" 
              name="passwordnew" >
          </div>
        </form>
        
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-success" onclick="modalUsuarioCambiarClaveGuardar()">Si</button>       
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>        
      </div>
    </div>
    </div>`;
  return modal;
}

function modalUsuarioCambiarClaveGuardar(){
  if (formValido("form_usuario_cambiar_clave")){
    let pwd=document.getElementById("password").value;
    let pwdnew=document.getElementById("passwordnew").value;
    if(pwd==pwdnew){
      let data_json=objForm("form_usuario_cambiar_clave");    
      console.log(data_json);  
      modalCargando();
      $.post(baseUrl+"usuario/cambiar",data_json,function(data){      
        let response=JSON.parse(data);      
        $('#modal_cargando').modal('toggle'); 
        if (response.estado){        
          $('#modal_formulario').html("");
          $('#modal_formulario').modal('toggle');      
          let modal_conten_loader=modalRespuestaOk('Correcto',response.mensaje,'');        
          $('#modal_mensaje').html(modal_conten_loader);
          $('#modal_mensaje').modal('toggle');        
        }else{        
          let modal_conten_loader=modalRespuestaError('Error',response.mensaje,'');        
          $('#modal_mensaje').html(modal_conten_loader);
          $('#modal_mensaje').modal('toggle');      
        }
      }); 
    }else{
      alert("Las Contraseñas Ingresadas No son Iguales")
      pwd.value="";
      pwdnew.value="";
    }  
    
  } 
}

function tableLoader(idTableBody,col,img){
  $("#"+idTableBody).html('<td colspan="'+col+'" class="table-loader-td"><img class="table-loader" src="../../img/cargando_modal.gif" /><td>');
}
/*FUNCIONES A MODIFICAR*/
function loadSelectNew(idSelect,datos){ 
  let select='';   
  for(x in datos){    
    select+='<option value="'+datos[x].ID+'">'+datos[x].NOMBRE+'</option>';
  }
  document.getElementById(idSelect).innerHTML=select;
}

function loadSelectSearch(idSelect,datos){ 
  let select='<option value="">Todos</option>';   
  for(x in datos){    
    select+='<option value="'+datos[x].ID+'">'+datos[x].NOMBRE+'</option>';
  }
  document.getElementById(idSelect).innerHTML=select;
}

function loadSelectString(datos){ 
  let select='';   
  for(x in datos){    
    select+='<option value="'+datos[x].ID+'">'+datos[x].NOMBRE+'</option>';
  }
  return select;
}

function loadListNew(idList,datos){    
  let list='';
  for(x in datos){            
    list+='<option codigo="'+datos[x].ID+'" value="'+datos[x].NOMBRE+'"/>';       
  }    
  document.getElementById(idList).innerHTML=list;
}

function modalLoadSelect(id,datosArray){  
  let select='';
  for(x in datosArray){    
    if(datosArray[x].ID==id){
      select+='<option selected value="'+datosArray[x].ID+'">'+datosArray[x].NOMBRE+'</option>';
    }else{
      select+='<option value="'+datosArray[x].ID+'">'+datosArray[x].NOMBRE+'</option>';
    }    
  }
  return select;
}

function modalLoadList(idList,datos,id,valor){
  document.getElementById(idList).innerHTML="";
  let list='<option codigo="'+id+'" value="'+valor+'"/>';
  for(x in datos){            
    list+='<option codigo="'+datos[x].ID+'" value="'+datos[x].NOMBRE+'"/>';       
  }    
  document.getElementById(idList).innerHTML=list;
}
</script>