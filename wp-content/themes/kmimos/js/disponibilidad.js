var fecha = new Date();
jQuery('#inicio').datepick(
    {
        dateFormat: 'dd/mm/yyyy',
        showTrigger: '#calImg',
        minDate: fecha,
        onSelect: seleccionar_checkin,
        yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
        firstDay: 1
    }
);

function seleccionar_checkin(date) {
    if( jQuery('#inicio').val() != '' ){
        var fecha = new Date();
        jQuery('#fin').attr('disabled', false);
        var ini = String( jQuery('#inicio').val() ).split('/');
        var fin = String( jQuery('#fin').val() ).split('/');
        var inicio = new Date( parseInt(ini[2]), parseInt(ini[1])-1, parseInt(ini[0]) );
        var fin = new Date( parseInt(fin[2]), parseInt(fin[1])-1, parseInt(fin[0]) );

        jQuery('#fin').removeClass('is-datepick');
        jQuery('#fin').datepick({
            dateFormat: 'dd/mm/yyyy',
            showTrigger: '#calImg',
            minDate: inicio,
            yearRange: fecha.getFullYear()+':'+(parseInt(fecha.getFullYear())+1),
            firstDay: 1
        });

        if( Math.abs(fin.getTime()) < Math.abs(inicio.getTime()) ){
            jQuery('#fin').datepick( 'setDate', jQuery('#inicio').val() );
        }
    }else{
        jQuery('#fin').val('');
        jQuery('#fin').attr('disabled', true);
    }
}