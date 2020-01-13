define([
    'jquery'
], function ($) {
    'use strict';

    return {
        time: 0,
        startId: '',

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
            let currentTime = this.time;
            this.time = 0;

            return currentTime;
        }
    }
});