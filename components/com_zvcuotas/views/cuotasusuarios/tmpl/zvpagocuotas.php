<?php 
defined( '_JEXEC' ) or die( 'Restricted access' ); 
?>
<?php
$task = JRequest::getVar('task');
$user =& JFactory::getUser();
echo $user->id;

$this->isSocio = 1;

if ($user->id == 0)
{
  header('location:'.JURI::base().'index.php?option=com_articlepermission&view=showarticle&idIntro=330&idSinPermiso=296&Itemid=2');
}

if (!isset($task))
{
?>
<?php if ($this->isSocio == 1) {?>
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

		<?php foreach ($this->aniosAdeuda as $deuda) {?>
			cb = document.getElementById("cbanio" + <?php echo$deuda->id_pago ?>); //anios[i] );
			txt = document.getElementById("txtValorAnio" + <?php echo$deuda->id_pago ?>); //anios[i] );
			
			//si esta chequeado el radio button "si" (si es estudiante)  y el a���o esta chequeado entonces le cobra la cuota estudiante
			if (cb.checked && cbEsEstudiante.checked == true)
			{
				txt.value = (<?php echo $deuda->monto?> * <?php echo$deuda->porcentaje_estudiante?>)/100;
			}
			else
			{
				txt.value = <?php echo $deuda->monto?> 
			}
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
		
		<?php foreach ($this->aniosAdeuda as $deuda) {?>
			txt = document.getElementById("txtValorAnio" + <?php echo$deuda->id_pago ?>);
			total += parseFloat(txt.value);
		<?php }?>
	
		txtTotalUSD.value = total.toFixed(2);
		txtTotalReales.value = (total * cotizacion).toFixed(2);
		txtTotalUSD2.value = total.toFixed(2);
		txtTotalReales2.value = (total * cotizacion).toFixed(2);
		refreshTotalIpagare();
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
		<?php foreach ($this->aniosAdeuda as $deuda) {?>
			cb = document.getElementById("cbanio" + <?php echo$deuda->id_pago ?>); //anios[i] );
			totalUSD = document.getElementById("txtValorAnio" + <?php echo$deuda->id_pago ?>); //anios[i] );
			totalREALES = (totalUSD.value * cotizacion).toFixed(2)
			stringDescripcion += "<?php echo $deuda->anio?> (" + totalUSD.value +" USD)";
			
			//si esta chequeado el radio button "si" (si es estudiante)  y el a���o esta chequeado entonces le cobra la cuota estudiante
			if (cb.checked && cbEsEstudiante.checked == true)
			{
				stringDescripcion += "(est.)";
			}
			stringDescripcion += ";<?php echo$deuda->id_pago ?>;" + ((totalREALES.replace(",","")).replace(".",""))+ ";"
			//segundo separador:
			totalUSD = 0;
			totalREALES = 0;
			stringDescripcion = stringDescripcion.substring(0,(stringDescripcion.length - 1));
			stringDescripcion += "-";
		<?php }?>
		 
		//borro el ultimo "-"
		stringDescripcion = stringDescripcion.substring(0,(stringDescripcion.length - 1));

		stringDescripcion = escape(stringDescripcion);
		stringDescripcion = encodeURI(stringDescripcion);
		
		document.getElementById("ipagareIframe").src = "components/com_pagocuota/includes/ipagare.php?dt="+ stringDescripcion +"&codigo_cliente=<?echo $this->codigoCliente; ?>&email_cliente=<?echo urlencode($this->emailCliente); ?>&nome_cliente=<?echo urlencode( str_replace('-','',$this->userNameCliente)); ?>&cest=<?echo $this->ipagareEstablecimiento; ?>&csec=<?echo $this->ipagareCodigoSeguridad; ?>&valor_total="+valorTotal+"&cotizacion="+cotizacion;
	}
	

		
</script>
<?php }?>
	<?php if ($this->isSocio == 1) {?>
	<div id="divformularioPrincipal" >
		<form id="formulario" name="formulario" action="" >
			<?$fecha = time ();?>	
			<!-- Comienzo de pagos anteriores-->
			<br>
			<p>Estimado(a) <b><?echo $this->userNameCliente; ?></b>, en <?echo date( "j/m/Y" , $fecha ); ?> sus datos de Cuota en la Asociaci&oacute;n Latino Americana de Poblaci&oacute;n son:</p>
			<br>
			
			<h4>Su a&ntilde;o de afiliaci&oacute;n es: <?php echo $this->anioAfiliacion;?></h4>
				<p>Para pagar su cuota puede utilizar la opci&oacute;n de pago en linea con tarjeta de credito 
				o utilizar la opci&oacute;n de formulario via fax 
				(las instrucciones se encuentra al hacer clic en este boton).
				
				Para pagar su cuota puede utilizar la opci&oacute;n de pago en l&iacute;nea con tarjeta 
				de cr&eacute;dito o utilizar la opci&oacute;n de formulario v&iacute;a fax 
				(las instrucciones se encuentran al hacer clic en este bot&oacute;n).
				
				</p>
			<br/><br/>

			<?php if (count($this->pagosAnteriores) > 0) {?>	
			<h4>Sus Pagos anteriores son:</h4>
				<center>
					<table border="1" id="table1" width="95%" cellpadding="3" cellspacing="0">
						<tr>
							<td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%" bgcolor="#4f4b32">Fecha</td>
							<td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%">A&ntilde;o</td>
							<td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="20%">Valor</td>
							<td class="titulos_tablas" bgcolor="#f0ede7" align="center" width="40%">Concepto</td>
						</tr>
						<?php foreach ($this->pagosAnteriores as $pago) {?>
						<tr>
							<td class="contenido_tablas" align="center"><?php echo $pago->fecha_pago;?></td>
							<td class="contenido_tablas" align="center"><?php echo ' '.$pago->anio; ?></td>
							<td class="contenido_tablas"align="center"><?php echo $pago->moneda;?> <?php echo $pago->monto;?></td>
							<td class="contenido_tablas" width="40%"><?php echo $pago->concepto;?></td>
						</tr>
						<?php }?>
					</table>
				</center>
			<?php }?>				
			<!-- Fin de pagos anteriores-->

			<!-- Comienzo de cuotas adeudadas-->
			
			<?php
			if (count($this->aniosAdeuda) > 0) {?>	
			<h4>Los valores debidos de su cuota son:</h4>
			<center>
				<table border="1" id="table3"  width="95%" cellpadding="3" cellspacing="0">
					<?php foreach ($this->aniosAdeuda as $adeuda) {?>
					<tr>
						<td class="titulos_tablas" bgcolor="#f0ede7" align="center">Valor en Dolares:</td>
						<td class="titulos_tablas"  align="center">U$D <input type="text" id="txtValorAnio<?php echo $adeuda->id_pago;?>" name="txtValorAnio<?php echo $adeuda->id_pago;?>" size="6" readonly style="border: 0px;" value="<?php echo $adeuda->monto ?>"></td>
						<td class="titulos_tablas" bgcolor="#f0ede7" align="center">A&ntilde;o <?php echo $adeuda->anio;?></td>
						<td class="titulos_tablas"  width="30%"><?php echo $adeuda->concepto;?></td>
					</tr>
					<?php }?>
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
					<?php foreach ($this->aniosAdeuda as $adeuda) {?>
					<input type="checkbox" name="cbanio<?php echo  $adeuda->id_pago;?>" id="cbanio<?php echo  $adeuda->id_pago;?>" value="ON" onclick="getValorCuota();"><?php echo  $adeuda->anio;?>
					<?php }?>
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
						<?if ($this->idArticuloTransferencia != 0){?>
							<input type="button" value="Transferencia Bancaria" name="B3" onclick="showTransferenciaBancaria();">
						<?php }?>
						<input type="button" value="Pago en linea (Tarjetas y otros)" name="B4"  onclick="showPagoOnline();">
						<?if ($this->idArticuloFax != 0){?>
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
			<input type="text" name="txtCotizacion" id="txtCotizacion" size="3" value="<?php echo $this->cotizacion;?>" readonly style="border: 0px;"> R$ </p>
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
	<? }
	else
	{
	?>
		<center>
			<p style="font-color:green;">Ud. no es socio afiliado a ALAP..</p>
		</center>

	<? }?>

	<!-- Comienzo de Divs de formas de pago-->
	<div id="divTransferencia" style="display:none">
	<?if ($this->idArticuloTransferencia != 0){?>
		<h4>Pago con Tranferencia Bancaria</h4>
		<hr>
		<? echo $this->contentArticuloTransferencia?>
	<? }?>	

	</div>
	
	<div id="divFax" style="display:none">
	<?if ($this->idArticuloFax != 0){?>
		<h4>Pago con formulario via FAX</h4>
		<hr>
		<? echo $this->contentArticuloFax?>
	<? }?>
	</div>
	
	<div id="divPagoOnline" style="display:none">
		<h4>Pago Online</h4>
		<iframe id="ipagareIframe" name="ipagareIframe" src ="" width="100%" height="500" border="0" style="border: 0px">
			<p>Your browser does not support iframes.</p>
		</iframe>
	</div>
	<!-- Fin de Divs de formas de pago-->

<? 
} 
?>