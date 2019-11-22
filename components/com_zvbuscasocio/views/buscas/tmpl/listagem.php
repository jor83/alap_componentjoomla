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
$Itemid = JFactory::getApplication()->input->getInt('Itemid');

$total= count($this->lista);
//echo '<pre>';
//var_dump($this->lista);
//echo '</pre>';
$Itemid = JFactory::getApplication()->input->getInt('Itemid');
?>
<h4>Se encontraron <?php echo $total;?> resultados</h4>
<p><a href="<?php echo JRoute::_('index.php?option=com_zvbuscasocio&view=buscas&Itemid='.$Itemid);?>">Volver</a></p>
<?php
foreach($this->lista as $l) {
?>
<div class="row-fluid">
    <div class="span12">
        <a href="<?php echo JRoute::_('index.php?option=com_zvbuscasocio&view=buscas&layout=detalha&user_id='.$l->id.'&Itemid='.$Itemid);?>" style="text-decoration: underline;"><?php echo $l->name.' '.$l->zv_apellidopadre;?></a> <?php echo $l->zv_institucion.', '.$l->zv_direccionprovincia;?>
    </div>
</div>
<?php
}
?>