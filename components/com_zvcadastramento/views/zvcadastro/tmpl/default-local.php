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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_zvcadastramento');
$canEdit    = $user->authorise('core.edit', 'com_zvcadastramento');
$canCheckin = $user->authorise('core.manage', 'com_zvcadastramento');
$canChange  = $user->authorise('core.edit.state', 'com_zvcadastramento');
$canDelete  = $user->authorise('core.delete', 'com_zvcadastramento');
?>

<form action="<?php echo JRoute::_('index.php?option=com_zvcadastramento&view=zvcadastro'); ?>" method="post" name="adminForm" id="adminForm">

	
	<table class="table table-striped" id="zvusuariosList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				
			<?php endif; ?>

							<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ZVCADASTRAMENTO_ZVCADASTRO_USER_ID', 'a.user_id', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ZVCADASTRAMENTO_ZVCADASTRO_ZV_NAME', 'a.zv_name', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ZVCADASTRAMENTO_ZVCADASTRO_ZV_EMAIL', 'a.zv_email', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_ZVCADASTRAMENTO_ZVCADASTRO_ZV_USUARIO', 'a.zv_usuario', $listDirn, $listOrder); ?>
				</th>


			<?php if (isset($this->items[0]->id)): ?>
				<th width="1%" class="nowrap center hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			<?php endif; ?>

							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_ZVCADASTRAMENTO_ZVCADASTRO_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_zvcadastramento'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_zvcadastramento')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)): ?>
					<?php $class = ($canEdit || $canChange) ? 'active' : 'disabled'; ?>
					
				<?php endif; ?>

								<td>

					<?php echo $item->user_id; ?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'zvcadastro.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_zvcadastramento&view=zvusuarios&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->zv_name); ?></a>
				</td>
				<td>

					<?php echo $item->zv_email; ?>
				</td>
				<td>

					<?php echo $item->zv_usuario; ?>
				</td>


				<?php if (isset($this->items[0]->id)): ?>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
				<?php endif; ?>

								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_zvcadastramento&task=zvusuariosform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<button data-item-id="<?php echo $item->id; ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></button>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($canCreate): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_zvcadastramento&task=zvusuariosform.edit&id=0', false, 2); ?>"
			class="btn btn-success btn-small"><i
				class="icon-plus"></i> <?php echo JText::_('COM_ZVCADASTRAMENTO_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {
		var item_id = jQuery(this).attr('data-item-id');
		if (confirm("<?php echo JText::_('COM_ZVCADASTRAMENTO_DELETE_MESSAGE'); ?>")) {
			window.location.href = '<?php echo JRoute::_('index.php?option=com_zvcadastramento&task=zvusuariosform.remove&id=', false, 2) ?>' + item_id;
		}
	}
</script>


