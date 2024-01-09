define(['mage/storage',], storage =>
{
    return payload => {
        //@todo
        // 1. create php object for result (swagger requirements)
        // 2. handle errors during the savings
        // 3. create action to get results? show the best result on the header
        const serviceUrl = 'rest/V1/digits/save';
        storage.post(serviceUrl, JSON.stringify({'result' : payload}));
        //     .done(function (response) {
        //     alert({
        //         content: $t('Action Successfully completed.')
        //     });
        // }).fail(function (response) {
        //     alert({
        //         content: $t('There was error during saving data')
        //     });
        // });
    };
})
