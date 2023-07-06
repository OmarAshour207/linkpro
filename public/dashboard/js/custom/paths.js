$(document).ready(function() {
    $(document).on('change', '.floors', function () {
        var floorId = $('.floors option:selected').val();
        if(floorId) {
            $.ajax({
                url: "/dashboard/offices/paths",
                data_type: 'html',
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    floor_id: floorId
                }, success: function (data) {
                    $('.paths').html(data);
                    $('.offices').html('');
                }
            });
        } else {
            $('.paths').html('');
        }
    });
});
