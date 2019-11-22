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

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Zvcadastro list controller class.
 */
class ZvcadastramentoControllerZvcadastro extends ZvcadastramentoController
{
    /**
     * Proxy for getModel.
     * @since    1.6
     */
    public function &getModel($name = 'Zvcadastro', $prefix = 'ZvcadastramentoModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    public function recebeDados()
    {


        // Datos personales //
        $name = JFactory::getApplication()->input->getString('name');
        $email = JFactory::getApplication()->input->getString('email');

        // verifica se e-mnail é único //
        $validaEmail = $this->validaEmail($email);
        if (!empty($validaEmail)) {
            $mensagem = 'Descule! Ya existe un registro para esa cuenta de correo electrónico.';
            $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&Itemid=60', $mensagem, 'Error');
            return false;
        }

        $username = JFactory::getApplication()->input->get('username');
        // validando username unico //
        $validaUsername = $this->validaNomeDeUsuario($username);
        if (!empty($validaUsername)) {
            $mensagem = 'Descule! Ya existe un registro con el nombre escrito!';
            $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&Itemid=60', $mensagem, 'Error');
            return false;
        }

        // validando senhas //
        $password = JFactory::getApplication()->input->getString('password');
        $password__verify = JFactory::getApplication()->input->getString('password__verify');

        if ($password == $password__verify) {
            $senha_validada = $password;
        } else {
            $mensagem = '¡Lo siento! Las contraseñas no coinciden.';
            $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&Itemid=60', $mensagem, 'Warning');
        }

        // id usuario Joomla //
		$zv_apellidopadre = JFactory::getApplication()->input->getString('zv_apellidopadre');
		$name_joomla = ($name.' '.$zv_apellidopadre);
        $idUsuario = $this->newUSerJoomla($name_joomla, $email, $username, $senha_validada);

        // gravando dados na tabela ZVCadastro //
        $zvCadastro = $this->gravaDadosZvCadastramento($idUsuario);
        if ($zvCadastro == 1) {
            $mensagem = 'éxito de inscripción completo requiere! <br>Por favor, espere liberación de nuestra gestión y pronto será notificado por correo electrónico  .' . $idUsuario;
            $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&Itemid=60', $mensagem, 'Warning');
        } else {
            $mensagem = '¡Lo siento! hubo un error de realizar su inscripción. Busque un administrador.';
            $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&Itemid=60', $mensagem, 'Warning');
        }


    }// fim do metodo //


    public function newUSerJoomla($name, $email, $username, $senha_validada)
    {
        // criando novo usuário do Joomla!!! //

        $db = JFactory::getDbo();

        date_default_timezone_set('America/Sao_Paulo');
        $registerDate = date("Y-m-d H:i:s");
        $params = '{}';

        // salt na senha //
        $salt = JUserHelper::genRandomPassword(32);
        $crypt = JUserHelper::getCryptedPassword($senha_validada, $salt);
        $password = $crypt . ':' . $salt;

        $lastResetTime = '0000-00-00 00:00:00';
        $resetCount = 0;
        $otpKey = '';
        $otep = '';
        $requireReset = 0;


        $sql = "INSERT INTO #__users
                (
                id ,
			    name,
				username,
				email,
				password,
				block,
				sendEmail,
				registerDate,
				lastvisitDate,
				activation,
				params,
				lastResetTime,
				resetCount,
				otpKey,
				otep,
				requireReset
				)
                VALUES
                (
                NULL,
                '{$name}',
                '{$username}',
                '{$email}',
                '{$password}',
                '1',
                '0',
                '{$registerDate}',
                '0000-00-00 00:00:00',
                '',
                '{$params}',
                '{$lastResetTime}',
                $resetCount,
                '{$otpKey}',
                '{$otep}',
                $requireReset
				)
                ";

        $db->setQuery($sql);
        $resUser = $db->execute();
        // usuario gravado no Joomla! //
        $idUser = $db->insertid();

        // inserindo usuario na tabela de grupos comno registrado //
        $sql1 = "INSERT INTO
		     	#__user_usergroup_map(user_id, group_id)
                VALUES
                ({$idUser},2);";

        $db->setQuery($sql1);
        $db->execute();

        // envia email para secretaria avisando novo cadastro //
        $this->enviaEmailNovoCadastro($name,$username);


        return $idUser;


    }// fim do metodo //


    public function gravaDadosZvCadastramento($idUsuario)
    {

        $name = JFactory::getApplication()->input->getString('name');
		$zv_apellidopadre = JFactory::getApplication()->input->getString('zv_apellidopadre');
        $zv_direccionpais = JFactory::getApplication()->input->getString('zv_direccionpais');
        $zv_tipodeafiliacin = JFactory::getApplication()->input->getString('zv_tipodeafiliacin');
        $zv_tipodocumento = JFactory::getApplication()->input->getString('zv_tipodocumento');
        $zv_documento = JFactory::getApplication()->input->getString('zv_documento');
        $zv_nacionalidad = JFactory::getApplication()->input->getString('zv_nacionalidad');
        $zv_fechadenacimiento = $this->formatDataBanco(JFactory::getApplication()->input->getString('zv_fechadenacimiento'));
        $zv_sexo = JFactory::getApplication()->input->getString('zv_sexo');
        $zv_telefono = JFactory::getApplication()->input->getString('zv_telefono');

        // Datos postales //
        $zv_direccion = JFactory::getApplication()->input->getString('zv_direccion');
        $zv_direccionnumero = JFactory::getApplication()->input->getString('zv_direccionnumero');
        $zv_direccincomplemento = JFactory::getApplication()->input->getString('zv_direccincomplemento');
        $zv_direccionbarrio = JFactory::getApplication()->input->getString('zv_direccionbarrio');
        $zv_direccionciudad = JFactory::getApplication()->input->getString('zv_direccionciudad');
        $zv_direccionprovincia = JFactory::getApplication()->input->getString('zv_direccionprovincia');
        $zv_direccioncodigopostal = JFactory::getApplication()->input->getString('zv_direccioncodigopostal');
        $zv_autorizodivulgarmisdatosporalap = JFactory::getApplication()->input->get('zv_autorizodivulgarmisdatosporalap', '', 'ARRAY');

        // tratando Array //

        $tam = count($zv_autorizodivulgarmisdatosporalap);
        foreach ($zv_autorizodivulgarmisdatosporalap as $x => $p) {
            $valor .= $p;
            if ($x + 1 < $tam) {
                $valor .= ',';
            }
        }
        $zv_autorizodivulgarmisdatosporalap = $valor;

        // Datos Laborales:  //
        $zv_institucion = JFactory::getApplication()->input->getString('zv_institucion');
        $zv_telefonolaboral = JFactory::getApplication()->input->getString('zv_telefonolaboral');
        $zv_faxlaboral = JFactory::getApplication()->input->getString('zv_faxlaboral');
        $zv_emaillaboral = JFactory::getApplication()->input->getString('zv_emaillaboral');

        // Dirección: //
        $zv_direccionlaboral = JFactory::getApplication()->input->getString('zv_direccionlaboral');
        $zv_direccionlaboralnumero = JFactory::getApplication()->input->getString('zv_direccionlaboralnumero');
        $zv_direccionlaboralcomplemento = JFactory::getApplication()->input->getString('zv_direccionlaboralcomplemento');
        $zv_direcionlaboralbarrio = JFactory::getApplication()->input->getString('zv_direcionlaboralbarrio');
        $zv_direccionlaboralciudad = JFactory::getApplication()->input->getString('zv_direccionlaboralciudad');
        $zv_direccionlaboralprovincia = JFactory::getApplication()->input->getString('zv_direccionlaboralprovincia');
        $zv_direcionlaboralpais = JFactory::getApplication()->input->getString('zv_direcionlaboralpais');
        $zv_direccionlaboralcodigopostal = JFactory::getApplication()->input->getString('zv_direccionlaboralcodigopostal');

        // Datos académicos //
        $zv_titulopostgrado = JFactory::getApplication()->input->getString('zv_titulopostgrado');
        $zv_titulogrado = JFactory::getApplication()->input->getString('zv_titulogrado');
        $zv_profesion = JFactory::getApplication()->input->getString('zv_profesion');
        $zv_perfilacadmico = JFactory::getApplication()->input->getString('zv_perfilacadmico');


        // Otros datos: //
        // tratando Array //
        $zv_areainteres = JFactory::getApplication()->input->get('zv_areainteres', '', 'ARRAY');
        $tam = count($zv_areainteres);
        foreach ($zv_areainteres as $x => $p) {
            $valor2 .= $p;
            if ($x + 1 < $tam) {
                $valor2 .= ',';
            }
        }
        $zv_areainteres = $valor2;

        $zv_perteneceasociacion = JFactory::getApplication()->input->getString('zv_perteneceasociacion');
        $zv_asocnacionalpoblacion = JFactory::getApplication()->input->getString('zv_asocnacionalpoblacion');
        $zv_otras = JFactory::getApplication()->input->getString('zv_otras');

        // outros campos LEGADO //
        $zv_aodeafiliacion = date('Y'); // ano de filiação //
        $zv_tipomembresia = '';// no banco se individual ou individual/estudante;
        $zv_avatar = '';
        $zv_emailproblematico = '';
        $zv_estadocivil = '';
        $zv_fechaactualizaciondatos = '0000-00-00';
        $zv_fechadebaja = '0000-00-00';
        $zv_ddi = '';
        $zv_fax = '';
        $zv_ddilaboral = '';
        $zv_titulonoaplicado = 0;// no banco tem 0 ou -1;
        $zv_instnet = 0;
        $zv_direccioninstnet = 0;
        $zv_direccionparticularnet = 0;
        $zv_emailpersonalnet = 0;
        $zv_emaillaboralnet = 0;
        $zv_areasujest = '';
        $zv_areanoaplicado = 0;
        $zv_socio = 0; // valor inicial 0 e quando clicar na aprovação 1;
        $zv_fechaaprobacion = '0000-00-00'; // data de aprovação //

        $sql = "INSERT INTO #__zvcadastramento
       (id, user_id, zv_name, zv_apellidopadre, zv_direccionpais, zv_tipodeafiliacin,
       zv_tipodocumento, zv_documento, zv_nacionalidad, zv_fechadenacimiento, zv_sexo, zv_telefono, zv_avatar, zv_aodeafiliacion,
       zv_direccincomplemento, zv_apellidomadre, zv_profesion, zv_tipomembresia, zv_estadocivil, zv_emailproblematico,
       zv_fechaactualizaciondatos, zv_fechadebaja, zv_direccion, zv_direccionnumero, zv_direccionbarrio, zv_direccionciudad,
       zv_direccionprovincia, zv_direccioncodigopostal, zv_ddi, zv_fax, zv_emaillaboral, zv_direccionlaboralnumero, zv_institucion,
       zv_direccionlaboral, zv_direccionlaboralcomplemento, zv_direcionlaboralbarrio, zv_direccionlaboralciudad, zv_direccionlaboralprovincia,
       zv_direcionlaboralpais, zv_direccionlaboralcodigopostal, zv_ddilaboral, zv_telefonolaboral, zv_faxlaboral, zv_titulogrado,
       zv_titulopostgrado, zv_titulonoaplicado, zv_areainteres, zv_instnet, zv_direccioninstnet, zv_direccionparticularnet, zv_emailpersonalnet,
       zv_emaillaboralnet, zv_areasujest, zv_areanoaplicado, zv_perteneceasociacion, zv_asocnacionalpoblacion, zv_perfilacadmico,
       zv_autorizodivulgarmisdatosporalap, zv_socio, zv_otras, zv_fechaaprobacion)
       VALUES
       (
       '{$idUsuario}', '{$idUsuario}', '{$name}', '{$zv_apellidopadre}', '{$zv_direccionpais}',
       '{$zv_tipodeafiliacin}', '{$zv_tipodocumento}', '{$zv_documento}', '${zv_nacionalidad}',
       '{$zv_fechadenacimiento}', '{$zv_sexo}', '{$zv_telefono}','${zv_avatar}', '{$zv_aodeafiliacion}', '{$zv_direccincomplemento}', '{$zv_apellidomadre}',
       '{$zv_profesion}', '{$zv_tipomembresia}', '{$zv_estadocivil}', '{$zv_emailproblematico}', '{$zv_fechaactualizaciondatos}', '{$zv_fechadebaja}', '{$zv_direccion}',
       '{$zv_direccionnumero}', '{$zv_direccionbarrio}', '{$zv_direccionciudad}', '{$zv_direccionprovincia}',
       '{$zv_direccioncodigopostal}', '{$zv_ddi}', '{$zv_fax}','{$zv_emaillaboral}','{$zv_direccionlaboralnumero}','{$zv_institucion}',
       '{$zv_direccionlaboral}', '{$zv_direccionlaboralcomplemento}', '{$zv_direcionlaboralbarrio}', '{$zv_direccionlaboralciudad}',
       '{$zv_direccionlaboralprovincia}', '{$zv_direcionlaboralpais}', '{$zv_direccionlaboralcodigopostal}', '${zv_ddilaboral}',
       '{$zv_telefonolaboral}', '{$zv_faxlaboral}', '{$zv_titulogrado}', '{$zv_titulopostgrado}', '{$zv_titulonoaplicado}',
       '{$zv_areainteres}', '{$zv_instnet}', '{$zv_direccioninstnet}', '{$zv_direccionparticularnet}', '{$zv_emailpersonalnet}',
       '{$zv_emaillaboralnet}', '{$zv_areasujest}', '{$zv_areanoaplicado}', '{$zv_perteneceasociacion}', '{$zv_asocnacionalpoblacion}',
       '{$zv_perfilacadmico}', '{$zv_autorizodivulgarmisdatosporalap}', '{$zv_socio}', '{$zv_otras}', '{$zv_fechaaprobacion}'
       );";

        $db =& JFactory::getDbo();
        if ($db->setQuery($sql)) {
            $db->execute();
            echo $db->getErrorMsg();
            return 1;
        } else {
            echo $db->getErrorMsg();
            return 0;
        }


    }// fim do método //


    public function formatDataBanco($data)
    {
        $data = explode('/', $data);

        $data = $data[2] . '-' . $data[1] . '-' . $data[0];

        return $data;
    }

    public function validaEmail($email)
    {

        $db =& JFactory::getDbo();
        $sql = "SELECT id FROM #__users WHERE email='{$email}'";
        $db->setQuery($sql);
        $db->execute();
        return $db->loadAssocList();

    }// fim do metodo //

    public function validaNomeDeUsuario($username)
    {

        $db =& JFactory::getDbo();
        $sql = "SELECT id FROM #__users WHERE username='{$username}'";
        $db->setQuery($sql);
        $db->execute();
        return $db->loadAssocList();

    }// fim do metodo //

    public function editarCadastro()
    {
        $zv_idusers = JFactory::getApplication()->input->getInt('zv_idusers');
        $zv_name = JFactory::getApplication()->input->getString('zv_name');
        $email = JFactory::getApplication()->input->getString('email');
        $username = JFactory::getApplication()->input->getString('username');
        $zv_apellidopadre = JFactory::getApplication()->input->getString('zv_apellidopadre');
        $zv_direccionpais = JFactory::getApplication()->input->getString('zv_direccionpais');
        $zv_tipodeafiliacin = JFactory::getApplication()->input->getString('zv_tipodeafiliacin');
        $zv_tipodocumento = JFactory::getApplication()->input->getString('zv_tipodocumento');
        $zv_documento = JFactory::getApplication()->input->getString('zv_documento');
        $zv_nacionalidad = JFactory::getApplication()->input->getString('zv_nacionalidad');
        $zv_fechadenacimiento = $this->formatDataBanco(JFactory::getApplication()->input->getString('zv_fechadenacimiento'));
        $zv_sexo = JFactory::getApplication()->input->getString('zv_sexo');
        $zv_telefono = JFactory::getApplication()->input->getString('zv_telefono');

        // Datos postales //
        $zv_direccion = JFactory::getApplication()->input->getString('zv_direccion');
        $zv_direccionnumero = JFactory::getApplication()->input->getString('zv_direccionnumero');
        $zv_direccincomplemento = JFactory::getApplication()->input->getString('zv_direccincomplemento');
        $zv_direccionbarrio = JFactory::getApplication()->input->getString('zv_direccionbarrio');
        $zv_direccionciudad = JFactory::getApplication()->input->getString('zv_direccionciudad');
        $zv_direccionprovincia = JFactory::getApplication()->input->getString('zv_direccionprovincia');
        $zv_direccioncodigopostal = JFactory::getApplication()->input->getString('zv_direccioncodigopostal');
        $zv_autorizodivulgarmisdatosporalap = JFactory::getApplication()->input->get('zv_autorizodivulgarmisdatosporalap', '', 'ARRAY');

        $valor = '';
        $tam = count($zv_autorizodivulgarmisdatosporalap);
        foreach ($zv_autorizodivulgarmisdatosporalap as $x => $p) {
            $valor .= $p;
            if ($x + 1 < $tam) {
                $valor .= ',';
            }
        }
        $zv_autorizodivulgarmisdatosporalap = $valor;

        // Datos Laborales:  //
        $zv_institucion = JFactory::getApplication()->input->getString('zv_institucion');
        $zv_telefonolaboral = JFactory::getApplication()->input->getString('zv_telefonolaboral');
        $zv_faxlaboral = JFactory::getApplication()->input->getString('zv_faxlaboral');
        $zv_emaillaboral = JFactory::getApplication()->input->getString('zv_emaillaboral');

        // Dirección: //
        $zv_direccionlaboral = JFactory::getApplication()->input->getString('zv_direccionlaboral');
        $zv_direccionlaboralnumero = JFactory::getApplication()->input->getString('zv_direccionlaboralnumero');
        $zv_direccionlaboralcomplemento = JFactory::getApplication()->input->getString('zv_direccionlaboralcomplemento');
        $zv_direcionlaboralbarrio = JFactory::getApplication()->input->getString('zv_direcionlaboralbarrio');
        $zv_direccionlaboralciudad = JFactory::getApplication()->input->getString('zv_direccionlaboralciudad');
        $zv_direccionlaboralprovincia = JFactory::getApplication()->input->getString('zv_direccionlaboralprovincia');
        $zv_direcionlaboralpais = JFactory::getApplication()->input->getString('zv_direcionlaboralpais');
        $zv_direccionlaboralcodigopostal = JFactory::getApplication()->input->getString('zv_direccionlaboralcodigopostal');

        // Datos académicos //
        $zv_titulopostgrado = JFactory::getApplication()->input->getString('zv_titulopostgrado');
        $zv_titulogrado = JFactory::getApplication()->input->getString('zv_titulogrado');
        $zv_profesion = JFactory::getApplication()->input->getString('zv_profesion');
        $zv_perfilacadmico = JFactory::getApplication()->input->getString('zv_perfilacadmico');


        // Otros datos: //
        // tratando Array //
        $valor2 = '';
        $zv_areainteres = JFactory::getApplication()->input->get('zv_areainteres', '', 'ARRAY');
        $tam = count($zv_areainteres);

        foreach ($zv_areainteres as $x => $p) {
            $valor2 .= $p;
            if ($x + 1 < $tam) {
                $valor2 .= ',';
            }
        }
        $zv_areainteres = $valor2;

        $zv_perteneceasociacion = JFactory::getApplication()->input->getString('zv_perteneceasociacion');
        $zv_asocnacionalpoblacion = JFactory::getApplication()->input->getString('zv_asocnacionalpoblacion');
        $zv_otras = JFactory::getApplication()->input->getString('zv_otras');

        // outros campos LEGADO //
        $zv_aodeafiliacion = date('Y'); // ano de filiação //
        $zv_tipomembresia = '';// no banco se individual ou individual/estudante;
        $zv_avatar = '';
        $zv_emailproblematico = '';
        $zv_estadocivil = '';
        $zv_fechaactualizaciondatos = '0000-00-00';
        $zv_fechadebaja = '0000-00-00';
        $zv_ddi = '';
        $zv_fax = '';
        $zv_ddilaboral = '';
        $zv_titulonoaplicado = 0;// no banco tem 0 ou -1;
        $zv_instnet = 0;
        $zv_direccioninstnet = 0;
        $zv_direccionparticularnet = 0;
        $zv_emailpersonalnet = 0;
        $zv_emaillaboralnet = 0;
        $zv_areasujest = '';
        $zv_areanoaplicado = 0;
        $zv_socio = 0; // valor inicial 0 e quando clicar na aprovação 1;
        $zv_fechaaprobacion = '0000-00-00'; // data de aprovação //

        $sql1 = "
				UPDATE `zv_users`
				SET
					`name` = '{$zv_name}',
					`username` = '{$username}',
					`email` = '{$email}'
				WHERE `id` = {$zv_idusers};
		";

        $db1 =& JFactory::getDbo();
        if ($db1->setQuery($sql1)) {
            $db1->execute();

            $sql = "
		UPDATE `zv_zvcadastramento`
		SET
			`zv_name` = '{$zv_name}',
			`zv_apellidopadre` = '{$zv_apellidopadre}',
			`zv_direccionpais` = '{$zv_direccionpais}',
			`zv_tipodeafiliacin` = '{$zv_tipodeafiliacin}',
			`zv_tipodocumento` = '{$zv_tipodocumento}',
			`zv_documento` = '{$zv_documento}',
			`zv_nacionalidad` = '{$zv_nacionalidad}',
			`zv_fechadenacimiento` = '{$zv_fechadenacimiento}',
			`zv_sexo` = '{$zv_sexo}',
			`zv_telefono` = '{$zv_telefono}',
			`zv_avatar` = '{$zv_avatar}',
			`zv_aodeafiliacion` = '{$zv_aodeafiliacion}',
			`zv_direccincomplemento` = '{$zv_direccincomplemento}',
			`zv_profesion` = '{$zv_profesion}',
			`zv_tipomembresia` = '{$zv_tipomembresia}',
			`zv_estadocivil` = '{$zv_estadocivil}',
			`zv_emailproblematico` = '{$zv_emailproblematico}',
			`zv_fechaactualizaciondatos` = '{$zv_fechaactualizaciondatos}',
			`zv_fechadebaja` = '{$zv_fechadebaja}',
			`zv_direccion` = '{$zv_direccion}',
			`zv_direccionnumero` = '{$zv_direccionnumero}',
			`zv_direccionbarrio` = '{$zv_direccionbarrio}',
			`zv_direccionciudad` = '{$zv_direccionciudad}',
			`zv_direccionprovincia` = '{$zv_direccionprovincia}',
			`zv_direccioncodigopostal` = '{$zv_direccioncodigopostal}',
			`zv_ddi` = '{$zv_ddi}',
			`zv_fax` = '{$zv_fax}',
			`zv_emaillaboral` = '{$zv_emaillaboral}',
			`zv_direccionlaboralnumero` = '{$zv_direccionlaboralnumero}',
			`zv_institucion` = '{$zv_institucion}',
			`zv_direccionlaboral` = '{$zv_direccionlaboral}',
			`zv_direccionlaboralcomplemento` = '{$zv_direccionlaboralcomplemento}',
			`zv_direcionlaboralbarrio` = '{$zv_direcionlaboralbarrio}',
			`zv_direccionlaboralciudad` = '{$zv_direccionlaboralciudad}',
			`zv_direccionlaboralprovincia` = '{$zv_direccionlaboralprovincia}',
			`zv_direcionlaboralpais` = '{$zv_direcionlaboralpais}',
			`zv_direccionlaboralcodigopostal` = '{$zv_direccionlaboralcodigopostal}',
			`zv_ddilaboral` = '{$zv_ddilaboral}',
			`zv_telefonolaboral` = '{$zv_telefonolaboral}',
			`zv_faxlaboral` = '{$zv_faxlaboral}',
			`zv_titulogrado` = '{$zv_titulogrado}',
			`zv_titulopostgrado` = '{$zv_titulopostgrado}',
			`zv_titulonoaplicado` = '{$zv_titulonoaplicado}',
			`zv_areainteres` = '{$zv_areainteres}',
			`zv_instnet` = '{$zv_instnet}',
			`zv_direccioninstnet` = '{$zv_direccioninstnet}',
			`zv_direccionparticularnet` = '{$zv_direccionparticularnet}',
			`zv_emailpersonalnet` = '{$zv_emailpersonalnet}',
			`zv_emaillaboralnet` = '{$zv_emaillaboralnet}',
			`zv_areasujest` = '{$zv_areasujest}',
			`zv_areanoaplicado` = '{$zv_areanoaplicado}',
			`zv_perteneceasociacion` = '{$zv_perteneceasociacion}',
			`zv_asocnacionalpoblacion` = '{$zv_asocnacionalpoblacion}',
			`zv_perfilacadmico` = '{$zv_perfilacadmico}',
			`zv_autorizodivulgarmisdatosporalap` = '{$zv_autorizodivulgarmisdatosporalap}',
			`zv_socio` = '{$zv_socio}',
			`zv_otras` = '{$zv_otras}',
			`zv_fechaaprobacion` = '{$zv_fechaaprobacion}'
		WHERE `id` = '{$zv_idusers}';
		";

            $db =& JFactory::getDbo();
            if ($db->setQuery($sql)) {
                $db->execute();
                $mensagem = 'Dados actualizados con éxito!';
                $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&layout=editar&Itemid=61', $mensagem, 'Message');
                //$this->setRedirect('index.php?option=com_content&view=article&id=216&Itemid=231', $mensagem, 'Message');
            } else {
                $mensagem = '¡Lo siento! hubo un error de realizar su inscripción. Busque un administrador.';
                $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&layout=editar&Itemid=61', $mensagem, 'Error');
            }
        } else {
            $mensagem = '¡Lo siento! hubo un error de realizar su inscripción. Busque un administrador.';
            $this->setRedirect('index.php?option=com_zvcadastramento&view=zvcadastro&layout=editar&Itemid=61', $mensagem, 'Error');
        }
    }




    public function enviaEmailNovoCadastro($nome, $usuario){


        $corpo = '<html>

                    <p>Olá Administrador, '  . $nome . ' se cadastrou no Portal Alapop, por favor analise o cadastro!</p>

                    <p>Usuário: ' . $usuario . '</p>

                    <p></p>

                    </html>';


        $to = 'alap.secretaria@alapop.org';


        $subject = 'ALAP - Novo usuário cadastrado';

        $message = $corpo;

        $headers = 'MIME-Version: 1.0' . "\r\n";

        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'Bcc: josevicente.sistemas@gmail.com' . "\r\n";

        $headers .= 'From: alap.webmaster@alapop.org' . "\r\n" .

            'X-Mailer: PHP/' . phpversion();


        mail($to, $subject, $message, $headers);

    }// fim do método //




}// fim da classe //