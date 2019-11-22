$(document).ready(function(){
    $("#name").click(function(){
        alert(0);
    });
});

    //jQuery('#zv_fechadenacimiento').mask('(99)9999-9999');
    //jQuery('#telefone_celular').mask('(99)9999-9999');
    //jQuery('#cpf').mask('99999999999');
    //jQuery('#zv_fechadenacimiento').mask('99/99/9999');





/*
jQuery('.data').datepicker({
    dateFormat: "dd/mm/yy",
    dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
    dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
});

jQuery('.data').blur(function () {
    alert(0);
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
        alert("Data Incorreta! Dia Inválido!");
        $('.data').focus();
        validado = false;
    }

    if ((dia > 29) && (mes == 2)) {
        alert("Data Incorreta! Dia Inválido!");
        $('.data').focus();
        validado = false;
    }

    if (mes > 12) {
        alert("Data Incorreta! Mês Inválido!");
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
        alert("Data Incorreta! Ano Inválido!");
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
    */