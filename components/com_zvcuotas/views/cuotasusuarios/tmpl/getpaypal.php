<?php
/**
 * Created by PhpStorm.
 * User: josevicente - http://ww.zevicente.com.br
 * Date: 31/01/16
 * Time: 12:45
 */
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addScript('./components/com_zvcuotas/assets/js/jquery-1.12.0.min.js');
$doc->addScript('./components/com_zvcuotas/assets/js/cuotasusuarios.js');
// recebendo objeto do helper //
$objHelper = new ZvcuotasFrontendHelper();

// recebendo valores //
$valor_total_reais = str_replace(',','.',JFactory::getApplication()->input->getString('valor_total'));
$numero_itens = JFactory::getApplication()->input->getString('numero_itens');
$dolar = JFactory::getApplication()->input->getString('dolar');

// gerando chave //
//$chave = md5(101555 . MD5('v1x0r6g4') . 1 . $valor_total);

// dados do usuario logado //
$user = JFactory::getUser();

$codigo_cliente = $user->id;

// ajustando o nome do cliente //
$sobre_nome = $objHelper->getSobreNomeDoUsuario($codigo_cliente);
$nome_cliente = $user->name.$sobre_nome[0]->zv_apellidopadre.' - ID USUARIO:'.$codigo_cliente;
$email_cliente = $user->email;
$valor_total = 0;
// escrevendo quantidade de itens //
for($x=1;$x<=$numero_itens;$x++){

    //PAYMENTREQUEST_0_PAYMENTACTION=SALE&
    //PAYMENTREQUEST_0_AMT=22.00&
    //PAYMENTREQUEST_0_CURRENCYCODE=BRL&
    //PAYMENTREQUEST_0_ITEMAMT=22.00&
    //PAYMENTREQUEST_0_INVNUM=1234&

    //L_PAYMENTREQUEST_0_NAME0=Item A& - ***
    //L_PAYMENTREQUEST_0_DESC0=Produto A – 110V& - OPCIONAL
    //L_PAYMENTREQUEST_0_AMT0=11.00& - ***
    //L_PAYMENTREQUEST_0_QTY0=1& - ***
    //L_PAYMENTREQUEST_0_ITEMAMT=11.00&

    //L_PAYMENTREQUEST_0_NAME1=Item B&
    //L_PAYMENTREQUEST_0_DESC1=Produto B – 220V&
    //L_PAYMENTREQUEST_0_AMT1=11.00&
    //L_PAYMENTREQUEST_0_QTY1=1&

    //$descricao_item_.$x = JFactory::getApplication()->input->getString('descricao_item_'.$x);
	$t = JFactory::getApplication()->input->getString('descricao_item_'.$x);
    $c = explode("|",$t);
    $valor = ($c[1]);
    $valor = number_format($valor,2,',','.');
    //var_dump($c);
    $valor_total += $valor;
    //
    //$desc .= '<input type="hidden" name="PAYMENTREQUEST_'.$x.'_PAYMENTACTION" value="SALE">';
    //$desc .= '<input type="hidden" name="PAYMENTREQUEST_'.$x.'_AMT" value="'.($valor).'">';
    //$desc .= '<input type="hidden" name="PAYMENTREQUEST_0_AMT" value="125">';
    //$desc .= '<input type="hidden" name="PAYMENTREQUEST_'.$x.'_CURRENCYCODE" value="BRL">';
    $desc .= '<input type="hidden" name="PAYMENTREQUEST_'.$x.'_INVNUM" value="'.$c[3].'">';
    //
    // calculando valor de cada item //
    //$valor = ($c[1] * $dolar);
    // valor do ítem //
	$desc .= '<input type="hidden" name="PAYMENTREQUEST_'.$x.'_ITEAMT" value="'.($valor_total_reais).'">';
	//
	$desc .= '<input type="hidden" name="L_PAYMENTREQUEST_'.$x.'_NAME0" value="'.($t).'">';
	$desc .= '<input type="hidden" name="L_PAYMENTREQUEST_'.$x.'_AMT0" value="'.($valor_total_reais).'">';
	$desc .= '<input type="hidden" name="L_PAYMENTREQUEST_'.$x.'_QTY0" value="1">';
    $desc .= '<input type="hidden" name="L_PAYMENTREQUEST_'.$x.'_ITEMAMT" value="'.($valor_total_reais).'">';

}
//var_dump($this->cuotasDisponiveis);
?>
<h2 style="color:red;">DEV PAYPAL</h2>
<div id="formDiv">

    <form name="PayPalForm" id="PayPalForm" action="./index.php?option=com_zvcuotas&task=validaTokenPayPal" method="POST" target="_blank">

        <input type="hidden" name="PAYMENTREQUEST_0_AMT" value="<?php echo $valor_total_reais;//number_format($valor_total_reais,2);?>">
        <?php echo $desc;?>
        <?php //echo $qtd;?>
        <?php //echo $cod;?>
        <?php //echo $val;?>
        <!--<input type="hidden" name="BUTTONSOURCE" value="BR_EC_EMPRESA">-->


        <br>
        <center>
            <h4>Bienvenido al sistema de pago online de la Asociaci&oacute;n Latino Americana de Poblaci&oacute;n</h4>
            <br>
            <br>

        </center>
        <span class="txt_contenido">
A continuaci&oacute;n siga los siguientes 3 sencillos pasos para realizar su pago, nuestro sistema cuenta con
				la mas alta seguridad a fin de brindarle tranquilidad en la realizaci&oacute;n del mismo.<br><br>
					<p><strong>Usted será redirigido a un entorno de pago seguro!</strong></p>

El procedimiento de pago en l&iacute;nea estar&aacute; listo cuando
				la informaci&oacute;n respecto la transacci&oacute;n presenta
				la &uacute;ltima etapa (confirmaci&oacute;n) y se visualiza la pantalla de impresi&oacute;n con el status
				y el n&uacute;mero de identificaci&oacute;n de la transacci&oacute;n. Elija su forma de pago y siga
				las instrucciones de pantalla.
				<br><br>
				</span>

        <p>
            <input type="submit" id="btContinuar"  value="Finalizar el pago?" class=" btn-success">
            <input type="button" id="btContinuar"  value="Cancelar" class="btn-danger">
            <!--<input type="button" id="id_vicente"  value="PAGAR" class="btn-danger">-->
        </p>
    </form>
</div>






