<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
defined('_JEXEC') or die;

/**
 * Class ZvcuotasFrontendHelper
 *
 * @since  1.6
 */
class ZvcuotasFrontendHelper
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
		if (file_exists(JPATH_SITE . '/components/com_zvcuotas/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_zvcuotas/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'ZvcuotasModel');
		}

		return $model;
	}


	public function getSobreNomeDoUsuario($id){

		$db = JFactory::getDbo();
		$sql ="SELECT user_id, zv_apellidopadre FROM #__zvcadastramento WHERE user_id='{$id}'";
		//
		$db->setQuery($sql);
		return $db->loadObjectList();



	}// fim do método //





}// fim da classe //
