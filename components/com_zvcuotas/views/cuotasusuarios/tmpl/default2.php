<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

?>

<div id="divformularioPrincipal" >
    <form id="formulario" name="formulario" action="" >
        <?//$fecha = time ();?>
        <!-- Comienzo de pagos anteriores-->
        <br>
        <p>Estimado(a) <b><?//echo $this->userNameCliente; ?></b>, en <?//echo date( "j/m/Y" , $fecha ); ?> sus datos de Cuota en la Asociaci&oacute;n Latino Americana de Poblaci&oacute;n son:</p>
        <br>

        <h4>Su a&ntilde;o de afiliaci&oacute;n es: <?php //echo $this->anioAfiliacion;?></h4>
        <p>Para pagar su cuota puede utilizar la opci&oacute;n de pago en linea con tarjeta de credito
            o utilizar la opci&oacute;n de formulario via fax
            (las instrucciones se encuentra al hacer clic en este boton).

            Para pagar su cuota puede utilizar la opci&oacute;n de pago en l&iacute;nea con tarjeta
            de cr&eacute;dito o utilizar la opci&oacute;n de formulario v&iacute;a fax
            (las instrucciones se encuentran al hacer clic en este bot&oacute;n).

        </p>
        <br/><br/>

        <?php //if (count($this->pagosAnteriores) > 0) {?>
            <h4>Sus Pagos anteriores son:</h4>
            <center>
                <table border="1" id="table1" width="95%" cellpadding="3" cellspacing="0">
                    <tr>
                        <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%" bgcolor="#4f4b32">Fecha</td>
                        <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%">A&ntilde;o</td>
                        <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%">Valor</td>
                        <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="40%">Concepto</td>
                    </tr>
                    <?php //foreach ($this->pagosAnteriores as $pago) {?>
                        <tr>
                            <td class="contenido_tablas" align="center"><?php //echo $pago->fecha_pago;?></td>
                            <td class="contenido_tablas" align="center"><?php //echo ' '.$pago->anio; ?></td>
                            <td class="contenido_tablas"align="center"><?php //echo $pago->moneda;?> <?php //echo $pago->monto;?></td>
                            <td class="contenido_tablas" width="40%"><?php //echo $pago->concepto;?></td>
                        </tr>
                    <?php //}?>
                </table>
            </center>
        <?php //}?>
        <!-- Fin de pagos anteriores-->

        <!-- Comienzo de cuotas adeudadas-->

        <?php
        //if (count($this->aniosAdeuda) > 0) {?>
            <h4>Los valores debidos de su cuota son:</h4>
            <center>
                <table border="1" id="table3"  width="95%" cellpadding="3" cellspacing="0">
                    <?php //foreach ($this->aniosAdeuda as $adeuda) {?>
                        <tr>
                            <td class="titulos_tablas" bgcolor="#f0ede7" align="center">Valor en Dolares:</td>
                            <td class="titulos_tablas"  align="center">U$D <input type="text" id="txtValorAnio<?php //echo $adeuda->id_pago;?>" name="txtValorAnio<?php //echo $adeuda->id_pago;?>" size="6" readonly style="border: 0px;" value="<?php //echo $adeuda->monto ?>"></td>
                            <td class="titulos_tablas" bgcolor="#f0ede7" align="center">A&ntilde;o <?php //echo $adeuda->anio;?></td>
                            <td class="titulos_tablas"  width="30%"><?php //echo $adeuda->concepto;?></td>
                        </tr>
                    <?php //}?>
                </table>
            </center>
        <br>
            <p align="center" class="destacado">TOTAL:
                <input type="text" name="txtTotalUSD" id="txtTotalUSD" size="3" value="0" style="border: 0px;" readonly>, en U$D, correspondientes a <input type="text" name="txtTotalReales" id="txtTotalReales" size="3" value="0" style="border: 0px;" readonly>en reales R$*.
            </p>
        <br>
            <p>

                Si eres estudiante, env&iacute;e la acreditaci&oacute;n (comprobaci&oacute;n) para
                <a href="mailto:alap.secretaria@alapop.org">alap.secretaria@alapop.org</a> y
                clic abajo para cambiar su valor debido de cuota:</p>
            <p align="center">Es estudiante?
                <input type="radio" id="cbEsEstudiante" name="cbEsEstudiante" value="si" onclick="ocultaDiv(0,'divAnioEstudiante'); getValorCuota();">si
                <input type="radio" id="cbEsEstudiante" name="cbEsEstudiante" value="no" onclick="ocultaDiv(1,'divAnioEstudiante'); getValorCuota();" checked>no
            </p>
            <div id="divAnioEstudiante" style="display:none">
                <h4>Marque el/los a&ntilde;o(s) para los cuales fue (es) estudiante con acreditacion:</h4>
                <div style="text-align: center">
                    <?php //foreach ($this->aniosAdeuda as $adeuda) {?>
                        <input type="checkbox" name="cbanio<?php //echo  $adeuda->id_pago;?>" id="cbanio<?php //echo  $adeuda->id_pago;?>" value="ON" onclick="getValorCuota();"><?php //echo  $adeuda->anio;?>
                    <?php //}?>
                </div>
            </div>
            <p><a name="gotoPago">&nbsp;</a></p>
            <h4>Total debido para pago en el a&ntilde;o corriente:</h4>
            <center>
                <table border="1" id="table2" width="35%" cellpadding="3" cellspacing="0">
                    <tr>
                        <td align="right"  class="titulos_tablas" bgcolor="#f0ede7">En dolares (U$D): </td>
                        <td><input type="text" name="txtTotalUSD2" id="txtTotalUSD2" size="6" value="0" style="border: 0px;" readonly></td>
                    </tr>
                    <tr>
                        <td align="right"  class="titulos_tablas" bgcolor="#f0ede7">En Real (R$)*: </td>
                        <td><input type="text" name="txtTotalReales2" id="txtTotalReales2" size="6" value="0" style="border: 0px;" readonly></td>
                    </tr>
                </table>
            </center>
            <div id="divMetodoPago">
                <blockquote>
                    <blockquote>
                        <p>Seleccione m&eacute;todo de pago:</p>
                    </blockquote>
                    <p align="center">
                        <?if //($this->idArticuloTransferencia != 0){?>
                            <input type="button" value="Transferencia Bancaria" name="B3" onclick="showTransferenciaBancaria();">
                        <?php }?>
                        <input type="button" value="Pago en linea (Tarjetas y otros)" name="B4"  onclick="showPagoOnline();">
                        <?if //($this->idArticuloFax != 0){?>
                            <input type="button" value="Formulario Visa (via Fax)" name="B5" onclick="showFax();">
                        <? }?>

                        <br><br>
                        Si tiene alguna duda:<br>
                        <a href="images/stories/InstructivoPagosOnline.pdf" target="_blank">
                            <img src="images/stories/descargueinstructivo.png" border="0">
                        </a>
                    </p>
                </blockquote>
            </div>
            <p align="center">*Cotizaci&oacute;n: 1 U$D =
                <input type="text" name="txtCotizacion" id="txtCotizacion" size="3" value="<?php //echo $this->cotizacion;?>" readonly style="border: 0px;"> R$ </p>

        <script>
                getValorCuota();
            </script>
        <?php }
        else
        {
        ?>
            <center>
                <br>
                <p style="font-color:green;">Ud. no adeuda cuotas.</p>
            </center>
        <?php }?>
        <!-- Fin de cuotas adeudadas-->

    </form>
</div>