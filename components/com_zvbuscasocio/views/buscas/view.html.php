<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvbuscasocio
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  2016 José Vicente Martins
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Zvbuscasocio.
 *
 * @since  1.6
 */
class ZvbuscasocioViewBuscas extends JViewLegacy
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
		$this->params     = $app->getParams('com_zvbuscasocio');
		

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}



		$nome = JFactory::getApplication()->input->getString('txtNombre');
		$apelido = JFactory::getApplication()->input->getString('txtApellido');
		$cidade = JFactory::getApplication()->input->getString('txtCiudad');
		$estado = JFactory::getApplication()->input->getString('txtProvincia');
		$pais = JFactory::getApplication()->input->getString('txtPais');
		@$areas = $_POST['cid'];

		//
		$model =& $this->getModel();

		// verifica se houve argumento para a pesquisa //
		if(empty($nome) && empty($apelido) && empty($cidade) && empty($estado) && empty($pais) && empty($areas)){
			//echo 'todos vazios';
		}else{
			$this->lista = $model->zvBuscaSocio();
			//var_dump($this->lista);
			//$lista = $model->getResults();

			// verifica resultado para redirecionar para lista //
			if(empty($this->lista)){
                           echo $erro = JFactory::getApplication()->enqueueMessage('No se encontraron resultados');
			}else{
				$this->setLayout('listagem');

			}
		}

		$view = JFactory::getApplication()->input->getInt('view');
		$layout = JFactory::getApplication()->input->getInt('layout');
		//
		if( ($view == 'buscas') && ($layout == 'detalha')){
			$this->lista = $model->detalhaUsuario();
			$this->setLayout('detalha');
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
			$this->params->def('page_heading', JText::_('COM_ZVBUSCASOCIO_DEFAULT_PAGE_TITLE'));
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
}
