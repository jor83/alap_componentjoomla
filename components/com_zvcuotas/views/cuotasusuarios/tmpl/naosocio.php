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

//JHtml::_('behavior.keepalive');
//JHtml::_('behavior.tooltip');
//JHtml::_('bootstrap.framework');
//JHtml::_('behavior.formvalidation');
//JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_zvcadastramento', JPATH_SITE);
$doc =& JFactory::getDocument();
//$doc->addStyleSheet("//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css");
//$doc->addScript(JUri::base() . 'components/com_zvcadastramento/assets/js/jquery-1.11.3.min.js');
//$doc->addScript(JUri::base() . 'components/com_zvcadastramento/assets/js/jquery-ui.js');
//$doc->addScript(JUri::base() . 'components/com_zvcadastramento/assets/js/jquery.mask.min.js');
//$doc->addScript(JUri::base() . 'components/com_zvcadastramento/assets/js/zvcadastramento.js');

//$doc->addstylesheet('components/com_zvcadastramento/assets/css/bootstrap.css');

$nome = JFactory::getUser()->name;
$usuario = JFactory::getUser()->username;

?>

<p>Estimado(a) <strong><?php echo $nome;?> (<?php echo $usuario;?>)</strong>, lo sentimos pero sólo los usuarios pueden realizar el pago de las cuotas;</p>
<p>Por favor, póngase en contacto con la oficina para resolver este problema por correo electrónico: <a href="mailto:alap.secretaria@alapop.org">alap.secretaria@alapop.org</a></p>
