<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_zvcuotas', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_zvcuotas/js/form.js');

/**/
?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-cuotasusuario').submit(function (event) {
				
			});

			
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-cuotasusuario').submit(function (event) {
				
			});

			
		});
	}
</script>

<div class="cuotasusuario-edit front-end-edit">
	<?php if (!empty($this->item->id)): ?>
		<h1>Edit <?php echo $this->item->id; ?></h1>
	<?php else: ?>
		<h1>Add</h1>
	<?php endif; ?>

	<form id="form-cuotasusuario"
		  action="<?php echo JRoute::_('index.php?option=com_zvcuotas&task=cuotasusuario.save'); ?>"
		  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" class="validate btn btn-primary">
						<?php echo JText::_('JSUBMIT'); ?>
					</button>
				<?php endif; ?>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_zvcuotas&task=cuotasusuarioform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_zvcuotas"/>
		<input type="hidden" name="task"
			   value="cuotasusuarioform.save"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
