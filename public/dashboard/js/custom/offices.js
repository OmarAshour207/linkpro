$(document).ready(function() {
    $(document).on('change', '.paths', function () {
        var pathId = $('.paths option:selected').val();
        if(pathId) {
            $.ajax({
                url: "/dashboard/contents/offices",
                data_type: 'html',
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    path_id: pathId
                }, success: function (data) {
                    $('.offices').html(data);
                }
            });
        } else {
            $('.offices').html('');
        }
    });
});
