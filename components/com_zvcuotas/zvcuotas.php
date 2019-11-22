<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::register('ZvcuotasFrontendHelper', JPATH_COMPONENT . '/helpers/zvcuotas.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Zvcuotas');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
