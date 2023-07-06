$(document).ready(function() {
    $(document).on('change', '.company_id', function () {
        var companyId = $('.company_id option:selected').val();
        if(companyId) {
            $.ajax({
                url: "/dashboard/paths/floors",
                data_type: 'html',
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    company_id: companyId
                }, success: function (data) {
                    $('.floors').html(data);
                    $('.paths').html('');
                    $('.offices').html('');
                }
            });
        } else {
            $('.floors').html('');
            $('.paths').html('');
        }
    });
});
