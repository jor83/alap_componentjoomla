<?php
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
error_reporting(E_ALL);
/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Zvcuotas.
 *
 * @since  1.6
 */
class ZvcuotasViewCuotasusuarios extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$this->state      = $this->get('State');
		$this->params     = $app->getParams('com_zvcuotas');
		

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}


		/*
        $view = JFactory::getApplication()->input->getWord('view');
        if($view == 'cuotasusuarios'){
           $socio = $model->verificaSeSocio();
           if($socio == 0){
               $this->setLayout('naosocio');
           }else{
               $this->socio = 1;
           }
        }*/

        $model = $this->getModel();
        $this->cotacaoDolar = $model->getContizacion2017("USD","BRL");
        if(empty($this->cotacaoDolar)){
            $this->cotacaoDolar = $this->getMaiorCotacaoDia();
        }else{
            $this->setCotacaoPorAcesso($this->cotacaoDolar);
        }


        // buscando cuotas pagas pelo usuario //
        $this->cuotasPagas = $model->listaCuotasPagas();

        // buscando cuotas disponiveis para pagamento //
        $this->cuotasDisponiveis = $model->cuotasDisponiveis();



        $token = JFactory::getApplication()->input->getString('token');
        $PayerID = JFactory::getApplication()->input->getString('PayerID');

        if(!empty($token) && (!empty($PayerID))){
            header('Location: index.php?option=com_zvcuotas&view=msg&layout=sucesso&06a3b4bf416dd7fc79967712980baaa9=06a3b4bf416dd7fc79967712980baaa9&94a08da1fecbb6e8b46990538c7b50b2='.$token.'&4b5546b16be0ea35d07fbe1f2342e527='.$PayerID.'&Itemid=763');
        }

		$this->_prepareDocument();
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_ZVCUOTAS_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}


    public function setCotacaoPorAcesso($cotacao){
        $db = JFactory::getDbo();
        //
        $sql="INSERT INTO `zv_cotacao_dia`(`cotacao_dia`, `data_cotacao`) VALUES ({$cotacao}, NOW())";
        $db->setQuery($sql);
        $db->execute();
    }

    public function getMaiorCotacaoDia($cotacao){
        $db = JFactory::getDbo();
        //
        $maior_data = $this->getMaiorData();

        //echo $sql="SELECT cotacao_dia FROM `wwwalapo_8alap`.`zv_cotacao_dia` WHERE data_cotacao = '{$maior_data}' ORDER BY data_cotacao DESC LIMIT 0,1";
        $sql="SELECT * FROM `wwwalapo_zvalapop`.`zv_cotacao_dia` WHERE `data_cotacao` = '{$maior_data}' LIMIT 0, 1000";
        $db->setQuery($sql);
        //$db->execute();
        $res = $db->loadObject();
        return $res->cotacao_dia;
    }

    public function getMaiorData(){
        $db = JFactory::getDbo();
        //
        $sql="SELECT MAX(data_cotacao) as maior_data FROM zv_cotacao_dia";
        $db->setQuery($sql);
        //$db->execute();
        $res = $db->loadObject();
        return $res->maior_data;
    }


}
