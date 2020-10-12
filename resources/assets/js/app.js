"use strict";

(function ($) {
    // Date picker
    $('.datepicker').each(function () {
        var txtDate = $(this);

        // Date picker
        var datepickerOptions = {weekStart: 1};

        // Min date
        var attrMinDate = txtDate.data('min-date');

        if (attrMinDate) {
            datepickerOptions['startDate'] = attrMinDate;
        }

        // Max date
        var attrMaxDate = txtDate.data('max-date');

        if (attrMaxDate) {
            datepickerOptions['endDate'] = attrMaxDate;
        }

        // Only show the days
        var attrDaysOfWeekDisabled = txtDate.data('days-of-week-disabled');

        if (attrDaysOfWeekDisabled) {
            datepickerOptions['daysOfWeekDisabled'] = attrDaysOfWeekDisabled;
        }

        // Register date picker
        txtDate.datepicker(datepickerOptions);

        /**
         * @event onChangeInputDisplay
         * @param {jQuery} input
         */
        var onChangeInputDisplay = function (input) {
            var fieldInfo = input.data('info');
            var field = input;
            var container = input.parent();
            // var name = input.attr('name');
            // Split name to array to detect is display field or not
            // var arrName = name.split('_');
            // var isDisplayField = (arrName[arrName.length - 1] === 'display');
            var isDisplayField = typeof fieldInfo !== 'undefined' && typeof fieldInfo.name !== 'undefined';
            var suffix = '';

            if (isDisplayField) {
                field = container.find('[name="' + fieldInfo.name + '"]');
                suffix = '_display';
            }

            // var val = input.val();
            var date = input.datepicker('getDate');
            var strDate = (date) ? moment(date).format('YYYY-MM-DD') : '';

            if (isDisplayField && field.length > 0) {
                field.val(strDate);
            }

            // Set ranger for datepicker ranger
            if (typeof input.data('datepicker-range-min') !== 'undefined') {
                $('[name="' + input.data('datepicker-range-min') + suffix + '"]').datepicker("setStartDate", date);
            }

            if (typeof input.data('datepicker-range-max') !== 'undefined') {
                $('[name="' + input.data('datepicker-range-max') + suffix + '"]').datepicker("setEndDate", date);
            }

        };

        /**
         * When change date picker
         * Listen for the change even on the input
         * @link https://stackoverflow.com/a/22507814/10174865
         */
        txtDate.on('changeDate', function (e) {
            var input = $(e.currentTarget);
            // Hide picker
            // input.datepicker('hide');
            // Register on change
            onChangeInputDisplay(input);
        });

        /**
         * When hide date picker
         */
        txtDate.on('hide', function (e) {
            var input = $(e.currentTarget);
            // Register on change
            onChangeInputDisplay(input);
        });

        /**
         * When change date input
         */
        txtDate.on('change', function (e) {
            var input = $(e.currentTarget);
            // Register on change
            onChangeInputDisplay(input);
        });
    });

    // Select2
    $('.select2').select2();
    
    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();
})(jQuery);