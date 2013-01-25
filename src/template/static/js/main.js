$(function(){
    if ($('#opera select').length ==0) return false
    $('#opera select').change(function(){
        var val = $(this).children('option:selected').val()
        $.getJSON('/?do=sync', {type:val}, function(data){
            alert(data.msg)
        })
    })
})
