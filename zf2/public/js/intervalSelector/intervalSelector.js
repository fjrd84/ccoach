/*global $, jQuery, alert*/
var intervalSelector = {
    intervalType: '',
    intervalNum: '',
    addListeners: function () {
        'use strict';
        intervalSelector.removeListeners();
        $('.intervalTypeElement').click(function () {
            intervalSelector.selectIntervalType($(this));
        });
        $('.intervalNumElement').click(function () {
            intervalSelector.selectIntervalNum($(this));
        });
    },
    resetSelector: function () {
        'use strict';
        $('.intervalTypeElement').removeClass('selected');
        $('.intervalNumElement').removeClass('selected');
        this.intervalNum = '';
        this.intervalType = '';
        this.displayInterval();
    },
    removeListeners: function () {
        'use strict';
        $('.intervalTypeElement').off();
        $('.intervalNumElement').off();
    },
    selectIntervalType: function (intType) {
        'use strict';
        $('.intervalTypeElement').removeClass('selected');
        intType.addClass('selected');
        this.intervalType = intType.data('type');
        this.displayInterval();
    },
    selectIntervalNum: function (intElement) {
        'use strict';
        $('.intervalNumElement').removeClass('selected');
        intElement.addClass('selected');
        this.intervalNum = intElement.data('num');
        this.displayInterval();
    },
    displayInterval: function () {
        'use strict';
        $('.intervalDisplay').empty();
        $('.intervalDisplay').append('<div class="content" style="display:none">' + intervalSelector.intervalNum + intervalSelector.intervalType + "</div>");
        $('.intervalDisplay .content').fadeIn(300);
    },
    getInterval: function () {
        'use strict';
        return this.intervalNum + this.intervalType;
    },
    selectInterval: function (interval){
        'use strict';
        $('.intervalTypeElement').removeClass('selected');
        $('.intervalNumElement').removeClass('selected');
        this.intervalNum = interval.substring(0,1);
        this.intervalType = interval.substring(1,2);
        this.displayInterval();
    }
};