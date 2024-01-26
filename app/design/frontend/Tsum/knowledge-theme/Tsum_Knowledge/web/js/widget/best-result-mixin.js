define(['jquery'], ($) => {
    const mixin = {
        // add functionality after existing function
        _handleSuccessResults(response)  {
            this._super(response);
            // if origin funct returns value we can get it and edit:
            // let result = this._super(response);
        }
    };

    return function (originWidget) {

        $.widget('tsum.bestResult', originWidget, mixin);

        return $.tsum.bestResult;
    }
})
