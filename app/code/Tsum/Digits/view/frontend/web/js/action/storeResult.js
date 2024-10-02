define(['jquery','mage/storage','Magento_Customer/js/customer-data'], ($, storage, customerData) =>
{
    return payload => {
        //@todo
        // 1. create php object for result (swagger requirements)
        // 2. handle errors during the savings
        // 3. create action to get results? show the best result on the header
        const serviceUrl = 'rest/V1/digits/save';
        beforeSend();
        storage.post(serviceUrl, JSON.stringify({'result11' : payload}))
             .done(onSuccess.bind(this))
            .fail(onFail.bind(this))
            .always(afterSend);

        function onSuccess(response) {

        }
        function beforeSend() {
            $('body').trigger('ProcessStart');
        }
        function afterSend() {
            debugger;
            showMessage('response.responseJSON.message', 'error');
            $('body').trigger('ProcessStop');
        }

        function onFail(response) {
            showMessage(response.responseJSON.message, 'error');
        }

        function showMessage(messageText, messageType) {
            if (!messageText) return;

            const messagesSection  = customerData.get('messages')() ?? {};
            const messages = messagesSection.messages ?? [];
            customerData.set('messages', [
                ...messages,
                {
                    text: messageText,
                    type: messageType
                }
            ]);
        }
    };
})
