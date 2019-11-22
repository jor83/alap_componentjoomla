<?php

/**
 * @version     1.0.0
 * @package     com_zvcadastramento
 * @copyright   Copyright (C) 2015. Todos os direitos reservados.
 * @license     GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 * @author      Zé Vicente <josevicente.sistemas@gmail.com> - http://www.zevicente.com.br
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Zvcadastramento records.
 */
class ZvcadastramentoModelZvcadastro extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		/*
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				                'id', 'a.id',
                'user_id', 'a.user_id',
                'zv_name', 'a.zv_name',
                'zv_apellidopadre', 'a.zv_apellidopadre',
                'zv_email', 'a.zv_email',
                'zv_usuario', 'a.zv_usuario',
                'zv_password', 'a.zv_password',
                'zv_password_verify', 'a.zv_password_verify',
                'zv_direccionpais', 'a.zv_direccionpais',
                'zv_tipodeafiliacin', 'a.zv_tipodeafiliacin',
                'zv_tipodocumento', 'a.zv_tipodocumento',
                'zv_documento', 'a.zv_documento',
                'zv_nacionalidad', 'a.zv_nacionalidad',
                'zv_fechadenacimiento', 'a.zv_fechadenacimiento',
                'zv_sexo', 'a.zv_sexo',
                'zv_telefono', 'a.zv_telefono',
                'zv_avatar', 'a.zv_avatar',
                'zv_aodeafiliacion', 'a.zv_aodeafiliacion',
                'zv_direccincomplemento', 'a.zv_direccincomplemento',
                'zv_apellidomadre', 'a.zv_apellidomadre',
                'zv_profesion', 'a.zv_profesion',
                'zv_tipomembresia', 'a.zv_tipomembresia',
                'zv_estadocivil', 'a.zv_estadocivil',
                'zv_emailproblematico', 'a.zv_emailproblematico',
                'zv_fechaactualizaciondatos', 'a.zv_fechaactualizaciondatos',
                'zv_fechadebaja', 'a.zv_fechadebaja',
                'zv_direccion', 'a.zv_direccion',
                'zv_direccionnumero', 'a.zv_direccionnumero',
                'zv_direccionbarrio', 'a.zv_direccionbarrio',
                'zv_direccionciudad', 'a.zv_direccionciudad',
                'zv_direccionprovincia', 'a.zv_direccionprovincia',
                'zv_direccioncodigopostal', 'a.zv_direccioncodigopostal',
                'zv_ddi', 'a.zv_ddi',
                'zv_fax', 'a.zv_fax',
                'zv_emaillaboral', 'a.zv_emaillaboral',
                'zv_direccionlaboralnumero', 'a.zv_direccionlaboralnumero',
                'zv_institucion', 'a.zv_institucion',
                'zv_direccionlaboral', 'a.zv_direccionlaboral',
                'zv_direccionlaboralcomplemento', 'a.zv_direccionlaboralcomplemento',
                'zv_direcionlaboralbarrio', 'a.zv_direcionlaboralbarrio',
                'zv_direccionlaboralciudad', 'a.zv_direccionlaboralciudad',
                'zv_direccionlaboralprovincia', 'a.zv_direccionlaboralprovincia',
                'zv_direcionlaboralpais', 'a.zv_direcionlaboralpais',
                'zv_direccionlaboralcodigopostal', 'a.zv_direccionlaboralcodigopostal',
                'zv_ddilaboral', 'a.zv_ddilaboral',
                'zv_telefonolaboral', 'a.zv_telefonolaboral',
                'zv_faxlaboral', 'a.zv_faxlaboral',
                'zv_titulogrado', 'a.zv_titulogrado',
                'zv_titulopostgrado', 'a.zv_titulopostgrado',
                'zv_titulonoaplicado', 'a.zv_titulonoaplicado',
                'zv_areainteres', 'a.zv_areainteres',
                'zv_instnet', 'a.zv_instnet',
                'zv_direccioninstnet', 'a.zv_direccioninstnet',
                'zv_direccionparticularnet', 'a.zv_direccionparticularnet',
                'zv_emailpersonalnet', 'a.zv_emailpersonalnet',
                'zv_emaillaboralnet', 'a.zv_emaillaboralnet',
                'zv_areasujest', 'a.zv_areasujest',
                'zv_areanoaplicado', 'a.zv_areanoaplicado',
                'zv_perteneceasociacion', 'a.zv_perteneceasociacion',
                'zv_asocnacionalpoblacion', 'a.zv_asocnacionalpoblacion',
                'zv_perfilacadmico', 'a.zv_perfilacadmico',
                'zv_autorizodivulgarmisdatosporalap', 'a.zv_autorizodivulgarmisdatosporalap',
                'zv_socio', 'a.zv_socio',
                'zv_otras', 'a.zv_otras',
                'zv_fechaaprobacion', 'a.zv_fechaaprobacion',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',

			);
		*/

		parent::__construct($config);
	}

	public function buscaDadosUsuario($idUsers){
		$db =& JFactory::getDbo();
		$sql = "
		SELECT
			users.id,
			users.`name`,
			users.username,
			users.email,
			cad.zv_name,
			cad.zv_apellidopadre,
			cad.zv_direccionpais,
			cad.zv_tipodeafiliacin,
			cad.zv_tipodocumento,
			cad.zv_documento,
			cad.zv_nacionalidad,
			DATE_FORMAT(cad.zv_fechadenacimiento,'%d/%m/%Y') as zv_fechadenacimiento,
			cad.zv_sexo,
			cad.zv_telefono,
			cad.zv_avatar,
			cad.zv_aodeafiliacion,
			cad.zv_direccincomplemento,
			cad.zv_apellidomadre,
			cad.zv_profesion,
			cad.zv_tipomembresia,
			cad.zv_estadocivil,
			cad.zv_emailproblematico,
			DATE_FORMAT(cad.zv_fechaactualizaciondatos,'%d/%m/%Y') as zv_fechaactualizaciondatos,
			DATE_FORMAT(cad.zv_fechadebaja,'%d/%m/%Y') as zv_fechadebaja,
			cad.zv_direccion,
			cad.zv_direccionnumero,
			cad.zv_direccionbarrio,
			cad.zv_direccionciudad,
			cad.zv_direccionprovincia,
			cad.zv_direccioncodigopostal,
			cad.zv_ddi,
			cad.zv_fax,
			cad.zv_emaillaboral,
			cad.zv_direccionlaboralnumero,
			cad.zv_institucion,
			cad.zv_direccionlaboral,
			cad.zv_direccionlaboralcomplemento,
			cad.zv_direcionlaboralbarrio,
			cad.zv_direccionlaboralciudad,
			cad.zv_direccionlaboralprovincia,
			cad.zv_direcionlaboralpais,
			cad.zv_direccionlaboralcodigopostal,
			cad.zv_ddilaboral,
			cad.zv_telefonolaboral,
			cad.zv_faxlaboral,
			cad.zv_titulogrado,
			cad.zv_titulopostgrado,
			cad.zv_titulonoaplicado,
			cad.zv_areainteres,
			cad.zv_instnet,
			cad.zv_direccioninstnet,
			cad.zv_direccionparticularnet,
			cad.zv_emailpersonalnet,
			cad.zv_emaillaboralnet,
			cad.zv_areasujest,
			cad.zv_areanoaplicado,
			cad.zv_perteneceasociacion,
			cad.zv_asocnacionalpoblacion,
			cad.zv_perfilacadmico,
			cad.zv_autorizodivulgarmisdatosporalap,
			cad.zv_socio,
			cad.zv_otras,
			DATE_FORMAT(cad.zv_fechaaprobacion,'%d/%m/%Y') as zv_fechaaprobacion
		FROM
			zv_users AS users
			INNER JOIN zv_zvcadastramento AS cad ON users.id = cad.user_id
		WHERE
			users.id = {$idUsers}
		";

		$db->setQuery($sql);
		$db->execute();
		return $db->loadObject();
	}









}// fim da classe //
