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


//echo '<pre>';
//var_dump($this->cuotasDisponiveis);
//echo '</pre>';
header("Location: index.php?option=com_zvcuotas&view=cuotasusuarios&layout=default33&Itemid=763");

?>

<div id="divformularioPrincipal" >
    <form id="formulario" name="formulario" action="<?php echo JRoute::_('index.php?option=com_zvcuotas&view=cuotasusuarios&layout=ipagare');?>" method="post">
        <!-- Comienzo de pagos anteriores-->
        <input type="hidden" value="<?php echo $this->cotacaoDolar;?>" name="cotacao_dolar" id="cotacao_dolar">
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
        <?php
        }
        if(empty($this->cuotasDisponiveis)) {
            ?>
            <p><h4>Ud. no adeuda cuotas.</h4></p>
        <?php
        }else{
        ?>
        <h4>Los valores debidos de su cuota son:</h4>
        <table border="1" id="table3" width="95%" cellpadding="3" cellspacing="0">
            <?php
            $x = 0;

            foreach ($this->cuotasDisponiveis as $adeuda) {
                @$total += $adeuda->monto;
                $x++;
                $idd[$x]['anio'] = $adeuda->anio;
                $idd[$x]['monto'] = $adeuda->monto . '|' . $adeuda->moneda;
                $idd[$x]['id_pago'] = $adeuda->id_pago;
                echo '<input type="hidden" id="descricao_item_' . ($x) . '"  name="descricao_item_' . ($x) . '" value="Anio: ' . $adeuda->anio . '|' . $idd[$x]["monto"] . '|' . $adeuda->id_pago . '|Cotacao:' . $this->cotacaoDolar . ' ">';
                ?>
                <tr>
                    <td class="titulos_tablas" bgcolor="#f0ede7" align="center">Valor en Dolares:</td>
                    <td class="titulos_tablas" align="center">U$D <input type="text"
                                                                         id="txtValorAnio<?php echo $adeuda->id_pago; ?>"
                                                                         name="txtValorAnio<?php echo $adeuda->id_pago; ?>"
                                                                         size="6" readonly style="border: 0px;"
                                                                         value="<?php echo $adeuda->monto ?>"></td>
                    <td class="titulos_tablas" bgcolor="#f0ede7" align="center">
                        A&ntilde;o <?php echo $adeuda->anio; ?></td>
                    <td class="titulos_tablas" width="30%"><?php echo $adeuda->concepto; ?></td>
                </tr>
            <?php
            }
            //echo '<pre>';
            //var_dump($idd);
            //echo '</pre>';
            ?>
        </table>

        <p align="center" class="destacado"><strong>Total:</strong><strong name="total2"></strong>
            <input type="text" name="txtTotalUSD" id="txtTotalUSD" size="3" value="<?php echo number_format($total, 2, '.', ',');?>" style="border: 0px; width: 50px; color: black; font-weight: bold;" readonly>
            <input type="hidden" name="txtTotalReales" id="txtTotalReales" size="3" value="0" style="border: 0px;"
                   readonly>
            , <strong>en U$D, correspondientes
                a </strong>
               <?php //echo number_format(($total * $this->cotacaoDolar), 2, '.', ',');?>
            <input type="text" name="total_real4" id="total_real4" size="3" value="<?php echo number_format(($total * $this->cotacaoDolar), 2, '.', ',');?>" style="border: 0px; width: 50px; color: black; font-weight: bold;" readonly>
                <strong> en reales
                R$*.</strong>
        </p>

        <p>
            Si eres estudiante, env&iacute;e la acreditaci&oacute;n (comprobaci&oacute;n) para
            <a href="mailto:alap.secretaria@alapop.org">alap.secretaria@alapop.org</a> y
            clic abajo para cambiar su valor debido de cuota:</p>

        <p align="center">Es estudiante?
            <input type="radio" id="cbEsEstudiante" name="cbEsEstudiante" value="si"
                   onclick="ocultaDiv(0,'divAnioEstudiante'); getValorCuota();">si
            <input type="radio" id="cbEsEstudiante" name="cbEsEstudiante" value="no"
                   onclick="ocultaDiv(1,'divAnioEstudiante'); getValorCuota();" checked>no
        </p>

        <div id="divAnioEstudiante" style="display:none">
            <h4>Marque el/los a&ntilde;o(s) para los cuales fue (es) estudiante con acreditacion:</h4>

            <div style="text-align: center">
                <?php foreach ($this->cuotasDisponiveis as $adeuda) {
                ?>
                <input type="checkbox" name="cbanio<?php echo  $adeuda->id_pago;?>" id="cbanio<?php echo  $adeuda->id_pago;?>" value="ON" onclick="getValorCuota();">
                <?php echo  $adeuda->anio;?>
                <?php }
                ?>
            </div>
        </div>

        <p><a name="gotoPago">&nbsp;</a></p>
        <h4>Total debido para pago en el a&ntilde;o corriente:</h4>
        <table border="1" id="table2" width="35%" cellpadding="3" cellspacing="0" align="center">
            <tr>
                <td align="right">En dolares (U$D):</td>
                <td>
                    <strong>
                        <input type="text" name="txtTotalUSD2" id="txtTotalUSD2" size="3" value="<?php echo number_format($total, 2, '.', ',');?>" style="border: 0px; width: 50px; color: black; font-weight: bold;" readonly>
                        <?php //echo number_format($total, 2, '.', ',');?>
                    </strong>
                </td>
            </tr>
            <tr>
                <td align="right" class="titulos_tablas" bgcolor="#f0ede7">En Real (R$)*:</td>
                <td><strong>
                        <input type="text" name="total_real3" id="total_real3" size="3" value="<?php echo number_format(($total * $this->cotacaoDolar), 2, '.', ',');?>" style="border: 0px; width: 50px; color: black; font-weight: bold;" readonly>
                        <?php //echo number_format(($total * $this->cotacaoDolar), 2, '.', ',');?>
                    </strong>
                </td>
            </tr>
        </table>

        <div id="divMetodoPago">
            <blockquote>
                <blockquote>
                    <p>Seleccione m&eacute;todo de pago:</p>
                </blockquote>
                <p align="center">
                    <?php //if ($this->idArticuloTransferencia != 0){
                    ?>
                    <!--<input type="button" value="Transferencia Bancaria" name="B3" onclick="showTransferenciaBancaria();">-->
                    <?php //}
                    ?>
                    <input type="submit" value="Pago en linea (Tarjetas y otros)" name="B4">
                    <?php //if ($this->idArticuloFax != 0){
                    ?>
                    <!--<input type="button" value="Formulario Visa (via Fax)" name="B5" onclick="showFax();">-->
                    <? //}
                    ?>

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
            <input type="hidden" name="zvcotizacion" id="zvcotizacion" size="3"
                   value="<?php echo $this->cotacaoDolar;?>"> R$ </p>
        <input type="hidden" name="valor_total" id="valor_total" size="3"
               value="<?php echo number_format(($total * $this->cotacaoDolar), 2, '', '');?>">
        <input type="hidden" name="numero_itens" id="numero_itens" value="<?php echo count($idd);?>">
        <input type="hidden" name="dolar" id="dolar" value="<?php echo $this->cotacaoDolar;?>">

    </form>
    <?php
    }
    ?>
