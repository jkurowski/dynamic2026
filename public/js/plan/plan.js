const resizeDelay = 200;

function onWindowResize() {
    window.setTimeout(function () {
        const e = $('#plan-holder').width();
        $("#invesmentplan").mapster("resize",e);
    }, resizeDelay);
}

$(document).ready(function(){
    $(".plan-control a").hover(function() {
        const e = $(this).attr("data-floornumber");
        $("area[alt='floor-"+ e +"']").mapster("set", true, {
            fillColor: "b79a5e",
            fillOpacity: 0.8
        })
    }, function() {
        $("area").mapster("set", false);
    });

    $('#invesmentplan').mapster({
        fillColor: 'fbc000',
        fillOpacity: 0.8,
        clickNavigate: true
    });
});

$(window).bind('resize', onWindowResize);
