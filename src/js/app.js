let paso =1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: [] 
}
document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
})
function iniciarApp(){
    mostrarSeccion();
    tabs(); //cambia la seccion cuando se presionen los tabs
    botonesPaginador(); //agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();
    consultarAPI(); //consulta la api en el backend de php
    idCliente();
    nombreCliente();// añade el nombre de cliente al objeto de cita
    seleccionarFecha(); // añade la fecha de la cita en el objeto
    seleccionarHora(); //añade la hora de la cita en el objeto 
    mostrarResumen();
}

function mostrarSeccion(){
    //ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){

        seccionAnterior.classList.remove('mostrar');

    }
    //seleccionar la seccion con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //oculta la clase anterior del tab
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    //resalta tab actual
    const tab = document.querySelector(`[data-paso = "${paso}"]`);
    tab.classList.add('actual');
}


function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
             
        });
    });
}
function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');
    if(paso===1){
         paginaAnterior.classList.add('ocultar');
         paginaSiguiente.classList.remove('ocultar');
    }else if(paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }
    else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
    mostrarSeccion();
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        if(paso>= pasoFinal) return;
        paso++;
        botonesPaginador();
    })
}
function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        if(paso<= pasoInicial) return;
        paso--;
        botonesPaginador();
    })
}
async function consultarAPI(){
    try {
        const url = 'http://localhost:3000/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios); 
    }catch(error){
        console.log(error);
    }
}
function mostrarServicios(servicios){
    servicios.forEach(servicio =>{
        const {id, nombre, precio} = servicio;
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;
        
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio =id;
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv); 
    });
}
function seleccionarServicio(servicio){
    const {id} =servicio;
    const {servicios} = cita;
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //comprobar si servicio ya fue agregado

    if(servicios.some(agregado => agregado.id === id)){
        cita.servicios =servicios.filter(agregado =>agregado.id !== id);
        divServicio.classList.remove('seleccionado'); 

    }else{
        cita.servicios =[... servicios, servicio];
        divServicio.classList.add('seleccionado'); 

    }
}

function idCliente(){
    cita.id = document.querySelector('#id').value;
}

function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value;

}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e){
        const dia  = new Date(e.target.value).getUTCDay();
        if([6, 0].includes(dia)){
            e.target.value='';
            mostrarAlerta('cerrados los fines de semana', 'error', '.formulario');
        }else{
            cita.fecha = e.target.value; 
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
    const alertaPrevia =document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }; 
    const alerta= document.createElement('DIV');
    alerta.textContent = mensaje; 
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);
    if(desaparece){
        setTimeout(() =>{
            alerta.remove();
        }, 3000);
    }
}
function seleccionarHora(){
     const inputHora = document.querySelector('#hora');
     inputHora.addEventListener('input',function(e){
        const horaCita = e.target.value;
        const hora =  horaCita.split(":")[0];
        if(hora<10 || hora >18){
            mostrarAlerta('horario no valido','error', '.formulario ');
        }else{
            cita.hora = e.target.value;
        }
     });
}
function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');
    //limpiar el contenido resiumen
    while( resumen.firstChild){
         resumen.removeChild(resumen.firstChild );
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0 ){
        mostrarAlerta('Faltan datos', 'error','.contenido-resumen',false);
        return;
    }
    //formatear el div de resumen 
     const {nombre, fecha, hora, servicios} = cita;
     const nombreCliente = document.createElement('P');
     nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    //formatear fecha
    const fechaOBJ = new Date(fecha);
    const mes = fechaOBJ.getMonth();
    const dia = fechaOBJ.getDate() + 2;
    const year = fechaOBJ.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes , dia));

    const opciones = {weekday:'long', year:'numeric', month:'long', day: 'numeric'};

    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR',opciones);

     const fechaCita = document.createElement('P');
     fechaCita.innerHTML = `<span>Fecha: </span>${fechaFormateada}`;


     const horaCita = document.createElement('P');
     horaCita.innerHTML = `<span>hora: </span>${hora} hs`;

     //heading para serivicios en resumen
    const headngServicios = document.createElement('H3');
    headngServicios.textContent = 'Resumen de servicios';
    resumen.appendChild(headngServicios);


    //iterando y mostrando servicios
    servicios.forEach(servicio =>{
        const {id, precio, nombre} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>precio:</span> $${precio}`;
        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        resumen.appendChild(contenedorServicio);
    })
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de cita';
    resumen.appendChild(headingCita);

    //boton para crear cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = "Reservar";
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita); 

    resumen.appendChild(botonReservar);
}
async function reservarCita(){
    const {id, fecha, hora, servicios} = cita;
    const idServicio = servicios.map(servicio => servicio.id);

    const datos = new FormData();
    datos.append('fecha',fecha);
    datos.append('hora',hora);
    datos.append('usuarioId',id);
    datos.append('servicios',idServicio);

try {
    //peticion hacia la api
    const url = 'http://localhost:3000/api/citas';
    const respuesta  = await fetch(url, {
        method: 'POST',
        body: datos
    });
    const resultado = await respuesta.json();
    if(resultado.resultado){
        Swal.fire({
          icon: 'success',
          title: 'Cita Creada',
          text: 'Tu cita fue creada correctamente',
          button: 'OK'
        }).then(()=>{
            window.location.reload();
        })
    }
     
} catch (error) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un error al guardar la cita',
      })
}
    //console.log([...datos]);
}