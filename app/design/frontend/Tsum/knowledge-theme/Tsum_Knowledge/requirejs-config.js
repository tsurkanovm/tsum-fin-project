// eslint-disable-next-line no-unused-vars
const config = {
    config: {
        mixins: {
            'Tsum_Digits/js/action/storeResult': {
                'Tsum_Knowledge/js/action/store-result-mixin': false
            },
            'Tsum_Digits/js/action/get-mock-payload': {
                'Tsum_Knowledge/js/action/get-mock-payload-mixin': false
            },
            'Tsum_Digits/js/widget/best-results': {
                'Tsum_Knowledge/js/widget/best-result-mixin': false
            },
            'Tsum_Digits/js/digits': {
                'Tsum_Knowledge/js/ui/digits-mixin': false
            }
        }
    }
};
