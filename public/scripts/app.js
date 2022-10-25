$(document).ready(function () {
    $('#modalForm').on('show.bs.modal', function (event) {
        //получаем контент диалогового окна. В зависимости от кнопки диалоговое окно отличается
        if ($(event.relatedTarget).data('request').length > 0) {
            let url = $(event.relatedTarget).data('request');
            let data = {url: url, targetRefresh: $(event.relatedTarget).data('loadcontenttarget')};
            $.ajax({
                type: 'GET',
                url: url,
                data: data,
                success: function (result) {
                    let json = jQuery.parseJSON(result);
                    if ((json.data) && (json.status === "new")) {
                        let ajax_wrapper = document.querySelector(json.targetRefresh);
                        ajax_wrapper.innerHTML = json.data;
                    }
                    else{
                        $('#modalForm').modal('hide');
                    }
                },
            });
        }
    });

    $(document).on('submit','body form#formModal',function (event) {
        event.preventDefault();
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                let json = jQuery.parseJSON(result);
                let ajax_wrapper = document.querySelector('[data-ajax]');
                if (json.data) {
                    ajax_wrapper.innerHTML = json.data;
                } else {
                    if (json.status === "success"){
                        ajax_wrapper.innerHTML = "<h3>Задача успешно сохранена</h3>";
                        refteshTableContent();
                    }
                }
            },
        });
    });
});
function refteshTableContent(){
    $.ajax({
        type: 'POST',
        url: location.href,
        data: {refresh:1},
        success: function (result) {
            let ajax_wrapper = document.querySelector('#TableSection');
            ajax_wrapper.innerHTML = result;
        },
    });
}