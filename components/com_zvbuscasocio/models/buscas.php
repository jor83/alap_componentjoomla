<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvbuscasocio
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  2016 José Vicente Martins
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Zvbuscasocio records.
 *
 * @since  1.6
 */
class ZvbuscasocioModelBuscas extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->getUserStateFromRequest('limitstart', 'limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');

		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');

		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		

		if (isset($list['ordering']))
		{
			$this->setState('list.ordering', $list['ordering']);
		}

		if (isset($list['direction']))
		{
			$this->setState('list.direction', $list['direction']);
		}
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_ZVBUSCASOCIO_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}




	public function zvBuscaSocio(){

		$db = $this->getDbo();

		$nome = JFactory::getApplication()->input->getString('txtNombre');
		$apelido = JFactory::getApplication()->input->getString('txtApellido');
		$cidade = JFactory::getApplication()->input->getString('txtCiudad');
		$estado = JFactory::getApplication()->input->getString('txtProvincia');
		$pais = JFactory::getApplication()->input->getString('txtPais');
		@$areas = $_POST['cid'];

		$tam = count($areas);

		$sql = "SELECT
				zv_users.`name`,
				zv_users.username,
				zv_users.email,
				zv_zvcadastramento.id,
				zv_zvcadastramento.zv_direccionpais,
				zv_zvcadastramento.zv_direccionprovincia,
				zv_zvcadastramento.zv_apellidopadre,
				zv_zvcadastramento.zv_autorizodivulgarmisdatosporalap,
				zv_zvcadastramento.zv_telefono,
				zv_zvcadastramento.zv_institucion,
				zv_zvcadastramento.zv_titulogrado,
				zv_zvcadastramento.zv_titulopostgrado,
				zv_zvcadastramento.zv_areainteres,
				zv_zvcadastramento.zv_perfilacadmico
				FROM
				zv_users
				INNER JOIN zv_zvcadastramento ON zv_users.id = zv_zvcadastramento.id
				WHERE
				zv_users.`name` LIKE '%$nome%'";

				if(!empty($apelido)){
					$sql .= " AND zv_apellidopadre LIKE '%$apelido%'";
				}

				if(!empty($cidade)){
					$sql .= " AND zv_direccionciudad LIKE '%$cidade%'";
				}

		        if(!empty($estado)){
					$sql .= " AND zv_direccionciudad LIKE '%$estado%'";
				}

		        if(!empty($pais)){
					$sql .= " AND zv_direccionpais LIKE '%$pais%'";
				}



				for($x=0;$x<$tam;$x++){
					$sql .=  "AND `zv_areainteres` LIKE '%$areas[$x]%'";
				}



		$sql .= "AND
				 zv_zvcadastramento.zv_socio = 1
				 ORDER BY name";
				 
				 

		$db->setQuery($sql);
		$lista = $db->loadObjectList();
		return $lista;


	}// fim do metodo //





	function getResults(){
		//$nombre,$apellido,$ciudad,$provincia,$areas,$pais
		$db = $this->getDbo();

		$nombre = JFactory::getApplication()->input->getString('txtNombre');
		$apellido = JFactory::getApplication()->input->getString('txtApellido');
		$ciudad = JFactory::getApplication()->input->getString('txtCiudad');
		$provincia = JFactory::getApplication()->input->getString('txtProvincia');
		//$areas = JFactory::getApplication()->input->getString('txtProvincia');
		$pais = JFactory::getApplication()->input->getString('txtPais');

		$query = "
			SELECT
				CONCAT(u.name, ' ', cp.zv_apellidopadre,' ', cp.zv_apellidomadre) AS name,
				u.name AS firstname,
				u.email AS email,
				cp.zv_apellidopadre AS apellidopadre,
				cp.zv_apellidomadre AS apellidomadre,
				CONCAT(cp.zv_apellidopadre,' ',cp.zv_apellidomadre) AS apellidos,
				cp.zv_telefono AS telefono,
				cp.zv_direccion AS direccion,
				cp.zv_direccionnumero AS numero,
				CONCAT(cp.zv_direccion,' ', cp.zv_direccionnumero) AS direccionCompleta,
				cp.zv_perfilacadmico AS perfil,
				IF(INSTR(cp.zv_autorizodivulgarmisdatosporalap,'instituc')>0,
					cp.zv_institucion ,-1)AS institucion
				,
				cp.zv_areainteres AS areas,
				cp.zv_direccionprovincia AS provincia,
				cp.user_id AS id

			FROM zv_zvcadastramento cp
			LEFT JOIN zv_users u ON cp.user_id = u.id

			WHERE
				cp.zv_socio = 1 AND
				u.name != 'administrator' AND
				IF(INSTR(cp.zv_autorizodivulgarmisdatosporalap,'nombre')>0,
					IF('' != '".$nombre."',
						u.name like '%".$nombre."%'
					,u.name like '%%')
					AND
					IF('' != '".$apellido."',
						cp.zv_apellidomadre like '%".$apellido."%'
								OR cp.zv_apellidopadre like '%".$apellido."%'
							,cp.zv_apellidomadre like '%%'
								OR cp.zv_apellidopadre like '%%'
							)

				,'')
					AND

					IF('' != '".$ciudad."',
						cp.zv_direccionciudad like '%".$ciudad."%'
					,cp.zv_direccionciudad like '%%')

					AND

					IF('' != '".$provincia."',
							cp.zv_direccionprovincia like '%".$provincia."%'
					,cp.zv_direccionprovincia like '%%')

					AND

					IF('' != '".$pais."',
							cp.zv_direccionpais like '%".$pais."%'
					,cp.zv_direccionpais like '%%')

			";

		/*
		foreach($areas as $area){
			foreach($fields as $field){
				if($field->fieldvalueid == $area){
					$query.=
						"AND
							IF(INSTR(cp.zv_autorizodivulgarmisdatosporalap,'reas de inter') > 0,
								cp.zv_areainteres like'%".$field->fieldtitle."%'
							,'')";
				}
			}
		}*/

		$query .= " ORDER BY name";


		$users = $this->_getList($query);

		return $users;
	}




	public function detalhaUsuario(){

		$user_id = JFactory::getApplication()->input->getInt('user_id');
		//
		$db = $this->getDbo();

		$sql="SELECT
				u.id,
				u.`name`,
				u.username,
				u.email,
				cad.id,
				cad.zv_telefono,
				cad.zv_institucion,
				cad.zv_direccincomplemento,
				cad.zv_direccionpais,
				cad.zv_direccion,
				cad.zv_direccionnumero,
				cad.zv_direccionbarrio,
				cad.zv_direccionciudad,
				cad.zv_direccionprovincia,
				cad.zv_direccioncodigopostal,
				cad.zv_areainteres,
				cad.zv_perfilacadmico
				FROM
				zv_users AS u
				INNER JOIN zv_zvcadastramento AS cad ON u.id = cad.id
				WHERE
				u.id = {$user_id}";

		$db->setQuery($sql);
		$lista = $db->loadObjectList();
		return $lista;

	}// fim do método //


}// fim da classe //
