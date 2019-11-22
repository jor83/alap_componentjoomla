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

//JHtml::_('behavior.keepalive');
//JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.framework');
//JHtml::_('behavior.formvalidation');
//JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_zvcadastramento', JPATH_SITE);
$doc = JFactory::getDocument();
//$doc->addScript(JUri::base() . '/components/com_zvcadastramento/assets/js/form.js');
//$doc->addstylesheet('components/com_zvcadastramento/assets/css/bootstrap.css');
?>
<h1>Registro</h1>

<div>
A continuación se presenta un formulario que debe ser completado para afiliarse a la Asociación Latinoamericana de Población- ALAP. Su objetivo es recopilar información detallada de aquellas personas que desean afiliarse a ALAP. Tome nota de que no todos los campos a llenar son obligatorios (solo aquellos marcados con un símbolo de color naranjo). Sin embargo, toda la información entregada es de gran utilidad para que el Consejo de Dirección pueda evaluar su solicitud de afiliación a la asociación.
</div>

<p style="color:#5A0101;"><strong>Sección I:</strong></p> 

<p style="color:#5A0101;"><strong>Datos personales</strong></p> 

<p style="color:#5A0101;"><strong>Nota: si se trata de una afiliación institucional, por favor lea las instrucciones bajo el símbolo "Explicación":</p></strong>

<form id="form-zvusuarios" action="<?php echo JRoute::_('index.php?option=com_zvcadastramento&task=zvusuarios.save'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="name">Nombre:</label>
    <div class="controls">
      <input id="name" name="name" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="zv_apellidopadre">Apelido:</label>
    <div class="controls">
      <input id="zv_apellidopadre" name="zv_apellidopadre" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="email">E-mail</label>
    <div class="controls">
      <input id="email" name="email" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="username">Nombre de USuario:</label>
    <div class="controls">
      <input id="username" name="username" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Password input-->
  <div class="control-group">
    <label class="control-label" for="password">Contraseña:</label>
    <div class="controls">
      <input id="password" name="password" type="password" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Password input-->
  <div class="control-group">
    <label class="control-label" for="password__verify">Verificar Contraseña:</label>
    <div class="controls">
      <input id="password__verify" name="password__verify" type="password" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="zv_direccionpais">País:</label>
    <div class="controls">
      <input id="zv_direccionpais" name="zv_direccionpais" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Select Basic -->
  <div class="control-group">
    <label class="control-label" for="zv_tipodeafiliacin">Tipo de afiliación:</label>
    <div class="controls">
      <select id="zv_tipodeafiliacin" name="zv_tipodeafiliacin" class="input-medium">
        <option>Individual</option>
        <option>Institucional</option>
      </select>
    </div>
  </div>

  <!-- Select Basic -->
  <div class="control-group">
    <label class="control-label" for="zv_tipodocumento">Tipo de documento:</label>
    <div class="controls">
      <select id="zv_tipodocumento" name="zv_tipodocumento" class="input-medium">
        <option>CPF</option>
        <option>Passaporte</option>
        <option>DNI</option>
        <option>Otro</option>
      </select>
    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="zv_documento">N. Documento</label>
    <div class="controls">
      <input id="zv_documento" name="zv_documento" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="zv_nacionalidad">Nacionalidad:</label>
    <div class="controls">
      <input id="zv_nacionalidad" name="zv_nacionalidad" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="zv_fechadenacimiento">Fecha de nacimiento:</label>
    <div class="controls">
      <input id="zv_fechadenacimiento" name="zv_fechadenacimiento" type="text" placeholder="" class="input-large">

    </div>
  </div>

  <!-- Multiple Radios -->
  <div class="control-group">
    <label class="control-label" for="zv_sexo">Sexo</label>
    <div class="controls">
      <label class="radio" for="zv_sexo-0">
        <input type="radio" name="zv_sexo" id="zv_sexo-0" value="Femenino" checked="checked">
        Femenino
      </label>
      <label class="radio" for="zv_sexo-1">
        <input type="radio" name="zv_sexo" id="zv_sexo-1" value="Masculino">
        Masculino
      </label>
    </div>
  </div>

  <!-- Text input-->
  <div class="control-group">
    <label class="control-label" for="zv_telefono">Teléfono de contacto:</label>
    <div class="controls">
      <input id="zv_telefono" name="zv_telefono" type="text" placeholder="" class="input-large" required="">

    </div>
  </div>
