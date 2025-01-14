<?php
session_start();
include('conexion.php');
function generarFolioPago($conn) // Generar folio de pago
{
    // Consulta el último número de control del año actual
    $queryFolioPago = "SELECT fo FROM pagos WHERE fo LIKE 'PAUIO%' ORDER BY fo DESC LIMIT 1";
    $result = $conn->query($queryFolioPago);
    if ($result && $row = $result->fetch_assoc()) {
        $ultimoFolioPago = intval(substr($row['fo'], 6)) + 1; // Extrae la numeración del último número de control registrado y la incrementa
    } else {
        $ultimoFolioPago = 1; // Comienza desde 0001 si no hay registros previos
    }
    $folioPago = 'PAUIO' . str_pad($ultimoFolioPago, 4, '0', STR_PAD_LEFT); // Formatear el número con ceros a la izquierda y añadir el prefijo del año
    return $folioPago;
}
$folioPago = generarFolioPago($conn);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styleModales.css">
    <script>
        var userRole = "<?php echo $_SESSION['rol']; ?>";
    </script>
</head>

<body>
    <div class="">
        <div class="contenido-principal">
            <!-- Encabezado -->
            <div class="superior">
                <button class="boton-accion" onclick="abrirModalRegistrar()">Registrar Pago</button>
                <div>
                    <h2>Registro de Pagos</h2>
                </div>
                <div>
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
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Alumno</th>
                            <th>Pago</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Importe</th>
                            <th>Total</th>
                            <th>Forma de Pago</th>
                            <?php if ($_SESSION['rol'] == 'Rector'): ?>
                                <th></th>
                            <?php endif; ?>
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
    <!-- Modal Registrar Pagos -->
    <div id="registrar" class="modal">
        <div class="modal-content show">
            <span class="close" onclick="cerrarModalRegistrar()">&times;</span>
            <h1 class="modal-title">FICHA DE PAGO</h1>
            <form id="formRegistrarPago" enctype="multipart/form-data">
                <div id="documentosContainer" class="form-group">
                    <!-- DATOS DEL PAGO -->
                    <fieldset>
                        <legend class="modal-title">Datos Generales</legend>
                        <div class="input-group">
                            <div class="input-item">
                                <label>FOLIO DE PAGO</label>
                                <input type="text" name="fo" class="input-field" value="<?php echo $folioPago ?>" readonly>
                            </div>
                            <div class="input-item">
                                <label>FECHA</label>
                                <input type="text" name="fe" class="input-field" value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-item">
                                <label>NIVEL EDUCATIVO</label>
                                <select name="nivel" id="nivel" class="input-field" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    // Consulta para obtener los niveles educativos
                                    $consultaNiveles = "SELECT cv, ni FROM niveles";
                                    $resultadoNiveles = $conn->query($consultaNiveles);
                                    if ($resultadoNiveles && $resultadoNiveles->num_rows > 0) {
                                        while ($fila = $resultadoNiveles->fetch_assoc()) {
                                            $cv = htmlspecialchars($fila['cv'], ENT_QUOTES, 'UTF-8');
                                            $nivel = htmlspecialchars($fila['ni'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value='$cv'>$nivel</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay niveles disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-item">
                                <label>CARRERA</label>
                                <select name="carrera" id="carrera" class="input-field" required>
                                    <option>Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-item">
                                <label>ALUMNO</label>
                                <select name="nC" id="nC" class="input-field" required>
                                    <option>Seleccione</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <!-- DESCRIPCIÓN -->
                    <fieldset>
                        <legend class="modal-title">Descripción del Pago</legend>
                        <div class="input-group">
                            <div class="input-item">
                                <label>TIPO DE PAGO</label>
                                <select name="tipoPago" id="tipoPago" class="input-field" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $consultaTipoPago = "SELECT cv, tipo FROM tipopago";
                                    $resultadoTipoPago = $conn->query($consultaTipoPago);
                                    if ($resultadoTipoPago && $resultadoTipoPago->num_rows > 0) {
                                        while ($fila = $resultadoTipoPago->fetch_assoc()) {
                                            $cT = htmlspecialchars($fila['cv'], ENT_QUOTES, 'UTF-8');
                                            $tipoPago = htmlspecialchars($fila['tipo'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value='$cT'>$tipoPago</option>";
                                        }
                                    } else {
                                        echo "<option>No hay tipos de pago disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-item">
                                <label>DESCRIPCIÓN</label>
                                <input type="text" name="de" class="input-field" placeholder="Ingrese la descripción del pago" onkeyup="mayus(this);" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-item">
                                <label>CANTIDAD</label>
                                <input type="number" name="ca" id="ca" class="input-field" value="1" required>
                            </div>
                            <div class="input-item">
                                <label>IMPORTE</label>
                                <input type="number" name="im" id="im" class="input-field" step="0.01" placeholder="Ingrese el importe" required>
                                <!-- <input type="text" name="im" id="im" class="input-field" placeholder="Ingrese el importe" required> -->
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-item">
                                <label>TOTAL</label>
                                <input type="number" name="tot" id="tot" class="input-field" value="0.00" readonly>
                            </div>
                            <div class="input-item">
                                <label>FORMA DE PAGO</label>
                                <select name="formaPago" id="formaPago" class="input-field" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $consultaFormaPago = "SELECT cv, forma FROM formaPago";
                                    $resultadoFormaPago = $conn->query($consultaFormaPago);
                                    if ($resultadoFormaPago && $resultadoFormaPago->num_rows > 0) {
                                        while ($fila = $resultadoFormaPago->fetch_assoc()) {
                                            $cF = htmlspecialchars($fila['cv'], ENT_QUOTES, 'UTF-8');
                                            $formaPago = htmlspecialchars($fila['forma'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value='$cF'>$formaPago</option>";
                                        }
                                    } else {
                                        echo "<option>No hay formas de pago disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <div class="button-group">
                        <button type="button" class="boton-accion" id="btnRegistrarPago">Registrar Pago</button>
                    </div>
                </div>
            </form>
            <div id="mensaje"></div>
        </div>
    </div>
    <!-- Modal Modificar Pago -->
    <div id="modificar" class="modal">
        <div class="modal-content show">
            <span class="close" onclick="cerrarModalModificar()">&times;</span>
            <h1 class="modal-title">MODIFICAR FICHA DE PAGO</h1>
            <form id="formModificar" enctype="multipart/form-data">
                <div id="documentosContainer" class="form-group">
                    <!-- DATOS DEL PAGO -->
                    <fieldset>
                        <legend class="modal-title">Datos Generales</legend>
                        <div class="input-group">
                            <div class="input-item">
                                <input type="hidden" name="cNMod" id="cNMod">
                                <input type="hidden" name="cCMod" id="cCMod">
                                <label>FOLIO</label>
                                <input type="text" name="foMod" id="foMod" class="input-field" title="No se puede modificar" readonly>
                            </div>
                            <div class="input-item">
                                <label>FECHA</label>
                                <input type="text" name="feMod" id="feMod" class="input-field" title="No se puede modificar" readonly>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-item">
                                <input type="hidden" name="nCA" id="nCA">
                                <label>ALUMNO</label>
                                <input name="alumno" id="alumno" class="input-field" title="No se puede modificar" readonly>
                            </div>
                        </div>
                    </fieldset>
                    <!-- DESCRIPCIÓN -->
                    <fieldset>
                        <legend class="modal-title">Descripción del Pago</legend>
                        <div class="input-group">
                            <div class="input-item">
                                <input type="hidden" name="tpa" id="tpa">
                                <label>TIPO DE PAGO</label>
                                <input type="text" name="tipoPagoMod" id="tipoPagoMod" class="input-field" title="No se puede modificar" readonly>
                            </div>
                            <div class="input-item">
                                <label>DESCRIPCIÓN</label>
                                <input type="text" name="deMod" id="deMod" class="input-field" placeholder="Ingrese la descripción del pago" onkeyup="mayus(this);" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-item">
                                <label>CANTIDAD</label>
                                <input type="number" name="caMod" id="caMod" class="input-field" value="1" required>
                            </div>
                            <div class="input-item">
                                <input type="hidden" name="imA" id="imA">
                                <label>IMPORTE</label>
                                <input type="number" name="imMod" id="imMod" class="input-field" step="0.01" placeholder="Ingrese el importe" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-item">
                                <label>TOTAL</label>
                                <input type="number" name="totMod" id="totMod" class="input-field" value="0.00" readonly>
                            </div>
                            <div class="input-item">
                                <label>FORMA DE PAGO</label>
                                <select name="formaPagoMod" id="formaPagoMod" class="input-field" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $consultaFormaPago = "SELECT cv, forma FROM formaPago";
                                    $resultadoFormaPago = $conn->query($consultaFormaPago);
                                    if ($resultadoFormaPago && $resultadoFormaPago->num_rows > 0) {
                                        while ($fila = $resultadoFormaPago->fetch_assoc()) {
                                            $cF = htmlspecialchars($fila['cv'], ENT_QUOTES, 'UTF-8');
                                            $formaPago = htmlspecialchars($fila['forma'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value='$cF'>$formaPago</option>";
                                        }
                                    } else {
                                        echo "<option>No hay formas de pago disponibles</option>";
                                    }
                                    ?>
                                </select>
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
    <script src="modales/pagos/script.js"></script>
    <script src="modulos/script.js"></script>
</body>

</html>