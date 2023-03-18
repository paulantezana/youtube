async function clienteList() {
    let reposense = await  fetch(URL_PATH + '/cliente/table');
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
                <a href="${URL_PATH}/cliente/edit/?id=${item.id}">
                <button>Editar</button>
                </a>
                <button onclick="eliminarCliente(${item.id})">Eliminar</button>
            </td>
            </tr>`)
        });
    }
}

clienteList();

function eliminarCliente(id){
    BsModal.confirm({
        title: '¿Esta seguro de eliminar este registro?',
        onOk: async () => {
            let reposense = await  fetch(URL_PATH + '/cliente/delete',{
                method: 'DELETE',
                body: JSON.stringify({ id }),
            });
            let reposenseData = await reposense.json();
            if(reposenseData.success){
                BsModal.confirm({ title: reposenseData.message });
                clienteList();
            } else {
                BsModal.confirm({ title: reposenseData.message });
            }
        }
    })
}