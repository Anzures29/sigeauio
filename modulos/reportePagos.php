<?php
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
?>
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
            <div class="superior">
                <div></div>
                <div>
                    <h2>Reporte de Pagos</h2>
                </div>
                <div>
                    <button id="imprimirPdf" class="boton-accion">PDF</button>
                    <button id="imprimirExcel" class="boton-accion">EXCEL</button>
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
                    <label for="registros">registros</label>
                </div>
                <!-- Filtro por mes -->
                <div class="filtro-registros">
                    <label for="mes">Filtrar por mes</label>
                    <input type="month" id="mes" name="mes" value="<?php echo isset($_GET['mes']) ? $_GET['mes'] : date('Y-m'); ?>" class="form-control">
                </div>
                <!-- Buscador de registros -->
                <input type="text" id="buscar" placeholder="Buscar..." class="input-busqueda">
            </div>
            <!-- Tabla de datos -->
            <div class="tabla-responsive">
                <table class="tabla tabla-strip tabla-hover tabla-bordeada">
                    <thead class="tabla-oscura">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Alumno</th>
                            <th>Pago</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Importe</th>
                            <th>Total</th>
                            <th>Forma de Pago</th>
                        </tr>
                    </thead>
                    <tbody id="tablaPagos">
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

    <!-- Script para manejar el modal y la tabla con AJAX -->
    <script src="modales/reportePagos/script.js"></script>

</body>

</html>