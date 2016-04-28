function ajaxBuilder(url, data, templateResult, templateSelection) {
    return {
        ajax: {
            url: url,
            type: 'POST',
            dataType: 'json',
            delay: 500,
            data: data,
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: templateResult,
        templateSelection: templateSelection
    }
}