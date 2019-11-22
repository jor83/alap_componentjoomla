/**
 * Created by josevicente on 13/10/2015.
 */


$(document).ready(function(){
   //alert("Estamos desarrollando !");
   $('.data').mask('99/99/9999');
   //$('#name').mask('(99)9999-9999');
});



jQuery(document).ready(function(){
    /*
    jQuery('#zv_fechadenacimiento').datepicker({
        dateFormat: "dd/mm/yy",
        dayNames: ["Domingo", "Segunda", "Ter�a", "Quarta", "Quinta", "Sexta", "S�bado"],
        dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        monthNames: ["Janeiro", "Fevereiro", "Mar�o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
    });
    */
});



$(document).ready(function(){
    $('.data').blur(function () {
        data = $('.data').val();
        data = data.split("/");
        dia = data[0];
        mes = data[1];
        ano = data[2];

        Atual = new Date();
        ano = parseInt(ano);
        anoAtual = Atual.getFullYear();
        anoAtual = parseInt(anoAtual);
        mesAtual = Atual.getMonth();
        mesAtual = parseInt(mesAtual + 1);
        diaAtual = Atual.getDate();
        diaAtual = parseInt(diaAtual);

        validado = true;

        if ((dia > 31) || (dia <= 0)) {
            alert("Data Incorreta! Dia Inv�lido!");
            $('.data').focus();
            validado = false;
        }

        if ((dia > 29) && (mes == 2)) {
            alert("Data Incorreta! Dia Inv�lido!");
            $('.data').focus();
            validado = false;
        }

        if (mes > 12) {
            alert("Data Incorreta! M�s Inv�lido!");
            $('.data').focus();
            validado = false;
        }

        if ((ano >= anoAtual)) {
            if ((mes >= mesAtual)) {
                if ((dia > diaAtual)) {
                    alert("Data Incorreta!");
                    $('.data').focus();
                    validado = false;
                }
            } else if ((ano > anoAtual)) {
                alert("Data Incorreta!");
                $('.data').focus();
                validado = false;
            }
        }

        if ((ano < 1900) || (isNaN(ano))) {
            alert("Data Incorreta! Ano Inv�lido!");
            $('.data').focus();
            validado = false;
        }

        if ((data.length < 3)) {
            alert("Data muito curta!");
            $('.data').focus();
            validado = false;
        }

        if (validado) {
            validado = true;
        }

        return validado;
    });
});


