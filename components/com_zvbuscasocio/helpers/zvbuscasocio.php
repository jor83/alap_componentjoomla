<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvbuscasocio
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  2016 José Vicente Martins
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
defined('_JEXEC') or die;

/**
 * Class ZvbuscasocioFrontendHelper
 *
 * @since  1.6
 */
class ZvbuscasocioHelpersZvbuscasocio
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_zvbuscasocio/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_zvbuscasocio/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'ZvbuscasocioModel');
		}

		return $model;
	}
}
