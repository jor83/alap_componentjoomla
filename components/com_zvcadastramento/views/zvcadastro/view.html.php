<?php

/**
 * @version     1.0.0
 * @package     com_zvcadastramento
 * @copyright   Copyright (C) 2015. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 * @author      Zé Vicente <josevicente.sistemas@gmail.com> - http://www.zevicente.com.br
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Zvcadastramento.
 */
class ZvcadastramentoViewZvcadastro extends JViewLegacy
{

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $this->idUsuario = JFactory::getUser()->get('id');

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->params = $app->getParams('com_zvcadastramento');

        $this->dados = $this->getModel()->buscaDadosUsuario($this->idUsuario);

        if($this->dados->zv_autorizodivulgarmisdatosporalap != ''){
            $dados = explode(',',$this->dados->zv_autorizodivulgarmisdatosporalap);

            foreach($dados as $value){
                $this->autorizo[$value] = $value;
            }
        }

        if($this->dados->zv_areainteres != ''){
            $dados = explode(',',$this->dados->zv_areainteres);

            foreach($dados as $value){
                $this->areainteres[$value] = $value;
            }
        }

        //Check for errors .
       /*if (count($errors = $this->get('Errors'))) {
           throw new Exception(implode("\n", $errors));
       }*/

        $this->_prepareDocument();
        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_ZVCADASTRAMENTO_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}