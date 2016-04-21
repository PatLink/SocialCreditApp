/* Java Script functions */
$(function () {
    // users -> SCP Abrechnung -> drucken und download
    $(".pdf").on("click", function (event) {
        event.preventDefault();
        var pdf_button = $(this);
        $(".filter").each(function () {
            if ($(this).hasClass("active")) {
                var id = parseInt($(this).data("id"));
                if ((pdf_button).attr('id') == 'drucken') {
                    window.open('scp/print/' + id).print();
                }
                else if((pdf_button).attr('id') == 'download'){
                    window.open('scp/download/' + id);
                }
            }
        });
    });

    // users -> SCP Abrechnung -> first active
    $(".filter").each(function(){
        if($(".btn-group").data("activeonload") == $(this).data("id")){
            $(this).addClass("active");
        }
    });

    // users -> SCP Abrechnung -> Projekte nach Jahren anzeigen & Filter active
    $(".filter").on("click", function(event) {
        event.preventDefault();
        $(".header ~ tr").remove();
        $(".loading").show();
        $(".filter").removeClass("active");
        $(this).addClass("active");
        $('#search').val('');
        var id = parseInt($(this).data("id"));
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        $.post(
            'scp',
            {filter_id:id},
            function(data){
                $(".loading").hide();
                $(".header").after(data);
            });
    });

    // SGL -> SCP Abrechnung -> Live-Suche eines Studenten
    $("#search").on("keyup", function() {
        var value = $(this).val().toUpperCase();

        $("table tr").each(function(index) {
            if (index !== 0) {

                $row = $(this);

                var id = $row.find("td:first").text().toUpperCase();

                if (id.indexOf(value) !== 0) {
                    $row.hide();
                }
                else {
                    $row.show();
                }
            }
        });
    });

    // Carousel für die Startseite [Responsive]
    $('.scp-project-slider').owlCarousel({
        loop: true,
        margin: 10,
        padding: 10,
        navText: [
            "<i class='icon-chevron-left icon-white'><i class='fa fa-chevron-left'></i> Vorheriges Projekt</i>",
            "<i class='icon-chevron-right icon-white'>Nächstes Projekt <i class='fa fa-chevron-right'></i></i>"
        ],
        dots: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 3,
                nav: false
            },
            1000: {
                items: 5,
                nav: true,
                loop: false
            }
        }
    })
});