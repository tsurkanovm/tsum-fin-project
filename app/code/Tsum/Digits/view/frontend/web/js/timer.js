define([
    'uiElement'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Tsum_Digits/timer',
            time: 0,
            startId: '',
            status: 0, // 0 - stopped, 1 - run, 2 -paused
            tracks: {
                time: true,
                status: true
            },
            listens: {
                status: 'onStatusChange' //@todo rewrite to use uiEvents
            }
        },

        // require('uiRegistry').get(component => {if (component.name == 'digitsTimer') {console.log(component)}})

        onStatusChange: function (newStatus) {
            switch (newStatus) {
                        case 0:
                            this.stop();
                            break;
                        case 1:
                            this.start();
                            break;
                            //@todo pause - unpause
                    }
        },

        start: function () {
            function coreTimer()
            {
                this.time = this.time + 1;
            }

            this.startId = setInterval(coreTimer.bind(this), 1000);
        },

        pause: function () {

        },

        unPause: function () {

        },

        stop: function () {
            clearInterval(this.startId);
            this.time = 0;
        },

        getTime: function () {
            return `${this.time} sec`;
        }
    });
});
