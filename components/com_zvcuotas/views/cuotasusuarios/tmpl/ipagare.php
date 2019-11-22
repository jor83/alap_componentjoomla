<?php
/**
 * Created by PhpStorm.
 * User: josevicente - http://ww.zevicente.com.br
 * Date: 31/01/16
 * Time: 12:45
 */
defined('_JEXEC') or die;

// recebendo objeto do helper //
$objHelper = new ZvcuotasFrontendHelper();

// recebendo valores //
$valor_total = str_replace('.','',JFactory::getApplication()->input->getString('valor_total'));
$numero_itens = JFactory::getApplication()->input->getString('numero_itens');
$dolar = JFactory::getApplication()->input->getString('dolar');



// gerando chave //
$chave = md5(101555 . MD5('v1x0r6g4') . 1 . $valor_total);

// dados do usuario logado //
$user = JFactory::getUser();

$codigo_cliente = $user->id;

// ajustando o nome do cliente //
$sobre_nome = $objHelper->getSobreNomeDoUsuario($codigo_cliente);
$nome_cliente = $user->name.$sobre_nome[0]->zv_apellidopadre.' - ID USUARIO:'.$codigo_cliente;
$email_cliente = $user->email;

// escrevendo quantidade de itens //
for($x=1;$x<=$numero_itens;$x++){

	//$descricao_item_.$x = JFactory::getApplication()->input->getString('descricao_item_'.$x);
	$t = JFactory::getApplication()->input->getString('descricao_item_'.$x);
	$desc .= '<input type="hidden" name="descricao_item_'.$x.'" value="'.$t.'">';
	$desc .= '<input type="hidden" name="quantidade_item_'.$x.'" value="100">';
	//
	$c = explode("|",$t);
	//
	$desc .= '<input type="hidden" name="codigo_item_'.$x.'" value="'.$c[3].'">';
	// calculando valor de cada item //
	$valor = ($c[1] * $dolar);
	$valor = number_format($valor,2);
	$desc .= '<input type="hidden" name="valor_item_'.$x.'" value="'.(str_replace('.','',$valor)).'">';

}

?>

<div id="formDiv">
			<form name="ipagareForm" id="ipagareForm" action="https://ww2.ipagare.com.br/service/process.do" method="POST" target="_blank">
				<input type="hidden" name="estabelecimento" value="101555">
				<input type="hidden" name="acao" value="1">
				<input type="hidden" name="valor_total" value="<?php echo $valor_total;?>">
				<input type="hidden" name="chave" id="chave" value="<?php echo $chave;?>">
				<!--<input type="hidden" name="teste" value="1">-->
				<input type="hidden" name="idioma" value="sp">

				<input type="hidden" name="codigo_cliente" value="<?php echo $codigo_cliente;?>">
				<input type="hidden" name="nome_cliente" value="<?php echo $nome_cliente;?>">
				<input type="hidden" name="email_cliente" value="<?php echo $email_cliente;?>">

				<input type="hidden" name="numero_itens" value="<?php echo $numero_itens;?>">

				<?php echo $desc;?>
				<?php echo $qtd;?>
				<?php echo $cod;?>
				<?php echo $val;?>
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
			</p>
			</form>
		</div>
