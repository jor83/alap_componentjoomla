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
?>
<div>
    <p class="title" style="text-align: center;">Búsqueda de Socios de ALAP</p>
    <em>
        <p class="txt_contenido">Para encontrar las informaciones de contacto de socios de ALAP, llene uno o más campos abajo con palavras claves y presione el botón 'Buscar' en el final de la página. La divulgación de las informaciones es hecha con la autorización del socio. Si no esta autorizada, el resultado de la búsqueda aparece como 'no autorizada'. Si eres socio y desea autorizar la divulgación de sus datos, ingrese al sitio con su clave de acceso y entre en 'Actualización de Datos'.</p>
        <p class="txt_contenido"><strong>El uso de esta información esta limitada a referencias y a propósitos profesionales relacionados con la afiliación a ALAP. Su uso comercial esta terminantemente prohibido.</strong></p>
    </em>
</div>


            <form action="<?php echo JRoute::_('index.php?option=com_zvbuscasocio&view=buscas');?>" method="POST" name="ZvbuscaSocio">
                <table class="buscar_usuarios">
                    <tbody><tr>
                        <td width="50%">Nombre:</td>
                        <td><input class="campo_buscar-buscar" type="text" id="txtNombre" name="txtNombre" value=""></td>
                    </tr>
                    <tr>
                        <td>Apellido:</td>
                        <td><input class="campo_buscar-buscar" type="text" id="txtApellido" name="txtApellido" value=""></td>
                    </tr>
                    <tr>
                        <td>Ciudad:</td>
                        <td><input class="campo_buscar-buscar" type="text" id="txtCiudad" name="txtCiudad" value=""></td>
                    </tr>
                    <tr>
                        <td>Provincia/Estado:</td>
                        <td><input class="campo_buscar-buscar" type="text" id="txtProvincia" name="txtProvincia" value=""></td>
                    </tr>
                    <tr>
                        <td>Pais:</td>
                        <td><input class="campo_buscar-buscar" type="text" id="txtPais" name="txtPais" value=""><br /></td>
                    </tr>

                    
                    <tr colspan="2">
                        <br />
                        <td><u><strong>Areas de interés:</strong></u></td>
                    </tr>
                    <tr><td>Distribución Espacial</td><td>
                            <input class="check_buscar" type="checkbox" value="Distribución Espacial" name="cid[]" id="Distribución Espacial">
                        </td></tr><tr><td>Migración Internacional</td><td>
                            <input class="check_buscar" type="checkbox" value="Migración Internacional" name="cid[]" id="Migración Internacional">
                        </td></tr><tr><td>Migración Interna</td><td>
                            <input class="check_buscar" type="checkbox" value="Migración Interna" name="cid[]" id="Migración Interna">
                        </td></tr><tr><td>Movilidad de la Población</td><td>
                            <input class="check_buscar" type="checkbox" value="Movilidad de la Población" name="cid[]" id="Movilidad de la Población">
                        </td></tr><tr><td>Población y Vivienda</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Vivienda" name="cid[]" id="Población y Vivienda">
                        </td></tr><tr><td>Población y Familia</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Familia" name="cid[]" id="Población y Familia">
                        </td></tr><tr><td>Población y Pobreza</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Pobreza" name="cid[]" id="Población y Pobreza">
                        </td></tr><tr><td>Población y Salud</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Salud" name="cid[]" id="Población y Salud">
                        </td></tr><tr><td>Población y Medio Ambiente</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Medio Ambiente" name="cid[]" id="Población y Medio Ambiente">
                        </td></tr><tr><td>Población y Mercado de Trabajo</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Mercado de Trabajo" name="cid[]" id="Población y Mercado de Trabajo">
                        </td></tr><tr><td>Envejecimiento de la Población</td><td>
                            <input class="check_buscar" type="checkbox" value="Envejecimiento de la Población" name="cid[]" id="Envejecimiento de la Población">
                        </td></tr><tr><td>Proyecciones de Población</td><td>
                            <input class="check_buscar" type="checkbox" value="Proyecciones de Población" name="cid[]" id="Proyecciones de Población">
                        </td></tr><tr><td>Producción de Datos Demográficos</td><td>
                            <input class="check_buscar" type="checkbox" value="Producción de Datos Demográficos" name="cid[]" id="Producción de Datos Demográficos">
                        </td></tr><tr><td>Métodos y Técnicas para el Estudio de la Población</td><td>
                            <input class="check_buscar" type="checkbox" value="Métodos y Técnicas para el Estudio de la Población" name="cid[]" id="Métodos y Técnicas para el Estudio de la Población">
                        </td></tr><tr><td>Fecundidad y Salud Reproductiva</td><td>
                            <input class="check_buscar" type="checkbox" value="Fecundidad y Salud Reproductiva" name="cid[]" id="Fecundidad y Salud Reproductiva">
                        </td></tr><tr><td>Capacitación de Recursos Humanos en Población</td><td>
                            <input class="check_buscar" type="checkbox" value="Capacitación de Recursos Humanos en Población" name="cid[]" id="Capacitación de Recursos Humanos en Población">
                        </td></tr><tr><td>Población y Derechos Humanos</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Derechos Humanos" name="cid[]" id="Población y Derechos Humanos">
                        </td></tr><tr><td>Políticas de Población</td><td>
                            <input class="check_buscar" type="checkbox" value="Políticas de Población" name="cid[]" id="Políticas de Población">
                        </td></tr><tr><td>Población y Desarrollo</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Desarrollo" name="cid[]" id="Población y Desarrollo">
                        </td></tr><tr><td>Estudios Urbanos</td><td>
                            <input class="check_buscar" type="checkbox" value="Estudios Urbanos" name="cid[]" id="Estudios Urbanos">
                        </td></tr><tr><td>Población y Genero</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Genero" name="cid[]" id="Población y Genero">
                        </td></tr><tr><td>Población y Pueblos Originarios</td><td>
                            <input class="check_buscar" type="checkbox" value="Población y Pueblos Originarios" name="cid[]" id="Población y Pueblos Originarios">
                        </td></tr><tr><td>Población Afrodescendiente</td><td>
                            <input class="check_buscar" type="checkbox" value="Población Afrodescendiente" name="cid[]" id="Población Afrodescendiente">
                        </td></tr><tr><td>Demografía Histórica</td><td>
                            <input class="check_buscar" type="checkbox" value="Demografía Histórica" name="cid[]" id="Demografía Histórica">
                        </td></tr><tr><td>Vulnerabilidad Sociodemográfica</td><td>
                            <input class="check_buscar" type="checkbox" value="Vulnerabilidad Sociodemográfica" name="cid[]" id="Vulnerabilidad Sociodemográfica">
                        </td></tr><tr><td>Otra</td><td>
                            <input class="check_buscar" type="checkbox" value="Otra" name="cid[]" id="Otra">
                        </td></tr>
                    <tr>
                        <td align="center" colspan="2">
                            
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" colspan="2">
                            <p><input class="b_buscar-buscar" type="submit" name="task_button" id="button" value="Buscar">
                            <input class="b_buscar-buscar" type="button" name="clear" id="clear" value="Borrar" onclick="javascript:limpiarForm();">
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!--
                <input type="hidden" name="view" value="userresult">
                <input type="hidden" name="areaInteres" value="128">
                <input type="hidden" name="idarticulo" value="296">
                <input type="hidden" name="option" value="com_searchusers">
                <input type="hidden" name="idIntro" value="266">
                <input type="hidden" name="Itemid" value="62">
                -->

        </td>
    </tr>
    </tbody>
</form>
