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

//echo '<pre>';
//var_dump($this->lista);
//echo '</pre>';

?>
<h4 style="color:#610101 !important;"><?php echo $this->lista[0]->name;?></h4>
<p><a href="<?php echo JRoute::_('index.php?option=com_zvbuscasocio&view=buscas&Itemid='.$Itemid);?>" style="text-decoration: underline;"> Volver</a></p>

<div class="row-fluid">
    <div class="span12">

    <p><?php echo $this->lista[0]->name;?></p>
    <p><strong>Institución:</strong> <?php echo $this->lista[0]->zv_institucion;?></p>
    <p><strong>Teléfono:</strong> 55-21-22254183, 55-21-2142 4689, 55-21-22422077</p>
    <p><strong>E-mail:</strong> <?php echo $this->lista[0]->email;?></p>
    <p><strong>Dirección correspondencia completa:</strong><?php echo $this->lista[0]->zv_direccion.' '.$this->lista[0]->zv_direccionnumero.' '.$this->lista[0]->zv_direccincomplemento.' '.$this->lista[0]->zv_direccioncodigopostal.' '.$this->lista[0]->zv_direccionciudad.' '.$this->lista[0]->zv_direccionprovincia.' '.$this->lista[0]->zv_direccionpais;?></p>
    <p><strong>Areas de Interés: </strong><?php echo $this->lista[0]->zv_areainteres = str_replace(",", ", ", $this->lista[0]->zv_areainteres);?></p>
    <p><strong>Perfil:</strong><?php echo ($this->lista[0]->zv_perfilacadmico);?></p>

    </div>
</div>
