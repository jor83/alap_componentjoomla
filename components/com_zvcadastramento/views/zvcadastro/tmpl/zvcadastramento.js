/**
 * Created by josevicente on 13/10/2015.
 */
jQuery(document).ready(function(){
   alert(0);
});

jQuery('.data').datepicker({
    dateFormat: "dd/mm/yy",
    dayNames: ["Domingo", "Segunda", "Ter�a", "Quarta", "Quinta", "Sexta", "S�bado"],
    dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
    monthNames: ["Janeiro", "Fevereiro", "Mar�o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
});
