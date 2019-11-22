<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */

defined('_JEXEC') or die;

echo $token = JFactory::getApplication()->input->getString('token');
echo '<br>';
echo $PayerID = JFactory::getApplication()->input->getString('PayerID');
//

?>
