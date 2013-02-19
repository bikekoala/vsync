/**
 * 同步操作
 */
$(function(){ 
    if ($('#opera select').length == 0) return false
    $('#opera select').change(function(){
        var val = $(this).children('option:selected').val()
        $.getJSON('/?do=sync',{type:val},function(j){
            alert(j.msg)
        })
    })
})

/**
 * 退出登录
 */
$(function(){ 
    $('#paper a[app]').bind('click', function(){
        var app = $(this).attr('app')
        var url = '?do=cauth.' + app
        var logo = 'static/img/' + app + '_logo.png'
        $.getJSON(url,function(j){
            if (j.stat) {
                var $ele = $('#' + app)
                $ele.empty()
                $ele.append('<a href="?do=oauth.'+app+'"><img src='+logo+' /></a>')
                grayscale($ele.find('img'))
            } else {
                alert(j.msg)
            }
        })
        return false
    })
})

/**
 * Avator效果
 */
$(window).load(function(){
    grayscale($('.logo img'))
})

/**
 * Grayscale
 */
function grayscale($ele){
    // 跨域图片不执行
    var arr = new Array()
    $ele.each(function(i){
        if (this.src.indexOf(document.domain) >= 0) {
            arr[i] = this
        }
    })
    if (arr.length == 0) return false
    var $ele = $(arr)

    // Fade in images so there isn't a color "pop" document load and then on window load
    $ele.animate({opacity:1},500)

    // This waits until images have loaded which is essential
    $ele.each(function(){
        // clone image
        var $img = $(this)
        $img.css({'position':'absolute'}).
             addClass('img_degrayscale').
             wrap("<div class='img_wrapper' style='display: inline-block'>").
             clone().
             addClass('img_grayscale').
             css({'position':'absolute','z-index':'998','opacity':'0'}).
             insertBefore($img).
             queue(function(){
                 var $img = $(this)
                 $img.parent().css({'width':this.width,'height':this.height})
                 $img.dequeue()
        })
        this.src = grayscale_canvas(this.src)
    })

    // Fade image 
    $('.img_degrayscale').mouseover(function(){
        $(this).stop().animate({opacity:1}, 700)
    })
    $('.img_grayscale').mouseout(function(){
        $(this).stop().animate({opacity:0}, 700)
    })
}

// Grayscale w canvas method
function grayscale_canvas(src){
    var canvas = document.createElement('canvas')
    var ctx = canvas.getContext('2d')
    var imgObj = new Image()
    imgObj.src = src
    canvas.width = imgObj.width
    canvas.height = imgObj.height
    ctx.drawImage(imgObj, 0, 0)
    var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height)
    for(var y = 0; y < imgPixels.height; y++){
        for(var x = 0; x < imgPixels.width; x++){
            var i = (y * 4) * imgPixels.width + x * 4
            var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3
            imgPixels.data[i] = avg
            imgPixels.data[i + 1] = avg 
            imgPixels.data[i + 2] = avg
        }
    }
    ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height)
    return canvas.toDataURL()
}
