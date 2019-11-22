<?php
/**
 * @version     1.0.0
 * @package     com_zvcadastramento
 * @copyright   Copyright (C) 2015. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 * @author      Zé Vicente <josevicente.sistemas@gmail.com> - http://www.zevicente.com.br
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Zvcadastro list controller class.
 */
class ZvcadastramentoControllerZvcadastro extends ZvcadastramentoController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Zvcadastro', $prefix = 'ZvcadastramentoModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function novoUsuario(){

		echo 'passei aqui';
		exit;




	}// fim do metodo //






}// fim da classe //