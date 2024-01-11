define([
    'jquery',
    'mage/url',
    'mage/multiselect'
], function ($, urlBuilder) {
    'use strict';

    return function (config, element) {

        $(element).multiselect2({ nextPageUrl: urlBuilder.build('know/ajax/getcategories')});
        $(element).multiselect2('appendOptions', [{"value": "1", "label": "Label #1"}, {"value": "2", "label": "Label #2"}]);

        console.log($(element).data());
            //'appendOptions: [{"value": "1", "label": "Label #1"}, {"value": "2", "label": "Label #2"}]});
    }
    // $.widget('tsum.test', {
    //     options: {
    //         ajaxCreateItemsUrl: '',
    //         nextPageUrl: urlBuilder.build('know/ajax/getcategories'),
    //     },
    //
    //     _create() {
    //        console.log('widget is running');
    //
    //     }
    // });
    //
    // return $.tsum.test;
});
