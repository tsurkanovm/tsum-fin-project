define([], () => {
    return {
        payload: {
            hits: 8,
            time: 88,
            customer_id: 1
        },

        getPayload: function (size) {
            this.payload = {...this.payload, size: size};

            return this.payload;
        }
    }
})
