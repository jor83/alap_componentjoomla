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

function sendNvpRequest(array $requestNvp, $sandbox = false)
{
    //Endpoint da API
    $apiEndpoint  = 'https://api-3t.' . ($sandbox? 'sandbox.': null);
    $apiEndpoint .= 'paypal.com/nvp';

    //Executando a operação
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp));

    $response = urldecode(curl_exec($curl));

    curl_close($curl);

    //Tratando a resposta
    $responseNvp = array();

    if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
        foreach ($matches['name'] as $offset => $name) {
            $responseNvp[$name] = $matches['value'][$offset];
        }
    }

    //Verificando se deu tudo certo e, caso algum erro tenha ocorrido,
    //gravamos um log para depuração.
    if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
        for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
            $message = sprintf("PayPal NVP %s[%d]: %s\n",
                $responseNvp['L_SEVERITYCODE' . $i],
                $responseNvp['L_ERRORCODE' . $i],
                $responseNvp['L_LONGMESSAGE' . $i]);

            error_log($message);
        }
    }

    return $responseNvp;
}


function atualizaPagamentoCuota($id_cuota, $descricao, $id_transacao){

    $desc= $descricao.'- ID PAYPAL: '.$id_transacao;

    $db = JFactory::getDbo();

    $sql = "UPDATE
            #__cuotas_pagos
            SET
            fecha_pago = NOW(),
            forma_pago = 'PAYPAL',
            isPagado = 1,
            concepto = '{$desc}'
            WHERE
            id_pago = $id_cuota
            ";

    $db->setQuery($sql);
    $db->execute();
    return true;

}//


$token = JFactory::getApplication()->input->getString('94a08da1fecbb6e8b46990538c7b50b2');
$PayerID = JFactory::getApplication()->input->getString('4b5546b16be0ea35d07fbe1f2342e527');
$USER = "alap.finanzas_api1.alapop.org";
$PWD = "RWELYGKXDN87UZDF";
$SIGNATURE = "AFcWxV21C7fd0v3bYYYRCpSSRl31AGSmG6R9bUa-Iyickbgk.cUNcz3F";
//
//Campos da requisição da operação DoExpressCheckoutPayment
$session = JFactory::getSession();
$valor_total = $session->get('valor_total');
$id_cuota = $session->get('id_cuota');
$descricao = $session->get('descricao');
//
$exp = explode(' ',$descricao);
$exp = explode('|',$exp[1]);
$ano_cuota = $exp[0];

$requestNvp = array(

    'USER' => $USER,
    'PWD' => $PWD,
    'SIGNATURE' => $SIGNATURE,
    'VERSION' => '114.0',
    'METHOD'=> 'DoExpressCheckoutPayment',
    'TOKEN' => $token,
    'PAYERID' => $PayerID,
    'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
    'PAYMENTREQUEST_0_AMT' => $valor_total, // valor total //
    'PAYMENTREQUEST_0_CURRENCYCODE' => 'BRL',
    'PAYMENTREQUEST_0_ITEMAMT' => $valor_total, // valor total //
    'PAYMENTREQUEST_0_INVNUM' => $id_cuota,// ID da CUOTA PAGA
    'L_PAYMENTREQUEST_0_NAME0' => 'CUOTA ALAP '.$ano_cuota, // CUOTA ALAP //
    'L_PAYMENTREQUEST_0_DESC0' => $descricao, // CUOTA ALAP //
    'L_PAYMENTREQUEST_0_AMT0' => $valor_total, // valor total //
    'L_PAYMENTREQUEST_0_QTY0' => '1'
    //'L_PAYMENTREQUEST_0_NAME1' => '',
    //'L_PAYMENTREQUEST_0_DESC1' => '',
    //'L_PAYMENTREQUEST_0_AMT1' => '',
    //'L_PAYMENTREQUEST_0_QTY1' => '',
);

//Envia a requisição e obtém a resposta da PayPal
$responseNvp = sendNvpRequest($requestNvp, null);

echo '<pre>';
var_dump($responseNvp);
echo '</pre>';
exit;

//echo 'O ID da transação é: ' . $responseNvp['PAYMENTINFO_0_TRANSACTIONID'];
if($responseNvp['PAYMENTINFO_0_TRANSACTIONID'] != null || !empty($responseNvp['PAYMENTINFO_0_TRANSACTIONID'])) {
    //
    $atualiza_tabela = atualizaPagamentoCuota($id_cuota, $descricao, $responseNvp['PAYMENTINFO_0_TRANSACTIONID']);
    ?>
    <h2>Recibimos su Pago. Gracias</h2>
    <p>El identificador de transacción es: <strong><?php echo $responseNvp['PAYMENTINFO_0_TRANSACTIONID']; ?></strong></p>
    <p>También recibirá un e-mail con detalles de su pago.</p>
    <?php
}else{
    ?>
    <h2>Pago no concluido</h2>
    <p>Lo sentimos, pero se ha producido algún problema con su pago. Lamentablemente no he recibido confirmación de su transacción, vuelva a intentarlo más tarde.</p>
    <p>También recibirá un e-mail con detalles de su pago.</p>
    <?php
}
$session->clear('valor_total');
$session->clear('id_cuota');
$session->clear('descricao');
?>
