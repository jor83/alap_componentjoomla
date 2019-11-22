<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvbuscasocio
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  2016 José Vicente Martins
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Zvbuscasocio', JPATH_COMPONENT);
JLoader::register('ZvbuscasocioController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Zvbuscasocio');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