<p style="color:#5A0101;"><strong>Datos postales (en los que desea usted recibir correspondencia de ALAP)
 Nota: si se trata de una institución, ingresar información de la persona responsable:</strong></p>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccion">Calle:</label>  
  <div class="col-md-6">
  <input id="zv_direccion" name="zv_direccion" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="vz_direccionnumero">Numero:</label>  
  <div class="col-md-6">
  <input id="vz_direccionnumero" name="vz_direccionnumero" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="vz_direccincomplemento">Complemento:</label>  
  <div class="col-md-6">
  <input id="vz_direccincomplemento" name="vz_direccincomplemento" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionbarrio">Barrio:</label>  
  <div class="col-md-6">
  <input id="zv_direccionbarrio" name="zv_direccionbarrio" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionciudad">Ciudad:</label>  
  <div class="col-md-6">
  <input id="zv_direccionciudad" name="zv_direccionciudad" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="cb_direccionprovincia">Provincia/Estado/Región/Dpto:  </label>  
  <div class="col-md-6">
  <input id="cb_direccionprovincia" name="cb_direccionprovincia" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccioncodigopostal">Código postal:</label>  
  <div class="col-md-6">
  <input id="zv_direccioncodigopostal" name="zv_direccioncodigopostal" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

	
<p></p>
<p style="color:#5A0101;"><strong>A continuación indique qué datos personales autoriza a divulgar:</strong></p>
<!-- Multiple Checkboxes -->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_autorizodivulgarmisdatosporalap[]">Autorizo divulgar mis datos por ALAP:</label>
<div class="col-md-4">
  <div class="checkbox">
    <label for="zv_autorizodivulgarmisdatosporalap[]-0">
      <input type="checkbox" name="zv_autorizodivulgarmisdatosporalap[]" id="zv_autorizodivulgarmisdatosporalap[]-0" value="Nombre">
      Nombre
    </label>
	</div>
  <div class="checkbox">
    <label for="zv_autorizodivulgarmisdatosporalap[]-1">
      <input type="checkbox" name="zv_autorizodivulgarmisdatosporalap[]" id="zv_autorizodivulgarmisdatosporalap[]-1" value="E-mail">
      E-mail
    </label>
	</div>
  <div class="checkbox">
    <label for="zv_autorizodivulgarmisdatosporalap[]-2">
      <input type="checkbox" name="zv_autorizodivulgarmisdatosporalap[]" id="zv_autorizodivulgarmisdatosporalap[]-2" value="Dirección de correspondencia">
      Dirección de correspondencia
    </label>
	</div>
  <div class="checkbox">
    <label for="zv_autorizodivulgarmisdatosporalap[]-3">
      <input type="checkbox" name="zv_autorizodivulgarmisdatosporalap[]" id="zv_autorizodivulgarmisdatosporalap[]-3" value="Teléfono de contacto">
      Teléfono de contacto
    </label>
	</div>
  <div class="checkbox">
    <label for="zv_autorizodivulgarmisdatosporalap[]-4">
      <input type="checkbox" name="zv_autorizodivulgarmisdatosporalap[]" id="zv_autorizodivulgarmisdatosporalap[]-4" value="Áreas de interés">
      Áreas de interés
    </label>
	</div>
  <div class="checkbox">
    <label for="zv_autorizodivulgarmisdatosporalap[]-5">
      <input type="checkbox" name="zv_autorizodivulgarmisdatosporalap[]" id="zv_autorizodivulgarmisdatosporalap[]-5" value="Perfil">
      Perfil
    </label>
	</div>
  <div class="checkbox">
    <label for="zv_autorizodivulgarmisdatosporalap[]-6">
      <input type="checkbox" name="zv_autorizodivulgarmisdatosporalap[]" id="zv_autorizodivulgarmisdatosporalap[]-6" value="Institución">
      Institución
    </label>
	</div>
  </div>
</div>


<p></p>
<p style="color:#5A0101;"><strong>Datos Laborales: <br>
 
Nota: si se trata de una afiliación institucional, ingresar información de la institución: 
</strong></p>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_institucion">Institución:</label>  
  <div class="col-md-6">
  <input id="zv_institucion" name="zv_institucion" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_telefonolaboral">Teléfono:</label>  
  <div class="col-md-6">
  <input id="zv_telefonolaboral" name="zv_telefonolaboral" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="cb_faxlaboral">Fax:</label>  
  <div class="col-md-6">
  <input id="cb_faxlaboral" name="cb_faxlaboral" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="cb_emaillaboral">Email laboral:</label>  
  <div class="col-md-6">
  <input id="cb_emaillaboral" name="cb_emaillaboral" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>


