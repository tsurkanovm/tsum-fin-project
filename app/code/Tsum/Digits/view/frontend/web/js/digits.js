define([
    'uiElement',
    'ko',
    'mage/storage',
    'mage/translate'
], function (Component, ko, storage, $t) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Tsum_Digits/digits',
            links: {
                status: '${ $.provider }:status'
            },

            imports: {
                time: '${ $.provider }:time'
            },

            strokeHistory: [],
            hasError: false,
            validationMessage: '',
            isStrokeInputVisible: true,
            isStrokeBtnVisible: false,
            size: 4,
            gameOver: false,
            noMoves: true,

            tracks: {
                strokeHistory: true,
                isStrokeInputVisible: true,
                isStrokeBtnVisible: true,
                strokeValue: true,
                size: true,
                gameOver: true,
                noMoves: true,
                status: true,
                hasError: true,
                validationMessage: true
            },

            listens: {
                strokeValue: 'strokeValueHandler'
            }
        },

        initiateGoal: function () {
            function shuffle(array)
            {
                let currentIndex = array.length, temporaryValue, randomIndex;
                while (0 !== currentIndex) {
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            }
            let buffer = Array.from({length: 10}, function (value, key) {
                return key;
            });

            this.goal = shuffle(buffer).slice(0, this.size);
        },

        addStroke: function () {
            if (this.noMoves) {
                this.initiateGoal();
                console.log(this.goal);
                this.status = 1; // start
                this.noMoves = false;
            }
            this.strokeHistory.push({stroke: this.strokeValue, score: this.getCurrentScore(), time: this.time});
            this.strokeValue = '';

            if (this.gameOver) {
                this.sendResultToSever();
            }
        },

        getCurrentScore: function () {
            let strokeArr = Array.from(this.strokeValue), score = { hits : 0, precise : 0 };
            strokeArr.forEach((value, index) => {
                let goalIndex = this.goal.indexOf(parseInt(value));
                if (goalIndex !==-1) {
                    score.hits +=1;
                    if (goalIndex === index) {
                        score.precise +=1;
                    }
                }
            });

            if (score.precise == this.size) {
                this.gameOverHandler();
            }

            return `${score.hits} : ${score.precise}`;
        },

        gameOverHandler: function () {
            this.gameOver = true;
            this.isStrokeBtnVisible = false;
            this.isStrokeInputVisible = false;
        },

        sendResultToSever: function () {
            let payload = {
                hits: this.strokeHistory.length,
                creation_time: this.time,
                customer_id: 1,
                size: this.size
            };

            this.status = 0; // stop
            let serviceUrl        = 'rest/V1/digits/save';

            console.log('Final result is - ', payload);

            // storage.post(
            //     serviceUrl,
            //     JSON.stringify(payload)
            // ).done(function (response) {
            //     alert({
            //         content: $t('Action Successfully completed.')
            //     });
            // }).fail(function (response) {
            //     alert({
            //         content: $t('There was error during saving data')
            //     });
            // });

        },

        getStrokePlaceholder: function () {

            return $t('Place your stroke here...');
        },

        strokeValueHandler: function (stroke) {
            if (stroke.length == this.size) {
                console.log(stroke);
                if (!checkOnUniqueDigits()) {
                    showError($t('Digits must be unique'));
                    return;
                }
                if (!this.strokeHistory.every((historyEl) => {
                    return historyEl.stroke !== stroke;
                })) {
                    showError($t('Strokes must be unique'));
                    return;
                }

                this.isStrokeBtnVisible = true;
                this.validationMessage = '';
            }

            function showError(msg)
            {
                this.isStrokeBtnVisible = false;
                this.hasError = true;
                this.validationMessage = msg;
            }

            function checkOnUniqueDigits()
            {
                let arr = stroke.split('');
                let newArr = arr.filter(function (value, index, self) {
                    return self.indexOf(value) === index;
                });

                return (newArr.length === arr.length);
            }
        },

        handleKeyup: function (data, event) {
            if (event.keyCode === 13) {
                if (this.isStrokeBtnVisible) {
                    event.preventDefault();
                    event.stopPropagation();
                    this.addStroke();
                }
            }
        },

        gameOverMessage: function () {
            return `You win! Your result is ${this.strokeHistory.length} hits`
        }
    });
});
