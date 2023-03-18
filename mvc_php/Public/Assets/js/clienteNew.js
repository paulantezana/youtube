const clienteFormulario = document.getElementById('clienteFormulario');
clienteFormulario.addEventListener('submit', (e)=>{
    e.preventDefault();
    clienteFormularioSubmit();
});

async function clienteFormularioSubmit(){
    let cliente = {};
    cliente.nombres = document.getElementById('nombres').value;
    cliente.apellidos = document.getElementById('apellidos').value;
    cliente.direccion = document.getElementById('direccion').value;
    cliente.id = document.getElementById('id').value;

    let ruta = cliente.id > 0 ? 'update' : 'create';

    let reposense = await  fetch('http://localhost:82/mvc_php/public/cliente/' + ruta,{
        method: 'POST',
        body: JSON.stringify(cliente),
    });

    let reposenseData = await reposense.json();
    console.log(reposenseData);
}