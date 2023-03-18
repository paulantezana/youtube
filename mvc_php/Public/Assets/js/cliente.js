async function clienteList() {
    let reposense = await  fetch('http://localhost:82/mvc_php/public/cliente/table');
    let reposenseData = await reposense.json();
    
    if(reposenseData.success){
        const clienteTableBody = document.getElementById('clienteTableBody');
        clienteTableBody.innerHTML = '';
        
        reposenseData.result.forEach(item => {
            clienteTableBody.insertAdjacentHTML('beforeend', `<tr>
            <td>${item.nombres}</td>
            <td>${item.apellidos}</td>
            <td>${item.direccion}</td>
            <td>
                <a href="http://localhost:82/mvc_php/public/cliente/edit/?id=${item.id}">
                <button>Editar</button>
                </a>
                <button onclick="eliminarCliente(${item.id})">Eliminar</button>
            </td>
            </tr>`)
        });
    }
}

clienteList();

async function eliminarCliente(id){
    let reposense = await  fetch('http://localhost:82/mvc_php/public/cliente/delete',{
        method: 'DELETE',
        body: JSON.stringify({ id }),
    });
    let reposenseData = await reposense.json();
    console.log(reposenseData);
    clienteList();
}