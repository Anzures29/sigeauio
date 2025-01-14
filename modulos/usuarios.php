<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styleModales.css">
</head>

<body>
    <div class="">
        <div class="contenido-principal">
            <!-- Encabezado -->
            <div class="superiorSolo">
                <div>
                    <h2>Relación de Usuarios y Empleados</h2>
                </div>
            </div>
            <!-- Filtros -->
            <div class="filtros">
                <!-- Filtro para seleccionar la cantidad de registros a mostrar -->
                <div class="filtro-registros">
                    <label for="registros">Mostrar</label>
                    <select id="registros" class="selector-filtro">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <label>registros</label>
                </div>
                <!-- Buscador de registros -->
                <input type="text" id="buscar" placeholder="Buscar..." class="input-busqueda">
            </div>
            <!-- Tabla de datos -->
            <div class="tabla-responsive">
                <table class="tabla tabla-strip tabla-hover tabla-bordeada">
                    <thead class="tabla-oscura">
                        <tr>
                            <th>Usuario</th>
                            <th>Puesto</th>
                            <th>Empleado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tablaUsuarios">
                        <!-- Aquí se cargan los datos -->
                    </tbody>
                </table>
            </div>
            <!-- Botones de paginación -->
            <div class="paginacion">
                <div id="cantidad-registros" style="margin-top: 10px;"></div>
                <div>
                    <button id="anterior" class="boton-accion">Anterior</button>
                    <span id="pageInfo"></span>
                    <button id="siguiente" class="boton-accion">Siguiente</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Modificar Usuario -->
    <div id="modificar" class="modal">
        <div class="modal-content show">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h1 class="modal-title">MODIFICAR USUARIO Y CONTRASEÑA</h1>
            <form id="formModificar" enctype="multipart/form-data">
                <div id="" class="form-group">
                    <!-- DATOS DEL EMPLEADO -->
                    <fieldset>
                        <legend class="modal-title">Ingrese las nuevas credenciales</legend>
                        <input type="hidden" id="cvUsu" name="cvUsu">
                        <input type="hidden" id="empleado" name="empleado">
                        <input type="hidden" id="em" name="em">
                        <div class="input-group">
                            <div class="input-item">
                                <label>NUEVO USUARIO</label>
                                <input type="text" name="usMod" id="usMod" class="input-field" placeholder="Ingrese el nuevo usuario" required>
                            </div>
                            <div class="input-item">
                                <label>NUEVA CONTRASEÑA</label>
                                <input type="password" name="coMod" id="coMod" class="input-field" placeholder="Ingrese la nueva contraseña" required>
                            </div>
                        </div>
                    </fieldset>
                    <div class="button-group">
                        <button type="button" class="boton-accion" id="btnModificar">Guardar Cambios</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Script para manejar el modal y la tabla con AJAX -->
    <script src="modales/usuarios/script.js"></script>

</body>

</html>