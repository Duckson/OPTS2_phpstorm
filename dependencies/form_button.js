$(document).ready(function () {
    var $divs = $('.div-radio'),
        $submit = $('#submit');
    $submit.prop('disabled', true);
    $divs.children('input').prop('checked', false);
    $divs.click(function () {
        var $this = $(this);
        if ($this.children('input').prop('type') == 'radio') {
            $divs.css('background-color', 'inherit');
            $this.children('input').prop('checked', true);
            $this.css('background-color', '#79EF78');
            $submit.prop('disabled', false);
        } else if($this.children('input').prop('type') == 'checkbox') {
            if (!($this.children('input').prop('checked'))) {
                $this.children('input').prop('checked', true);
                $this.css('background-color', '#79EF78');
                $submit.prop('disabled', false);
            } else {
                $this.children('input').prop('checked', false);
                $this.css('background-color', 'inherit');
                if ($(".div-radio input:checkbox:checked").length = 0) {
                    $submit.prop('disabled', true);
                }
            }
        }
    });
});

