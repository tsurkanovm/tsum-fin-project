define([], () => {
    return (originDigits) => {
        return originDigits.extend(
            {
                getStrokePlaceholder: function ()
                {
                    let originResult = this._super();
                    originResult += ' this part from mixin';

                    return originResult;
                }
            }
        );
    }
})
