<?php

/**
 * @version     1.0.0
 * @package     com_zvcadastramento
 * @copyright   Copyright (C) 2015. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 * @author      Zé Vicente <josevicente.sistemas@gmail.com> - http://www.zevicente.com.br
 */
defined('_JEXEC') or die;

class ZvcadastramentoFrontendHelper
{
	

	/**
	 * Get an instance of the named model
	 *
	 * @param string $name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_zvcadastramento/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_zvcadastramento/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'ZvcadastramentoModel');
		}

		return $model;
	}
}
