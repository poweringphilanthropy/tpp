jQuery(function ($) {
    $('[data-om-cc-code]').each(function () {
        var $this = $(this),
            data = $this.data('om-cc-code');

        if (!data) {
            $this.data('om-cc-code', true);

            var editor = ace.edit($('<div/>').insertAfter($this).get(0));

            editor.setTheme("ace/theme/chrome");
            editor.resize();
            editor.setValue(($this.val() || '').replace(/\|\|\|/g, '\n'));
            editor.clearSelection();
            editor.getSession().on('change', function () {
                $this.val(editor.getValue().replace(/\n/g, '|||'));
            });
        }
    });
});