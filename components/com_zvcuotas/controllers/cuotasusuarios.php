<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Cuotasusuarios list controller class.
 *
 * @since  1.6
 */
class ZvcuotasControllerCuotasusuarios extends ZvcuotasController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Cuotasusuarios', $prefix = 'ZvcuotasModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
