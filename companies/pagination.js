$(document).ready(function () {
    PageChange(1)
});

function PageChange(page){
    var tr_count = 3,
        trs_array = [],
        trs;

    for(var i = 0; i < tr_count; i++){
        trs_array[i] = '#tr-' + ((page * tr_count) - i);
    }
    trs = trs_array.join(', ');
    $('.page').removeClass('active');
    $('.page-tr').hide();
    $('#page-' + page).addClass('active');
    $(trs).show()
}


