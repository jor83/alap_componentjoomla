<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$nome = JFactory::getUser()->name;
$usuario = JFactory::getUser()->username;
$fecha = time ();
//
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() .  'components/com_zvcuotas/assets/js/jquery-1.12.0.min.js');
$doc->addScript(JUri::base() .  'components/com_zvcuotas/assets/js/cuotasusuarios.js');

?>

<div id="divformularioPrincipal" >
    <form id="formulario" name="formulario" action="<?php echo JRoute::_('index.php?option=com_zvcuotas&view=cuotasusuarios&layout=ipagare');?>" method="post">
        <!-- Comienzo de pagos anteriores-->
        <br>
        <p>Estimado(a) <b><?php echo $nome.' ('.$usuario.')'; ?></b>, en <?php echo date( "j/m/Y" , $fecha ); ?> sus datos de Cuota en la Asociaci&oacute;n Latino Americana de Poblaci&oacute;n son:</p>

        <h4>Su a&ntilde;o de afiliaci&oacute;n es: <?php //echo $this->anioAfiliacion;?></h4>
        <p>Para pagar su cuota puede utilizar la opci&oacute;n de pago en linea con tarjeta de credito
            o utilizar la opci&oacute;n de formulario via fax
            (las instrucciones se encuentra al hacer clic en este boton).

            Para pagar su cuota puede utilizar la opci&oacute;n de pago en l&iacute;nea con tarjeta
            de cr&eacute;dito o utilizar la opci&oacute;n de formulario v&iacute;a fax
            (las instrucciones se encuentran al hacer clic en este bot&oacute;n).
        </p>
        <?php if(!empty($this->cuotasPagas)){?>
        <h4>Sus Pagos anteriores son:</h4>
        <table border="1" id="table1" width="95%" cellpadding="3" cellspacing="0">
            <tr>
                <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%" bgcolor="#4f4b32">Fecha</td>
                <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%">A&ntilde;o</td>
                <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%">Valor</td>
                <td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="40%">Concepto</td>
            </tr>
            <?php foreach($this->cuotasPagas as $cp){;?>
            <tr>
                <td class="contenido_tablas" align="center"><?php echo $cp->fecha_pago;?></td>
                <td class="contenido_tablas" align="center"><?php echo ' '.$cp->anio; ?></td>
                <td class="contenido_tablas"align="center"><?php echo $cp->moneda;?> <?php echo $cp->monto;?></td>
                <td class="contenido_tablas" width="40%"><?php echo $cp->concepto;?></td>
            </tr>
            <?php };?>
        </table>
        <!-- Fin de pagos anteriores-->
            <?php };?>

        <h4>Los valores debidos de su cuota son:</h4>
            <table border="1" id="table3"  width="95%" cellpadding="3" cellspacing="0">
                <?php
                $x=0;

                foreach ($this->cuotasDisponiveis as $adeuda) {
                @$total += $adeuda->monto;
                $x++;
                $idd[$x]['anio']= $adeuda->anio;
                $idd[$x]['monto']= $adeuda->monto.'|'.$adeuda->moneda;
                $idd[$x]['id_pago']= $adeuda->id_pago;
                echo '<input type="hidden" name="descricao_item_'.($x).'" value="Anio: '.$adeuda->anio.'|'.$idd[$x]["monto"].'|'.$adeuda->id_pago.'|Cotacao:'.$this->cotacaoDolar.' ">';
                ?>
                <tr>
                    <td class="titulos_tablas" bgcolor="#f0ede7" align="center">Valor en Dolares:</td>
                    <td class="titulos_tablas"  align="center">U$D <input type="text" id="txtValorAnio<?php echo $adeuda->id_pago;?>" name="txtValorAnio<?php echo $adeuda->id_pago;?>" size="6" readonly style="border: 0px;" value="<?php echo $adeuda->monto ?>"></td>
                    <td class="titulos_tablas" bgcolor="#f0ede7" align="center">A&ntilde;o <?php echo $adeuda->anio;?></td>
                    <td class="titulos_tablas"  width="30%"><?php echo $adeuda->concepto;?></td>
                </tr>
                <?php
                }
                //echo '<pre>';
                //var_dump($idd);
                //echo '</pre>';
                ?>
            </table>

            <p align="center" class="destacado"><strong>Total: </strong><?php echo number_format($total,2,'.',',');?>
                <input type="hidden" name="txtTotalUSD" id="txtTotalUSD" size="3" value="<?php echo number_format($total,2,'.',',');?>" style="border: 0px;" readonly>
                <input type="hidden" name="txtTotalReales" id="txtTotalReales" size="3" value="0" style="border: 0px;" readonly>
                , <strong>en U$D, correspondientes a </strong><?php echo number_format(($total * $this->cotacaoDolar),2,'.',',');?><strong> en reales R$*.</strong>
            </p>

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
               <table border="1" id="table2" width="35%" cellpadding="3" cellspacing="0" align="center">
                    <tr>
                        <td align="right">En dolares (U$D): </td>
                        <td><strong><?php echo number_format($total,2,'.',',');?></strong></td>
                    </tr>
                    <tr>
                        <td align="right"  class="titulos_tablas" bgcolor="#f0ede7">En Real (R$)*: </td>
                        <td><strong><?php echo number_format(($total * $this->cotacaoDolar),2,'.',',');?></strong></td>
                    </tr>
                </table>

            <div id="divMetodoPago">
                <blockquote>
                    <blockquote>
                        <p>Seleccione m&eacute;todo de pago:</p>
                    </blockquote>
                    <p align="center">
                        <?php //if ($this->idArticuloTransferencia != 0){?>
                            <!--<input type="button" value="Transferencia Bancaria" name="B3" onclick="showTransferenciaBancaria();">-->
                        <?php //}?>
                            <input type="submit" value="Pago en linea (Tarjetas y otros)" name="B4" >
                        <?php //if ($this->idArticuloFax != 0){?>
                            <!--<input type="button" value="Formulario Visa (via Fax)" name="B5" onclick="showFax();">-->
                        <? //}?>

                        <br><br>
                        Si tiene alguna duda:<br>
                        <a href="images/stories/InstructivoPagosOnline.pdf" target="_blank">
                            <img src="images/stories/descargueinstructivo.png" border="0">
                        </a>
                    </p>
                </blockquote>
            </div>

            <p align="center">*Cotizaci&oacute;n: 1 U$D =
            <Strong><?php echo $this->cotacaoDolar;?></Strong>
                <input type="hidden" name="zvcotizacion" id="zvcotizacion" size="3" value="<?php echo $this->cotacaoDolar;?>" > R$ </p>
                <input type="hidden" name="valor_total" id="valor_total" size="3" value="<?php echo number_format(($total * $this->cotacaoDolar),2,'','');?>" >
                <input type="hidden" name="numero_itens" id="numero_itens" value="<?php echo count($idd);?>" >
                <input type="hidden" name="dolar" id="dolar" value="<?php echo $this->cotacaoDolar;?>" >

    </form>
<!--
    <div id="divPagoOnline" style="display:none">
        <h4>Pago Online</h4>
        <iframe id="ipagareIframe" name="ipagareIframe" src ="" width="100%" height="500" border="0" style="border: 0px">
            <p>Your browser does not support iframes.</p>
        </iframe>
    </div>
-->
</div>