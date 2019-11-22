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
$token = JFactory::getApplication()->input->getString('token');
$PayerID = JFactory::getApplication()->input->getString('PayerID');
?>
<h2 style="color:red;">DEV PAYPAL</h2>
<div id="divformularioPrincipal" >


        <!-- Comienzo de pagos anteriores-->
        <p>Estimado(a) <b><?php echo $nome.' ('.$usuario.')'; ?></b>, en <?php echo date( "j/m/Y" , $fecha ); ?> Recibimos su pago. ¡Gracias!
            Es su código de autorización:
            </p>
    <h2>Es su código de autorización: <?php echo $token;?></h2>
    <h2>ID PayPal: <?php echo $PayerID;?></h2>

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