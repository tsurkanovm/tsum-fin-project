define([
    'uiComponent',
    'ko',
    'Tsum_Digits/js/timer',
    'mage/translate'
], function (Component, ko, timer, $t) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Tsum_Digits/digits'
        },
        strokeHistory: ko.observableArray([]),
        isStrokeInputVisible: ko.observable(true),
        isStrokeBtnVisible: ko.observable(false),
        strokeValue: ko.observable(''),
        size: ko.observable('4'),
        gameOver: ko.observable(false),
        noMoves: ko.observable(true),

        /* @todo
        4. ajax for result
         */
        initialize: function () {
            let self = this;
            this._super();
            this.strokeValue.hasError = ko.observable(false);
            this.strokeValue.validationMessage = ko.observable('');
            this.strokeValue.extend({rateLimit: 50});
            this.strokeValue.subscribe(function (newValue) {
                self.strokeValueHandler(newValue);
            });
            // this.strokeHistory.toString = function () {
            //     return 'test!!';
            // };
            return this;
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
            this.goal = shuffle(buffer).slice(0, this.size());
        },

        addStroke: function () {
            let self = this;
            if (self.noMoves()) {
                self.initiateGoal();
                alert(self.goal);
                timer.start();
                self.noMoves(false);
            }
            self.strokeHistory.push({stroke: self.strokeValue(), score: self.getCurrentScore(), time: timer.time});
            self.strokeValue('');
        },

        getCurrentScore: function () {
            let self = this;
            let strokeArr = Array.from(this.strokeValue()), score = { hits : 0, precise : 0 };
            strokeArr.forEach(function (value, index) {
                let goalIndex = self.goal.indexOf(parseInt(value));
                if (goalIndex !==-1) {
                    score.hits +=1;
                    if (goalIndex === index) {
                        score.precise +=1;
                    }
                }
            });

            if (score.precise == this.size()) {
                this.gameOverHandler();
            }

            return `${score.hits} : ${score.precise}`;
        },

        gameOverHandler: function () {
            this.gameOver(true);
            this.isStrokeBtnVisible(false);
            this.isStrokeInputVisible(false);
            this.sendResultToSever();
        },

        sendResultToSever: function () {
            console.dir({hits:this.strokeHistory().length, time: timer.stop()});
        },

        getStrokePlaceholder: function () {

            return $t('Place your stroke here...');
        },

        strokeValueHandler: function (stroke) {
            let self = this;
            if (stroke.length == this.size()) {
                if (!checkOnUniqueDigits()) {
                    showError($t('Digits must be unique'));
                    return;
                }
                if (!this.strokeHistory.every(function (historyEl) {
                    return historyEl.stroke !== stroke;
                })) {
                    showError($t('Strokes must be unique'));
                    return;
                }
                this.isStrokeBtnVisible(true);
                this.strokeValue.validationMessage('');
            }

            function showError(msg)
            {
                self.isStrokeBtnVisible(false);
                self.strokeValue.hasError(true);
                self.strokeValue.validationMessage(msg);
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
                if (this.isStrokeBtnVisible()) {
                    event.preventDefault();
                    event.stopPropagation();
                    this.addStroke();
                }
            }
        },

        gameOverMessage: function () {
            return `You win! Your result is ${this.strokeHistory().length} hits`
        }

    });
});
