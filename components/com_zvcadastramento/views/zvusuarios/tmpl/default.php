<?php
/**
 * @version     1.0.0
 * @package     com_zvcadastramento
 * @copyright   Copyright (C) 2015. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 * @author      Zé Vicente <josevicente.sistemas@gmail.com> - http://www.zevicente.com.br
 */
// no direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_zvcadastramento');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_zvcadastramento')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_USER_ID'); ?></th>
			<td><?php echo $this->item->user_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_NAME'); ?></th>
			<td><?php echo $this->item->zv_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_APELLIDOPADRE'); ?></th>
			<td><?php echo $this->item->zv_apellidopadre; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_EMAIL'); ?></th>
			<td><?php echo $this->item->zv_email; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_USUARIO'); ?></th>
			<td><?php echo $this->item->zv_usuario; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_PASSWORD'); ?></th>
			<td><?php echo $this->item->zv_password; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_PASSWORD_VERIFY'); ?></th>
			<td><?php echo $this->item->zv_password_verify; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONPAIS'); ?></th>
			<td><?php echo $this->item->zv_direccionpais; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TIPODEAFILIACIN'); ?></th>
			<td><?php echo $this->item->zv_tipodeafiliacin; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TIPODOCUMENTO'); ?></th>
			<td><?php echo $this->item->zv_tipodocumento; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DOCUMENTO'); ?></th>
			<td><?php echo $this->item->zv_documento; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_NACIONALIDAD'); ?></th>
			<td><?php echo $this->item->zv_nacionalidad; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_FECHADENACIMIENTO'); ?></th>
			<td><?php echo $this->item->zv_fechadenacimiento; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_SEXO'); ?></th>
			<td><?php echo $this->item->zv_sexo; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TELEFONO'); ?></th>
			<td><?php echo $this->item->zv_telefono; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_AVATAR'); ?></th>
			<td><?php echo $this->item->zv_avatar; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_AODEAFILIACION'); ?></th>
			<td><?php echo $this->item->zv_aodeafiliacion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCINCOMPLEMENTO'); ?></th>
			<td><?php echo $this->item->zv_direccincomplemento; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_APELLIDOMADRE'); ?></th>
			<td><?php echo $this->item->zv_apellidomadre; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_PROFESION'); ?></th>
			<td><?php echo $this->item->zv_profesion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TIPOMEMBRESIA'); ?></th>
			<td><?php echo $this->item->zv_tipomembresia; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_ESTADOCIVIL'); ?></th>
			<td><?php echo $this->item->zv_estadocivil; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_EMAILPROBLEMATICO'); ?></th>
			<td><?php echo $this->item->zv_emailproblematico; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_FECHAACTUALIZACIONDATOS'); ?></th>
			<td><?php echo $this->item->zv_fechaactualizaciondatos; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_FECHADEBAJA'); ?></th>
			<td><?php echo $this->item->zv_fechadebaja; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCION'); ?></th>
			<td><?php echo $this->item->zv_direccion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONNUMERO'); ?></th>
			<td><?php echo $this->item->zv_direccionnumero; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONBARRIO'); ?></th>
			<td><?php echo $this->item->zv_direccionbarrio; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONCIUDAD'); ?></th>
			<td><?php echo $this->item->zv_direccionciudad; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONPROVINCIA'); ?></th>
			<td><?php echo $this->item->zv_direccionprovincia; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONCODIGOPOSTAL'); ?></th>
			<td><?php echo $this->item->zv_direccioncodigopostal; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DDI'); ?></th>
			<td><?php echo $this->item->zv_ddi; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_FAX'); ?></th>
			<td><?php echo $this->item->zv_fax; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_EMAILLABORAL'); ?></th>
			<td><?php echo $this->item->zv_emaillaboral; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONLABORALNUMERO'); ?></th>
			<td><?php echo $this->item->zv_direccionlaboralnumero; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_INSTITUCION'); ?></th>
			<td><?php echo $this->item->zv_institucion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONLABORAL'); ?></th>
			<td><?php echo $this->item->zv_direccionlaboral; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONLABORALCOMPLEMENTO'); ?></th>
			<td><?php echo $this->item->zv_direccionlaboralcomplemento; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECIONLABORALBARRIO'); ?></th>
			<td><?php echo $this->item->zv_direcionlaboralbarrio; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONLABORALCIUDAD'); ?></th>
			<td><?php echo $this->item->zv_direccionlaboralciudad; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONLABORALPROVINCIA'); ?></th>
			<td><?php echo $this->item->zv_direccionlaboralprovincia; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECIONLABORALPAIS'); ?></th>
			<td><?php echo $this->item->zv_direcionlaboralpais; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONLABORALCODIGOPOSTAL'); ?></th>
			<td><?php echo $this->item->zv_direccionlaboralcodigopostal; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DDILABORAL'); ?></th>
			<td><?php echo $this->item->zv_ddilaboral; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TELEFONOLABORAL'); ?></th>
			<td><?php echo $this->item->zv_telefonolaboral; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_FAXLABORAL'); ?></th>
			<td><?php echo $this->item->zv_faxlaboral; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TITULOGRADO'); ?></th>
			<td><?php echo $this->item->zv_titulogrado; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TITULOPOSTGRADO'); ?></th>
			<td><?php echo $this->item->zv_titulopostgrado; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_TITULONOAPLICADO'); ?></th>
			<td><?php echo $this->item->zv_titulonoaplicado; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_AREAINTERES'); ?></th>
			<td><?php echo $this->item->zv_areainteres; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_INSTNET'); ?></th>
			<td><?php echo $this->item->zv_instnet; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONINSTNET'); ?></th>
			<td><?php echo $this->item->zv_direccioninstnet; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_DIRECCIONPARTICULARNET'); ?></th>
			<td><?php echo $this->item->zv_direccionparticularnet; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_EMAILPERSONALNET'); ?></th>
			<td><?php echo $this->item->zv_emailpersonalnet; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_EMAILLABORALNET'); ?></th>
			<td><?php echo $this->item->zv_emaillaboralnet; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_AREASUJEST'); ?></th>
			<td><?php echo $this->item->zv_areasujest; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_AREANOAPLICADO'); ?></th>
			<td><?php echo $this->item->zv_areanoaplicado; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_PERTENECEASOCIACION'); ?></th>
			<td><?php echo $this->item->zv_perteneceasociacion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_ASOCNACIONALPOBLACION'); ?></th>
			<td><?php echo $this->item->zv_asocnacionalpoblacion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_PERFILACADMICO'); ?></th>
			<td><?php echo $this->item->zv_perfilacadmico; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_AUTORIZODIVULGARMISDATOSPORALAP'); ?></th>
			<td><?php echo $this->item->zv_autorizodivulgarmisdatosporalap; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_SOCIO'); ?></th>
			<td><?php echo $this->item->zv_socio; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_OTRAS'); ?></th>
			<td><?php echo $this->item->zv_otras; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_ZV_FECHAAPROBACION'); ?></th>
			<td><?php echo $this->item->zv_fechaaprobacion; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_ZVCADASTRAMENTO_FORM_LBL_ZVUSUARIOS_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_zvcadastramento&task=zvusuarios.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_ZVCADASTRAMENTO_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_zvcadastramento')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_zvcadastramento&task=zvusuarios.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_ZVCADASTRAMENTO_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_ZVCADASTRAMENTO_ITEM_NOT_LOADED');
endif;
?>
