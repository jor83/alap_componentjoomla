<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvbuscasocio
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  2016 José Vicente Martins
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Buscas list controller class.
 *
 * @since  1.6
 */
class ZvbuscasocioControllerBuscas extends ZvbuscasocioController
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
	public function &getModel($name = 'Buscas', $prefix = 'ZvbuscasocioModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
