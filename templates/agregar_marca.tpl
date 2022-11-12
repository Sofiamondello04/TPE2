<!-- formulario de alta de marca-->

{include file="header.tpl"}
<h3>{$titulo}</h3>

<form action="addBrand" method="POST" class="my-4">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>{$nombre}</label>
                <input name="nombre_marca" type="text" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <button type="submit" class="btn btn-primary mt-2">{$guardar}</button>
        </div>
    </div>
</form>
