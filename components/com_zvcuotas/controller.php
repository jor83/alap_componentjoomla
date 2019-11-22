<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zvcuotas
 * @author     José Vicente Martins <josevicente.sistemas@gmail.com>
 * @copyright  Copyright (C) 2015. Todos os direitos reservados.
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Class ZvcuotasController
 *
 * @since  1.6
 */
class ZvcuotasController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/zvcuotas.php';

		$view = JFactory::getApplication()->input->getCmd('view', 'cuotasusuarios');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}



	public function redirecionaIpagare(){


		$frame =& JFactory::getApplication();
		//echo '<iframe name="ifrm" id="ifrm" src="index.php?option=com_zvcuotas&view=cuotasusuarios&layout=ipagare" style="width: 600px; height: 500px"></iframe>';
		//echo include('./components/com_zvcuotas/assets/include/ipagare.php');
		$view = new JViewLegacy();
		//echo include('index.php?option=com_zvcuotas&view=cuotasusuarios&layout=ipagare');
		echo '<iframe name="ifrm" id="ifrm" src="components/com_zvcuotas/assets/include/ipagare.php" frameborder="0" marginheight="0" marginwidth="0" style="width:100%; height: 500px;"border: 0px !important;" border="0">';
		//echo include('components/com_zvcuotas/assets/include/ipagare.php');
		echo '</iframe>';
		$frame->close();


	}// fim do metodo //


    public function validaTokenPayPal(){

        //$url = 'https://api-3t.sandbox.paypal.com/nvp/?'; // TESTE //
        $url = 'https://api-3t.paypal.com/nvp/?'; // PRODUCAO //
        //
        $USER = "alap.finanzas_api1.alapop.org";
        $PWD = "RWELYGKXDN87UZDF";
        $SIGNATURE = "AFcWxV21C7fd0v3bYYYRCpSSRl31AGSmG6R9bUa-Iyickbgk.cUNcz3F";
        //
        $METHOD = 'SetExpressCheckout';
        $VERSION = '114.0';
        $RETURNURL = 'http://www.alapop.org/alap/index.php?option=com_zvcuotas&view=msg';
        $CANCELURL = 'http://www.alapop.org/alap/index.php?option=com_zvcuotas&view=cuotasusuarios&layout=cancelamentopaypal&Itemid=763';
        $PAYMENTREQUEST_0_PAYMENTACTION = "SALE";
        $PAYMENTREQUEST_0_CURRENCYCODE = 'BRL';
        //$PAYMENTREQUEST_1_CURRENCYCODE = '';
        $PAYMENTREQUEST_0_AMT = JFactory::getApplication()->input->getString('PAYMENTREQUEST_0_AMT');// valor total da transação //
        $PAYMENTREQUEST_0_INVNUM =  JFactory::getApplication()->input->getString('PAYMENTREQUEST_1_INVNUM');//ID CUOTA PAGA //
        $PAYMENTREQUEST_0_ITEAMT = JFactory::getApplication()->input->getString('PAYMENTREQUEST_1_ITEAMT');
        $L_PAYMENTREQUEST_0_NAME0 = JFactory::getApplication()->input->getString('L_PAYMENTREQUEST_1_NAME0');
        $L_PAYMENTREQUEST_0_AMT0 = JFactory::getApplication()->input->getString('L_PAYMENTREQUEST_1_AMT0');
        $L_PAYMENTREQUEST_0_QTY0 ="1";
        $L_PAYMENTREQUEST_0_ITEMAMT = JFactory::getApplication()->input->getString('L_PAYMENTREQUEST_1_ITEMAMT');
        $BUTTONSOURCE = '';//"ALAP";
        $SUBJECT ="alap.finanzas@alapop.org";
        $LOCALECODE='pt_BR';
        //

        $url = $url.'USER='.$USER.'&PWD='.$PWD.'&SIGNATURE='.$SIGNATURE.'&METHOD='.$METHOD.'&VERSION='.$VERSION.'&RETURNURL='.$RETURNURL.'&CANCELURL='.$CANCELURL.'&PAYMENTREQUEST_0_PAYMENTACTION='.$PAYMENTREQUEST_0_PAYMENTACTION.'&PAYMENTREQUEST_0_AMT='.
            $PAYMENTREQUEST_0_AMT.'&PAYMENTREQUEST_0_CURRENCYCODE='.$PAYMENTREQUEST_0_CURRENCYCODE.'&PAYMENTREQUEST_0_INVNUM='.$PAYMENTREQUEST_0_INVNUM.'&PAYMENTREQUEST_0_ITEAMT='.$PAYMENTREQUEST_0_ITEAMT.'&L_PAYMENTREQUEST_0_NAME0='.$L_PAYMENTREQUEST_0_NAME0.'&L_PAYMENTREQUEST_0_AMT0='.
            $L_PAYMENTREQUEST_0_AMT0.'&L_PAYMENTREQUEST_0_QTY0='.$L_PAYMENTREQUEST_0_QTY0.'&L_PAYMENTREQUEST_0_ITEMAMT='.$L_PAYMENTREQUEST_0_ITEMAMT.'&BUTTONSOURCE='.$BUTTONSOURCE.'&SUBJECT='.$SUBJECT.'&PAYMENTREQUEST_0_CURRENCYCODE=BRL';
        //

        $session = JFactory::getSession();
        $session->set('valor_total',$PAYMENTREQUEST_0_AMT);
        $session->set('id_cuota',$PAYMENTREQUEST_0_INVNUM);
        $session->set('descricao',$L_PAYMENTREQUEST_0_NAME0);
        //

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        //curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp/'); // TESTE //
        curl_setopt($curl, CURLOPT_URL, 'https://api-3t.paypal.com/nvp/'); // PRODUCAO //
        curl_setopt($curl, CURLOPT_POSTFIELDS,'USER='.$USER.'&PWD='.$PWD.'&SIGNATURE='.$SIGNATURE.'&METHOD='.$METHOD.'&VERSION='.$VERSION.'&RETURNURL='.$RETURNURL.'&CANCELURL='.$CANCELURL.'&PAYMENTREQUEST_0_PAYMENTACTION='.$PAYMENTREQUEST_0_PAYMENTACTION.'&PAYMENTREQUEST_0_AMT='.
            $PAYMENTREQUEST_0_AMT.'&PAYMENTREQUEST_0_CURRENCYCODE='.$PAYMENTREQUEST_0_CURRENCYCODE.'&PAYMENTREQUEST_0_INVNUM='.$PAYMENTREQUEST_0_INVNUM.'&PAYMENTREQUEST_0_ITEAMT='.$PAYMENTREQUEST_0_ITEAMT.'&L_PAYMENTREQUEST_0_NAME0='.$L_PAYMENTREQUEST_0_NAME0.'&L_PAYMENTREQUEST_0_AMT0='.
            $L_PAYMENTREQUEST_0_AMT0.'&L_PAYMENTREQUEST_0_QTY0='.$L_PAYMENTREQUEST_0_QTY0.'&L_PAYMENTREQUEST_0_ITEMAMT='.$L_PAYMENTREQUEST_0_ITEMAMT.'&BUTTONSOURCE='.$BUTTONSOURCE.'&SUBJECT='.$SUBJECT.'&PAYMENTREQUEST_0_CURRENCYCODE=BRL');

        $response =  curl_exec($curl);
        curl_close($curl);
        //
        $nvp = array();

        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $nvp[$name] = urldecode($matches['value'][$offset]);
            }
        }

        if (isset($nvp['ACK']) && $nvp['ACK'] == 'Success') {
            $query = array(
                'cmd'    => '_express-checkout',
                'token'  => $nvp['TOKEN']
            );
            //echo $redirectURL = sprintf('https://www.sandbox.paypal.com/cgi-bin/webscr?%s', http_build_query($query)); // TESTE //
            echo $redirectURL = sprintf('https://www.paypal.com/cgi-bin/webscr?%s', http_build_query($query)); // PRODUCAO //
            header('Location: ' . $redirectURL);
        } else {
            echo 'Opz, alguma coisa deu errada.';
            //Verifique os logs de erro para depuração.
        }


        exit;
        //$frame->close();

    }// fim do metodo //




    public function newValidaTokenPayPal(){

        //credenciais da API para produção
        $user = 'usuario';
        $pswd = 'senha';
        $signature = 'assinatura';

        //URL da PayPal para redirecionamento, não deve ser modificada
        $paypalURL = 'https://www.paypal.com/cgi-bin/webscr';

        //

        //Campos da requisição da operação SetExpressCheckout, como ilustrado acima.
        $requestNvp = array(
            'USER' => $user,
            'PWD' => $pswd,
            'SIGNATURE' => $signature,

            'VERSION' => '108.0',
            'METHOD'=> 'SetExpressCheckout',

            'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
            'PAYMENTREQUEST_0_AMT' => '22.00',
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'BRL',
            'PAYMENTREQUEST_0_ITEMAMT' => '22.00',
            'PAYMENTREQUEST_0_INVNUM' => '1234',

            'L_PAYMENTREQUEST_0_NAME0' => 'Item A',
            'L_PAYMENTREQUEST_0_DESC0' => 'Produto A – 110V',
            'L_PAYMENTREQUEST_0_AMT0' => '11.00',
            'L_PAYMENTREQUEST_0_QTY0' => '1',
            'L_PAYMENTREQUEST_0_ITEMAMT' => '11.00',
            'L_PAYMENTREQUEST_0_NAME1' => 'Item B',
            'L_PAYMENTREQUEST_0_DESC1' => 'Produto B – 220V',
            'L_PAYMENTREQUEST_0_AMT1' => '11.00',
            'L_PAYMENTREQUEST_0_QTY1' => '1',

            'RETURNURL' => 'http://PayPalPartner.com.br/VendeFrete?return=1',
            'CANCELURL' => 'http://PayPalPartner.com.br/CancelaFrete',
            'BUTTONSOURCE' => 'BR_EC_EMPRESA'
        );

//Envia a requisição e obtém a resposta da PayPal
        $responseNvp = sendNvpRequest($requestNvp, $sandbox);

//Se a operação tiver sido bem sucedida, redirecionamos o cliente para o
//ambiente de pagamento.
        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success') {
            $query = array(
                'cmd'    => '_express-checkout',
                'token'  => $responseNvp['TOKEN']
            );

            $redirectURL = sprintf('%s?%s', $paypalURL, http_build_query($query));

            header('Location: ' . $redirectURL);
        } else {
            //Opz, alguma coisa deu errada.
            //Verifique os logs de erro para depuração.
        }

    }// fim do metdodo //



    public function getDetalhesPayPal(){

        $token = JFactory::getApplication()->input->getString('token');


        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_URL, 'https://api-3t.paypal.com/nvp');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'USER' => 'alap.finanzas_api1.alapop.org',
            'PWD' => 'RWELYGKXDN87UZDF',
            'SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AGSmG6R9bUa-Iyickbgk.cUNcz3F',

            'METHOD' => 'GetExpressCheckoutDetails',
            'VERSION' => '114.0',

            'TOKEN' => $token
        )));

        $response =    curl_exec($curl);

        curl_close($curl);

        $nvp = array();

        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $nvp[$name] = urldecode($matches['value'][$offset]);
            }
        }

        echo '<pre>';
        print_r($nvp);
        echo '</pre>';
        exit;

    }// fim do metodo //


}// fim da classe //
