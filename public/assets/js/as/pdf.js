// $("#originPdf-form").submit(function () {
//     as.btn.loading($("#save-upload"), 'Saving...');
// });
$(function () {
    $(document).ready(function () {
        $('#pdf-form').ajaxForm({
            beforeSend: function () {
                var percentage = '0';
            },
            uploadProgress: function (event, position, total, percentComplete) {
                var percentage = percentComplete;
                $('.progress .progress-bar').css("width", percentage+'%', function() {
                    return $(this).attr("aria-valuenow", percentage) + "%";
                })
            },
            complete: function (xhr) {
                console.log('File has edited');
            }
        });
    });
});
$("#cancel-upload").click(function () {
    $('#choose-modal').modal('hide')
});

$("#save-upload").click(function() {
    var fd = new FormData()
    var url = $(this).parent().data('url');
    var files = $('#pdf-upload')[0].files[0];
    fd.append('file', files);

    $.ajax({
        url: url,
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
            if(response != 0){
                $('#choose-modal').modal('hide')
                window.location.reload()
                //alert('file uploaded');
            } else{
                alert('file not uploaded');
            }
        },
    });
});

$("#downloadPDF").click(function () {
    var url = $(this).parent().data('url');
    var file = $('#filename').attr('value')
    console.log(url+'?file='+file);
    window.open(url+'?file='+file);

});

$("#downloadEeditPDF").click(function () {
    var url = $(this).parent().data('url');
    var file = $('#filename').attr('value')
    console.log(url+'?file='+file+'&type=1');
    window.open(url+'?file='+file+'&type=1');
});

$("#editPDF").click(function () {
    console.log('edit');
    var url = $(this).parent().data('url');
    var product_id = $("#product_id").val();
    var quantity = $("#quantity").val();
    var stock_location = $("#stock_location").val();
    var ean_number = $("#ean_number").val();
    var file = $('#filename').attr('value');

    var data={
        'product_id':product_id,
        'quantity':quantity,
        'stock_location':stock_location,
        'ean_number':ean_number,
        'file':file
    }
    console.log('data',data)

    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: "json",
        success: function(response){
            if(response != 0){
                alert('file edit successful');

            } else{
                alert('file not uploaded');
            }
        },
    });

});









