$(function(){
    $('#showModalAnggota').click(function() {
        $("#anggota_id").val('');
        $("#anggotaForm").trigger("reset");
        $("#anggotaModalLabel").html("Tambah Anggota");
        $("#anggotaModal").modal("show");
        $('.modal-backdrop').hide()
    })
    $("#anggotaForm").on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url:$(this).attr('action'),
            method:$(this).attr('method'),
            data:new FormData(this),
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function(){
                $(document).find('span.error-text').text('');
            },
            success:function(data){
                if(data.status == 0){
                    $.each(data.error, function(prefix, val){
                        $('span.'+prefix+'_error').text(val[0]);
                    });
                }else{
                    $('#anggotaForm')[0].reset();
                    alert(data.msg);
                }
            }
        });
    });
});