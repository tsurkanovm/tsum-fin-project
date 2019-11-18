
define([
        'jquery',
        'jquery/ui'
    ],
    function($) {
        $.widget('tsum.trainer', {
            version : "0.0.1",

            _create: function () {
                // console.log(this.element);
                console.log(this.options);
                var answer;
                var total_exp = 10;
                var current_exp = 1;
                var set_interval_id = null;
                var currentTimeVal = 1;
                var btn_start = document.getElementById('btn_start');
                var btn_stop = document.getElementById('btn_stop');

                if(btn_start)
                    btn_start.onclick = start;
                if(btn_stop)
                    btn_stop.onclick = pause;

                function clockGoesOn(){
                    var el = document.getElementById('clock');
                    if (el) {
                        el.innerHTML = currentTimeVal;
                        currentTimeVal++;
                    }
                }

                function start(){
                    set_interval_id = setInterval(clockGoesOn, 1000);
                    addNewExpression();
                }

                function pause(){
                    if( set_interval_id )
                        clearInterval(set_interval_id);
                    var answer_input = document.getElementById('answer_input');
                    if (answer_input) {
                        answer_input.disabled = true
                    }
                }

                function addNewExpression(){
                    var title = "Решено - " + current_exp + " из " + total_exp;
                    var title_tag = document.createElement("h4");
                    title_tag.innerHTML = title;
                    var expression = document.getElementById('exp');
                    if (expression) {
                        // already exist, so refresh it
                        expression.innerHTML = '';
                    }else{
                        var expression = document.createElement("div");
                        expression.id = "exp";
                    }
                    var answer_input = document.createElement("input");
                    answer_input.id = 'answer_input';
                    answer_input.onkeypress = checkAnswer;
                    expression.appendChild(title_tag);
                    expression.innerHTML = expression.innerHTML + createExpression();
                    expression.appendChild(answer_input);
                    document.body.appendChild(expression);
                }

                function checkAnswer ( event ){
                    if (event.keyCode == 13) {
                        if ( answer == document.activeElement.value ) {
                            current_exp++;
                            if ( current_exp < total_exp ) {
                                addNewExpression();
                                var answer_input = document.getElementById('answer_input');
                                if (answer_input) {
                                    answer_input.focus();
                                }
                            }else{
                                finishTest();
                            }
                        }
                    }
                }

                function finishTest(){
                    pause();
                    var expression = document.getElementById('exp');
                    if (expression) {
                        // already exist, so refresh it
                        expression.innerHTML = '';
                    }
                    var title = "Поздравляем " + total_exp + " примеров решены за " + currentTimeVal + " секунд!";
                    var title_tag = document.createElement("h4");
                    title_tag.innerHTML = title;
                    document.body.appendChild(title_tag);
                }

                function getRandomInt(min, max) {
                    return Math.floor(Math.random() * (max - min)) + min;
                }

                function getSign() {
                    var sign_index = getRandomInt(0,2);
                    switch (sign_index) {
                        case 0:
                            return '+';
                        case 1:
                            return '-';
                        default :
                            return '*';
                    }
                }

                function createExpression(){
                    var sign = getSign();
                    if (sign == '-') {
                        var first = getRandomInt(9,19);
                        var second = getRandomInt(0,9);
                        answer = first - second;
                    }else if( sign == '+' ) {
                        var first = getRandomInt(0,9);
                        var second = getRandomInt(0,9);
                        answer = first + second;
                    }
                    else if( sign == '*' ) {
                        var first = getRandomInt(0,9);
                        var second = getRandomInt(0,9);
                        answer = first * second;
                    }
                    return first + ' ' + sign + ' ' + second + ' = ';
                }
            }
        });

        return $.tsum.trainer;
    });
