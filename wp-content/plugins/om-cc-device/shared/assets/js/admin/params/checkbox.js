jQuery(function ($) {
    $(document).on('change', '[data-om-cc-checkbox]', function () {
        var $this = $(this),
            $target = $($this.data('om-cc-checkbox'));

        $target.val($this.prop('checked'));
    });
});