<!--
    <div id="divPagoOnline" style="display:none">
        <h4>Pago Online</h4>
        <iframe id="ipagareIframe" name="ipagareIframe" src ="" width="100%" height="500" border="0" style="border: 0px">
            <p>Your browser does not support iframes.</p>
        </iframe>
    </div>
-->
</div>

<script>
    var isdivFaxOculto = true;
    var isdivTransferenciaOculto = true;
    var isdivPagoOnlineOculto = true;
    var valorTotal;

    function ocultaDiv(opcion, id)
    {
        if (opcion == 1)
        {
            document.getElementById(id).style.display = "none";
        }
        else
        {
            document.getElementById(id).style.display = "";
        }
    }




    function getValorCuota()
    {
        var cbEsEstudiante = document.formulario.cbEsEstudiante[0];
        var tot = 0;
        var inicial = document.getElementById("txtTotalUSD");
        var ini = inicial.value;
        var cotacao_dolar = document.getElementById("cotacao_dolar");
        var valor_total = 0;
        var x = 1;
        var lista = <?php echo count($this->cuotasDisponiveis);?>
        //Anio: 2016|40.00|USD|6733|Cotacao:3.2540


    <?php foreach ($this->cuotasDisponiveis as $x => $deuda) {?>
        cb = document.getElementById("cbanio" + <?php echo $deuda->id_pago ?>); //anios[i] );
        txt = document.getElementById("txtValorAnio" + <?php echo $deuda->id_pago ?>); //anios[i] );
        //alert(txt.value);
        campo = document.getElementById("descricao_item_" + <?php echo ($x + 1);?>);
        //lista = document.getElementById("descricao_item_" + x);
        //si esta chequeado el radio button "si" (si es estudiante)  y el año esta chequeado entonces le cobra la cuota estudiante
        if (cb.checked && cbEsEstudiante.checked == true)
        {
            txt.value = (<?php echo $deuda->monto;?> * <?php echo 50;?>)/100;
            tot += parseFloat(txt.value);

            campo.value = 'Anio: ' + <?php echo $deuda->anio ?> + '|' + txt.value + '|USD|' + <?php echo $deuda->id_pago ?> + '|Cotacao:' + cotacao_dolar.value;

            document.formulario.txtTotalUSD.value = tot;
            document.formulario.txtTotalUSD2.value = tot;
            document.formulario.total_real3.value = (tot * cotacao_dolar.value).toFixed(2);
            document.formulario.total_real4.value = (tot * cotacao_dolar.value).toFixed(2);
            valor_total = (tot * cotacao_dolar.value).toFixed(2);
            document.formulario.valor_total.value = valor_total.replace(".","");
            //







        }
        else
        {
            txt.value = <?php echo $deuda->monto;?>;
            tot += parseFloat(txt.value);
            document.formulario.txtTotalUSD.value = tot;
            document.formulario.txtTotalUSD2.value = tot;
            document.formulario.total_real3.value = (tot * cotacao_dolar.value).toFixed(2);
            document.formulario.total_real4.value = (tot * cotacao_dolar.value).toFixed(2);
            valor_total = (tot * cotacao_dolar.value).toFixed(2);
            document.formulario.valor_total.value = valor_total.replace(".","");
            campo.value = 'Anio: ' + <?php echo $deuda->anio ?> + '|' + <?php echo $deuda->monto;?> + '|USD|' + <?php echo $deuda->id_pago ?> + '|Cotacao:' + cotacao_dolar.value;

            //document.formulario.total_real.value = (tot * cotacao_dolar).toFixed(2);


        }

        //document.formulario.txtTotalUSD.value = tot;
        <?php }?>

        getTotalValoresCuotas();
    }



    function getTotalValoresCuotas()
    {
        var total = 0;
        var cotizacion = parseFloat(document.getElementById("txtCotizacion").value);
        var txtTotalReales = document.getElementById("txtTotalReales");
        var txtTotalUSD = document.getElementById("txtTotalUSD");
        var txtTotalReales2 = document.getElementById("txtTotalReales2");
        var txtTotalUSD2 = document.getElementById("txtTotalUSD2");

        <?php foreach ($this->cuotasDisponiveis as $deuda) {?>
        txt = document.getElementById("txtValorAnio" + <?php echo$deuda->id_pago;?>);
        total += parseFloat(txt.value);

        <?php }?>

        txtTotalUSD.value = total.toFixed(2);
        txtTotalReales.value = (total * cotizacion).toFixed(2);
        txtTotalUSD2.value = total.toFixed(2);
        txtTotalReales2.value = (total * cotizacion).toFixed(2);

        //refreshTotalIpagare();

    }




    function showFax()
    {
        if (isdivFaxOculto)
        {
            ocultaDiv(0,"divFax");
            ocultaDiv(1,"divTransferencia");
            ocultaDiv(1,"divPagoOnline");
            isdivFaxOculto = false;
            isdivTransferenciaOculto = true;
            isdivPagoOnlineOculto = true;
            document.location.href = "#gotoPago";
        }
        else
        {
            ocultaDiv(1,"divFax");
            isdivFaxOculto = true;
        }
    }

    function showTransferenciaBancaria()
    {
        if (isdivTransferenciaOculto)
        {
            ocultaDiv(0,"divTransferencia");
            ocultaDiv(1,"divFax");
            ocultaDiv(1,"divPagoOnline");
            isdivTransferenciaOculto = false;
            isdivPagoOnlineOculto = true;
            isdivFaxOculto = true;
            document.location.href = "#gotoPago";

        }
        else
        {
            ocultaDiv(1,"divTransferencia");
            isdivTransferenciaOculto = true;
        }
    }

    function showPagoOnline()
    {
        if (isdivPagoOnlineOculto)
        {
            refreshTotalIpagare();
            ocultaDiv(0,"divPagoOnline");
            ocultaDiv(1,"divFax");
            ocultaDiv(1,"divTransferencia");
            isdivPagoOnlineOculto = false;
            isdivTransferenciaOculto = true;
            isdivFaxOculto = true;

            document.location.href = "#gotoPago";
        }
        else
        {
            ocultaDiv(1,"divPagoOnline");
            isdivPagoOnlineOculto = true;
        }
    }

    function refreshTotalIpagare()
    {
        var txtTotalReales = document.getElementById("txtTotalReales").value;
        valorTotal = ((txtTotalReales.replace(",","")).replace(".",""));
        var stringDescripcion = "";
        var cbEsEstudiante = document.formulario.cbEsEstudiante[0];
        var cotizacion = parseFloat(document.getElementById("txtCotizacion").value);
        //formo cadena de la forma: desc1;valorReales1;codigoPago1-desc2;valorReales2;codigoPago2
        cb = document.getElementById("cbanio" + 5641); //anios[i] );
        totalUSD = document.getElementById("txtValorAnio" + 5641); //anios[i] );
        totalREALES = (totalUSD.value * cotizacion).toFixed(2)
        stringDescripcion += "2014 (" + totalUSD.value +" USD)";

        //si esta chequeado el radio button "si" (si es estudiante)  y el a�o esta chequeado entonces le cobra la cuota estudiante
        if (cb.checked && cbEsEstudiante.checked == true)
        {
            stringDescripcion += "(est.)";
        }
        stringDescripcion += ";5641;" + ((totalREALES.replace(",","")).replace(".",""))+ ";"
        //segundo separador:
        totalUSD = 0;
        totalREALES = 0;
        stringDescripcion = stringDescripcion.substring(0,(stringDescripcion.length - 1));
        stringDescripcion += "-";
        cb = document.getElementById("cbanio" + 5640); //anios[i] );
        totalUSD = document.getElementById("txtValorAnio" + 5640); //anios[i] );
        totalREALES = (totalUSD.value * cotizacion).toFixed(2)
        stringDescripcion += "2015 (" + totalUSD.value +" USD)";

        //si esta chequeado el radio button "si" (si es estudiante)  y el a�o esta chequeado entonces le cobra la cuota estudiante
        if (cb.checked && cbEsEstudiante.checked == true)
        {
            stringDescripcion += "(est.)";
        }
        stringDescripcion += ";5640;" + ((totalREALES.replace(",","")).replace(".",""))+ ";"
        //segundo separador:
        totalUSD = 0;
        totalREALES = 0;
        stringDescripcion = stringDescripcion.substring(0,(stringDescripcion.length - 1));
        stringDescripcion += "-";

        //borro el ultimo "-"
        stringDescripcion = stringDescripcion.substring(0,(stringDescripcion.length - 1));

        stringDescripcion = escape(stringDescripcion);
        stringDescripcion = encodeURI(stringDescripcion);

        document.getElementById("ipagareIframe").src = "components/com_pagocuota/includes/ipagare.php?dt="+ stringDescripcion +"&codigo_cliente=2734&email_cliente=suzana.cavenaghi%40gmail.com&nome_cliente=tempuser+temporario++%28tempuser%29&cest=101555&csec=v1x0r6g4&valor_total="+valorTotal+"&cotizacion="+cotizacion;
    }

</script>