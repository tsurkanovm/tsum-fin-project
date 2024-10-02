define(['uiElement'], uiElement => {
    'use strict';

    return uiElement.extend({
        defaults: {
            imports: {
                time: '${ $.provider }:time',
                status: '${ $.provider }:status'
            },
            currentColour: 'yellow',
            attempts: 20,
            colours: [
                'yellow', 'blue', 'red', 'green', 'purple', 'orange'
            ],
            coloursQty: 6,

            track: {
                currentColour: true,
                status: true
            }
        },

        initialize: function () {
          console.log('RRGFB');
        },
        onYesClick: function () {
            console.log(this.currentColour, 'trt');
            this.currentColour = 'red';
        }
    });
});