<p></p>
<p style="color:#5A0101;"><strong>Dirección:</strong></p>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionlaboral">Calle:</label>  
  <div class="col-md-6">
  <input id="zv_direccionlaboral" name="zv_direccionlaboral" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionlaboralnumero">Número:</label>  
  <div class="col-md-6">
  <input id="zv_direccionlaboralnumero" name="zv_direccionlaboralnumero" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionlaboralcomplemento">Complemento:</label>  
  <div class="col-md-6">
  <input id="zv_direccionlaboralcomplemento" name="zv_direccionlaboralcomplemento" type="text" placeholder="" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="cb_direcionlaboralbarrio">Barrio:</label>  
  <div class="col-md-6">
  <input id="cb_direcionlaboralbarrio" name="cb_direcionlaboralbarrio" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionlaboralciudad">Ciudad:</label>  
  <div class="col-md-6">
  <input id="zv_direccionlaboralciudad" name="zv_direccionlaboralciudad" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionlaboralprovincia">Provincia/Estado/Región/Dpto:</label>  
  <div class="col-md-6">
  <input id="zv_direccionlaboralprovincia" name="zv_direccionlaboralprovincia" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direcionlaboralpais">País del Trabajo:</label>  
  <div class="col-md-6">
  <input id="zv_direcionlaboralpais" name="zv_direcionlaboralpais" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_direccionlaboralcodigopostal">Código postal:</label>  
  <div class="col-md-6">
  <input id="zv_direccionlaboralcodigopostal" name="zv_direccionlaboralcodigopostal" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>


<p></p>
<p style="color:#5A0101;"><strong>Datos académicos <br>
 Nota: si se trata de una afiliación institucional, por favor lea las instrucciones bajo el símbolo "Explicación":
</strong></p>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="cb_titulopostgrado">Título postgrado completo:</label>  
  <div class="col-md-6">
  <input id="cb_titulopostgrado" name="cb_titulopostgrado" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_titulogrado">Título grado completo:</label>  
  <div class="col-md-6">
  <input id="zv_titulogrado" name="zv_titulogrado" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_profesion">Profesión/Estudio:</label>  
  <div class="col-md-6">
  <input id="zv_profesion" name="zv_profesion" type="text" placeholder="" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_perfilacadmico">Perfil (Ingrese su CV en 2000 caracteres):</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="zv_perfilacadmico" name="zv_perfilacadmico"></textarea>
  </div>
</div>

<p></p>
<p style="color:#5A0101;"><strong>Otros datos:</strong></p>

