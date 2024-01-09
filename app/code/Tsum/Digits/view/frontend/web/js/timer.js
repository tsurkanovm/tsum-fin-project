define([
    'uiElement',
    'uiEvents'
], function (Component, events) {
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
            }
        },

        initialize: function () {
            this._super();
            events.on('tsum_digits:start',this.start.bind(this));
            events.on('tsum_digits:stop',this.stop.bind(this));
            events.on('tsum_digits:pause',this.pause.bind(this));
            events.on('tsum_digits:unpause',this.unPause.bind(this));

            return this;
        },
        // require('uiRegistry').get(component => {if (component.name == 'digitsTimer') {console.log(component)}})

        start: function () {
            if (this.startId) {
                clearInterval(this.startId); // Clear existing interval if any
            }

            function coreTimer() {
                this.time = this.time + 1;
            }

            this.startId = setInterval(coreTimer.bind(this), 1000);
            this.status = 1;
        },

        pause: function () {
            if (this.startId) {
                clearInterval(this.startId);
                this.startId = null; // Clear the interval ID
            }
            this.status = 2;
        },

        unPause: function () {
            if (!this.startId) { // Only start if the timer is not already running
                this.start();
                this.status = 1;
            }
        },

        stop: function () {
            if (this.startId) {
                clearInterval(this.startId);
                this.startId = null;
            }
            this.time = 0;
            this.status = 0;
        },

        getTime: function () {
            return `${this.time} sec`;
        }
    });
});
