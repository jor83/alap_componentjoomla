<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Zvcuotas records.
 *
 * @since  1.6
 */
class ZvcuotasModelCuotasusuarios extends JModelList
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
			$app->enqueueMessage(JText::_("COM_ZVCUOTAS_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		parent::loadFormData();
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



	/*
	 * verifica se usuário é sócio
	 */
	public function verificaSeSocio(){

		$usuario_id = JFactory::getUser()->id;
		$db = $this->getDbo();

		$sql = "SELECT zv_socio FROM #__zvcadastramento WHERE `id` = $usuario_id";

		$db->setQuery($sql);
		$lt = $db->loadObjectList();
		return $lt[0]->zv_socio;



	}// fim do metodo //


	//trae la cotizacion de una moneda de finance.yahoo.com
	function getContizacion($cur_from,$cur_to){
		if(strlen($cur_from)==0){
			$cur_from = "USD";
		}
		if(strlen($cur_to)==0){
			$cur_from = "CAD";
		}
		$host="download.finance.yahoo.com";
		$fp = @fsockopen($host, 80, $errno, $errstr, 30);
		if (!$fp) {
			$errorstr="$errstr ($errno)<br />\n";
			return false;
		} else {
			$data = '';
			$file="/d/quotes.csv";
			$str = "?s=".$cur_from.$cur_to."=X&f=sl1d1t1ba&e=.csv";
			$out = "GET ".$file.$str." HTTP/1.0\r\n";
			$out .= "Host: download.finance.yahoo.com\r\n";
			$out .= "Connection: Close\r\n\r\n";

			@fputs($fp, $out);
			while (!@feof($fp))
			{
				$data .= @fgets($fp, 128);
			}

			@fclose($fp);
			@preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $data, $match);
			$data =$match[2];
			$search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
			$replace = array ("","","\\1","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\\1)");
			$data = @preg_replace($search, $replace, $data);
			$result = split(",",$data);
			return $result[1];

		}
	}


	public function listaCuotasPagas(){

		$usuario_id = JFactory::getUser()->id;
		$db = $this->getDbo();

		$sql= "SELECT * FROM  #__cuotas_pagos WHERE id_usuario = $usuario_id AND isPagado = 1 ORDER BY anio DESC";

		$db->setQuery($sql);
		return $lt = $db->loadObjectList();

	}// fim do metodo //


	public function cuotasDisponiveis(){

		$usuario_id = JFactory::getUser()->id;
		$db = $this->getDbo();

		$sql= "SELECT * FROM  #__cuotas_pagos WHERE id_usuario = $usuario_id AND isPagado = 0";

		$db->setQuery($sql);
		return $lt = $db->loadObjectList();

	}// fim do metodo //



    function getContizacion2017($cur_from,$cur_to){
        if(strlen($cur_from)==0){
            $cur_from = "USD";
        }
        if(strlen($cur_to)==0){
            $cur_from = "CAD";
        }

        $yql_base_url = "http://query.yahooapis.com/v1/public/yql";
        $yql_query = 'select * from csv where url="br.financas.yahoo.com/quote/USDBRL=X?ltr=1"';
        $yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query);
        $yql_query_url .= "&format=json";

//echo $yql_query_url;

        //$yql_query_url="https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20csv%20where%20url%3D%22br.financas.yahoo.com%2Fquote%2FUSDBRL%3DX%3Fltr%3D1%22%3B&format=json&diagnostics=true&callback=yahoo";
        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        $json = curl_exec($session);
        //
        $phpObj =  json_decode($json);
        //echo '<pre>';
        //var_dump(serialize($phpObj->query->results));
        //echo '</pre>';


       $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://br.financas.yahoo.com/quote/USDBRL=X?ltr=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $pagina =  curl_exec($ch);

        //preg_match('#react-text:\s*36\s*-->(\d{1}\,\d+)<#si',$pagina,$out);
          preg_match('#data-reactid=\"35\">(\d{1}\,\d+)<\/span>#si',$pagina,$out);

        $cotacao = str_replace(',','.',$out[1]);
        return $cotacao;
        //return 3.41;

        /*
        $fp = @fsockopen($host, 80, $errno, $errstr, 30);
        if (!$fp) {
            $errorstr="$errstr ($errno)<br />\n";
            return false;
        } else {
            $data = '';
            $file="/d/quotes.csv";
            $str = "?s=".$cur_from.$cur_to."=X&f=sl1d1t1ba&e=.csv";
            $out = "GET ".$file.$str." HTTP/1.0\r\n";
            $out .= "Host: download.finance.yahoo.com\r\n";
            $out .= "Connection: Close\r\n\r\n";

            @fputs($fp, $out);
            while (!@feof($fp))
            {
                $data .= @fgets($fp, 128);
            }

            @fclose($fp);
            @preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $data, $match);
            $data =$match[2];
            $search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
            $replace = array ("","","\\1","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\\1)");
            $data = @preg_replace($search, $replace, $data);
            $result = split(",",$data);
            return $result[1];

        }
        */
    }







}// fim da classe //
