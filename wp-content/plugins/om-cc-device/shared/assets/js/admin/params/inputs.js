jQuery(function ($) {
    $(document).on('change', '[data-om-cc-inputs]', function () {
        var $row = $(this).parent().parent(),
            $target = $row.prev();

        $target.val($row.find('[data-om-cc-inputs]').map(function () { return this.value; }).toArray().join('|||'));
    });
});