/**
 * Created by josevicente on 31/01/16.
 */

jQuery(document).ready(function(){
   jQuery('#id_vicente').click(function(){

      var USER = '';
      var PWD = '159VM5R589DQQ8PCGWQS3T';
      var SIGNATURE = 'AFcW2584xV21C7fd0v3bYYYRCpSSRl31AhfWzRsKKc4rE73ci0mFmmmYqNyx';
      var METHOD = 'SetExpressCheckout';
      var VERSION = '114.0';
      var RETURNURL = "";
      var CANCELURL = "";

      var PAYMENTREQUEST_1_PAYMENTACTION = "SALE";
      var PAYMENTREQUEST_0_AMT = jQuery("[name=PAYMENTREQUEST_0_AMT]").val();
      var PAYMENTREQUEST_1_CURRENCYCODE = "BRL";
      var PAYMENTREQUEST_1_INVNUM =  jQuery("[name=PAYMENTREQUEST_1_INVNUM]").val();
      var PAYMENTREQUEST_1_ITEAMT ="120.00";
      var L_PAYMENTREQUEST_1_NAME0 = "Anio: 2017";
      var L_PAYMENTREQUEST_1_AMT0 = "120.00";
      var L_PAYMENTREQUEST_1_QTY0 ="1";
      var L_PAYMENTREQUEST_1_ITEMAMT ="120.00";
      var BUTTONSOURCE = "ALAP";
      var SUBJECT ="";




      return false;
   });
});



function zvPagoOnline(){
    jQuery(document).ready(function(){
        jQuery('#divPagoOnline').css('display','block');
        $.post( "./index.php?option=com_zvcuotas&task=redirecionaIpagare", function( res ) {
            $( "#divPagoOnline" ).html( res );
        });
    });
}// fim da função //






