define(['mage/utils/wrapper'], (wrapper) => {
    return function (originObject) {
        // add new function
        originObject.somethingNew = function () {
          console.log('New');
        };

        // extend existing one
        originObject.getPayload = wrapper.wrapSuper(originObject.getPayload, function () {
            // get result of origin function with new size value (origin it is 4)
            let newSizePayload = this._super(5);

            // add new key
            return {... newSizePayload, fakeKey:'from stupid mixin'}
        });

        return originObject;
    }
})
