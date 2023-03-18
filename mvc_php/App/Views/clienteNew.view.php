<form action="" id="clienteFormulario">
    <input type="hidden" name="" id="id" value="<?= $parameters['cliente']['id'] ?? '0' ?>">
    <div>
        <label for="nombres">Nombres</label>
        <input type="text" id="nombres" value="<?= $parameters['cliente']['nombres'] ?? '' ?>">
    </div>
    <div>
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" value="<?= $parameters['cliente']['apellidos'] ?? '' ?>">
    </div>
    <div>
        <label for="direccion">Direccion</label>
        <input type="text" id="direccion" value="<?= $parameters['cliente']['direccion'] ?? '' ?>">
    </div>
    <button type="submit">GUARDAR</button>
</form>

<script src="<?= URL_PATH ?>/Assets/js/clienteNew.js"></script>