<!-- Multiple Checkboxes -->
<div class="form-group">
  <label class="col-md-4 control-label" for="checkboxes">Multiple Checkboxes</label>
  <div class="col-md-4">
  <div class="checkbox">
    <label for="checkboxes-0">
      <input type="checkbox" name="checkboxes" id="checkboxes-0" value="Distribución Espacial">
      Distribución Espacial
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-1">
      <input type="checkbox" name="checkboxes" id="checkboxes-1" value="Población y Salud">
      Población y Salud
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-2">
      <input type="checkbox" name="checkboxes" id="checkboxes-2" value="Fecundidad y Salud Reproductiva">
      Fecundidad y Salud Reproductiva
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-3">
      <input type="checkbox" name="checkboxes" id="checkboxes-3" value="Población y Pueblos Originarios">
      Población y Pueblos Originarios
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-4">
      <input type="checkbox" name="checkboxes" id="checkboxes-4" value="Migración Internacional">
      Migración Internacional
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-5">
      <input type="checkbox" name="checkboxes" id="checkboxes-5" value="Población y Medio Ambiente">
      Población y Medio Ambiente
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-6">
      <input type="checkbox" name="checkboxes" id="checkboxes-6" value="Capacitación de Recursos Humanos en Población">
      Capacitación de Recursos Humanos en Población
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-7">
      <input type="checkbox" name="checkboxes" id="checkboxes-7" value="Población Afrodescendiente">
      Población Afrodescendiente
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-8">
      <input type="checkbox" name="checkboxes" id="checkboxes-8" value="Migración Interna">
      Migración Interna
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-9">
      <input type="checkbox" name="checkboxes" id="checkboxes-9" value="Población y Mercado de Trabajo">
      Población y Mercado de Trabajo
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-10">
      <input type="checkbox" name="checkboxes" id="checkboxes-10" value="Población y Derechos Humanos">
      Población y Derechos Humanos
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-11">
      <input type="checkbox" name="checkboxes" id="checkboxes-11" value="Demografía Histórica">
      Demografía Histórica
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-12">
      <input type="checkbox" name="checkboxes" id="checkboxes-12" value="Movilidad de la Población">
      Movilidad de la Población
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-13">
      <input type="checkbox" name="checkboxes" id="checkboxes-13" value="Envejecimiento de la Población">
      Envejecimiento de la Población
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-14">
      <input type="checkbox" name="checkboxes" id="checkboxes-14" value="Políticas de Población">
      Políticas de Población
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-15">
      <input type="checkbox" name="checkboxes" id="checkboxes-15" value="Vulnerabilidad Sociodemográfica">
      Vulnerabilidad Sociodemográfica
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-16">
      <input type="checkbox" name="checkboxes" id="checkboxes-16" value="Población y Vivienda">
      Población y Vivienda
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-17">
      <input type="checkbox" name="checkboxes" id="checkboxes-17" value="Proyecciones de Población">
      Proyecciones de Población
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-18">
      <input type="checkbox" name="checkboxes" id="checkboxes-18" value="Población y Desarrollo">
      Población y Desarrollo
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-19">
      <input type="checkbox" name="checkboxes" id="checkboxes-19" value="Población y Familia">
      Población y Familia
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-20">
      <input type="checkbox" name="checkboxes" id="checkboxes-20" value="Producción de Datos Demográficos">
      Producción de Datos Demográficos
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-21">
      <input type="checkbox" name="checkboxes" id="checkboxes-21" value="Estudios Urbanos">
      Estudios Urbanos
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-22">
      <input type="checkbox" name="checkboxes" id="checkboxes-22" value="Población y Pobreza">
      Población y Pobreza
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-23">
      <input type="checkbox" name="checkboxes" id="checkboxes-23" value="Métodos y Técnicas para el Estudio de la Población">
      Métodos y Técnicas para el Estudio de la Población
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-24">
      <input type="checkbox" name="checkboxes" id="checkboxes-24" value="Población y Genero">
      Población y Genero
    </label>
	</div>
  <div class="checkbox">
    <label for="checkboxes-25">
      <input type="checkbox" name="checkboxes" id="checkboxes-25" value="Otra">
      Otra
    </label>
	</div>
  </div>
</div>

<p></p>
<!-- Multiple Radios -->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_perteneceasociacion">Pertenece a alguna asociación relacionada con temas de </label>
  <div class="col-md-4">
  <div class="radio">
    <label for="zv_perteneceasociacion-0">
      <input type="radio" name="zv_perteneceasociacion" id="zv_perteneceasociacion-0" value="Si" checked="checked">
      Si
    </label>
	</div>
  <div class="radio">
    <label for="zv_perteneceasociacion-1">
      <input type="radio" name="zv_perteneceasociacion" id="zv_perteneceasociacion-1" value="No">
      No
    </label>
	</div>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_asocnacionalpoblacion">Asociación nacional de población:</label>  
  <div class="col-md-6">
  <input id="zv_asocnacionalpoblacion" name="zv_asocnacionalpoblacion" type="text" placeholder="" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zv_otras">Otras:</label>  
  <div class="col-md-6">
  <input id="zv_otras" name="zv_otras" type="text" placeholder="" class="form-control input-md">
    
  </div>
</div>

<p>Gracias por enviar su solicitud de afiliación. Aguarde instrucciones en su dirrección de e-mail. Cualquier duda escriba a alap.secretaria@alapop.org</p>


        <div class="control-group">
            <div class="controls">
                <button type="submit" class="validate btn btn-primary"><?php echo JText::_('Registrar'); ?></button>
                <a class="btn" href="<?php echo JRoute::_('index.php?option=com_zvcadastramento&task=zvusuariosform.cancel'); ?>" title="<?php echo JText::_('Limpar'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
            </div>
        </div>
        
        <input type="hidden" name="option" value="com_zvcadastramento" />
        <input type="hidden" name="task" value="zvusuariosform.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
