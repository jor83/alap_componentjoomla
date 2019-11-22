<?php
/**
 * Created by PhpStorm.
 * User: josevicente
 * Date: 31/01/16
 * Time: 12:45
 */
//defined('_JEXEC') or die;
$chave = md5(101555 . MD5('v1x0r6g4') . 1 . 31992);
//$frame =& JFactory::getApplication();

?>

<div id="formDiv">
			<form name="ipagareForm" id="ipagareForm" action="https://ww2.ipagare.com.br/service/process.do" method="POST">
				<input type="hidden" name="estabelecimento" value="101555">
				<input type="hidden" name="acao" value="1">
				<input type="hidden" name="valor_total" value="31992">
				<input type="hidden" name="chave" id="chave" value="<?php echo $chave;?>">
				<input type="hidden" name="teste" value="1">
				<input type="hidden" name="idioma" value="sp">

				<input type="hidden" name="codigo_cliente" value="2734">
				<input type="hidden" name="nome_cliente" value="tempuser temporario  (tempuser)">
				<input type="hidden" name="email_cliente" value="suzana.cavenaghi@gmail.com">

				<input type="hidden" name="numero_itens" value="2">

				<input type="hidden" name="descricao_item_1" value="Cuota Año 2014 (40 USD) - 1 USD 3.999 R$">
				<input type="hidden" name="quantidade_item_1" value="100">
				<input type="hidden" name="codigo_item_1" value="5641">
				<input type="hidden" name="valor_item_1" value="15996">
				<input type="hidden" name="descricao_item_2" value="Cuota Año 2015 (40 USD) - 1 USD 3.999 R$">
				<input type="hidden" name="quantidade_item_2" value="100">
				<input type="hidden" name="codigo_item_2" value="5640">
				<input type="hidden" name="valor_item_2" value="15996">
						<br>
			<center>
				<span class="destacado">Bienvenido al sistema de pago online de la Asociaci&oacute;n Latino Americana de Poblaci&oacute;n</span>
				<br>
				<br>

				</center>
				<span class="txt_contenido">
A continuaci&oacute;n siga los siguientes 3 sencillos pasos para realizar su pago, nuestro sistema cuenta con
				la mas alta seguridad a fin de brindarle tranquilidad en la realizaci&oacute;n del mismo.<br><br>

El procedimiento de pago en l&iacute;nea estar&aacute; listo cuando
				la informaci&oacute;n respecto la transacci&oacute;n presenta
				la &uacute;ltima etapa (confirmaci&oacute;n) y se visualiza la pantalla de impresi&oacute;n con el status
				y el n&uacute;mero de identificaci&oacute;n de la transacci&oacute;n. Elija su forma de pago y siga
				las instrucciones de pantalla.
				<br><br>
				</span>

			<center><input type="submit" id="btContinuar"  value="Aguarde unos segundos..."> </center>
			</form>
		</div>
