define([
    'jquery',
    'mage/url',
    'mage/multiselect',
    'jquery-ui-modules/widget'
], function ($, urlBuilder) {
    'use strict';

    $.widget('tsum.knowledgeCreator', $.mage.multiselect2, {
        options: {
            ajaxCreateItemsUrl: '',
            nextPageUrl: urlBuilder.build('know/ajax/getcategories'),
        },

        _create() {
            this._super();
            this.loadOptions();
            if (this._checkElements()) {
                this._bindEvents();
            }
        },


        _checkElements: function () {
            return this.element.siblings('.description-field').length > 0 &&
                   this.element.siblings('.add-button').length > 0;
        },

        _bindEvents: function () {
            this.element.on('change', () => this._toggleButtonState());
            this.element.siblings('.description-field').on('input', () => this._toggleButtonState());
            this.element.siblings('.add-button').on('click', () => this._sendData());
        },

        _toggleButtonState: function () {
            let description = this.element.siblings('.description-field').val();
            let isEnabled = this.element.val() && description;
            this.element.siblings('.add-button').prop('disabled', !isEnabled);
        },

        _sendData: function () {
            let selectedCategories = this.element.val();
            let description = this.element.siblings('.description-field').val();

            $.ajax({
                url: this.options.ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    description: description,
                    categoryIds: selectedCategories
                },
                success: function (response) {
                    // Handle success
                },
                error: function () {
                    // Handle error
                }
            });
        }
    });

    return $.tsum.knowledgeCreator;
});
