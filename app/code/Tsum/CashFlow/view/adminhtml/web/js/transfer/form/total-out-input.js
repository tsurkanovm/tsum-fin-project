define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/element/abstract',
    'jquery/ui'
], function ($, _, Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
        },

        onUpdate: function (value) {
            if (this.source.get('data.total_in') == 0) {
                this.source.set('data.total_in', value);
            }
        }
    });
});

