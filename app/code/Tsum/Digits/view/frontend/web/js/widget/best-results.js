define(
    [
        'jquery',
        'mage/storage',
        'jquery-ui-modules/widget'
    ], ($, storage) => {

        $.widget('tsum.bestResult', {
            options: {
                title:'Default title',
                getResultsUrl: 'rest/V1/digits/best',
                results: []
            },

            _create() {
                this._renderTitle();
                // for the case of outside invoking rendering results, needs to comment rendering and invoke outside
                this.loadResults();
            },

            loadResults() {
                //debugger;
                storage.get(
                    this.options.getResultsUrl, false
                ).done(this._handleSuccessResults.bind(this))
                    .fail(function (response) {
                    console.log('Error', response);
                });
            },

            _handleSuccessResults(response)  {
                this.options.results = response;
                this.renderResults();
            },

            _renderTitle() {
                $('<strong>').text(this.options.title).appendTo(this.element);
            },

            renderResults() {
                this.options.results.forEach((result) => {
                    const {customer, hits, time} = result;
                    let resultText = `${customer} - ${hits} hits, with time ${time} sec.`;
                    $('<div>').text(resultText).appendTo(this.element);
                }, this);
            }
        });

        return $.tsum.bestResult;
});
