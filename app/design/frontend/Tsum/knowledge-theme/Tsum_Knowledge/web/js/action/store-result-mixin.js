define(['mage/utils/wrapper'], (wrapper) => {
    return function (extendedFunction) {
        return wrapper.wrap(
            extendedFunction,
            function (originFunction, payload) {
                payload = {...payload, 'justTest': 'it is works!'};
                originFunction(payload);
            }
        )
    }
})
