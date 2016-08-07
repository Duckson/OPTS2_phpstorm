$(document).ready(function () {
    var $divs = $('.div-radio'),
        $submit = $('#submit');
    $submit.prop('disabled', true);
    $divs.children('input').prop('checked', false);
    $divs.click(function () {
        var $this = $(this);
        $divs.css('background-color', 'inherit');
        $this.children('input').prop('checked', true);
        $this.css('background-color', '#79EF78');
        $submit.prop('disabled', false);
    });
});

