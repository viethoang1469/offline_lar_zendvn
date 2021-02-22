$(document).ready(function() {
    //////////////////////// Prevent closing from click inside dropdown
    $(document).on('click', '.dropdown-menu', function (e) {
        e.stopPropagation();
    });

      // make it as accordion for smaller screens
    if ($(window).width() < 992) {
        $('.dropdown-menu a').click(function(e){
            e.preventDefault();
            if($(this).next('.submenu').length){
                $(this).next('.submenu').toggle();
            }
            $('.dropdown').on('hide.bs.dropdown', function () {
                $(this).find('.submenu').hide();
            })
        });
    }
    if ($("#box-gold").length) {
        $("#box-gold").load($("#box-gold").data("url"), null, function(response, status) {
            if (status == 'success') {
                let data  = JSON.parse(response);
                $("#box-gold").html(renderGoldTable(data));
            }
        });
    }

    if ($("#box-coin").length) {
        $("#box-coin").load($("#box-coin").data("url"), null, function(response, status) {
            if (status == 'success') {
                let data  = JSON.parse(response);
                $("#box-coin").html(renderCoinTable(data));
            }
        });
    }

    var currentUrl = window.location.href;

    $('.main_nav_list a').each(function (index) {
        var href = $(this).attr('href');
        if (currentUrl == href) {
            $(this).addClass('active');

            if ($(this).data('parent')) {
                $('a[data-name="' + $(this).data('parent') + '"]').addClass('active');
            }
        }
    })

    $('#contact-form').on('submit', function () {
        localStorage.setItem("name", $('#contact-form [name="name"]').val());
        localStorage.setItem("email", $('#contact-form [name="email"]').val());
        localStorage.setItem("phone", $('#contact-form [name="phone"]').val());
    })

    if ($("#contact-form").length > 0) {
        $('#contact-form [name="name"]').val(localStorage.getItem("name"));
        $('#contact-form [name="email"]').val(localStorage.getItem("email"));
        $('#contact-form [name="phone"]').val(localStorage.getItem("phone"));
    }
});

function renderGoldTable(items) {
    let tmpl = $("#template-box-gold").html();
    Mustache.parse(tmpl);
    return Mustache.render(tmpl, { items: items });
}

function renderCoinTable(items) {
    let tmpl = $("#template-box-coin").html();
    Mustache.parse(tmpl);

    let list = {
        items: items,
        price: function () {
            return new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(this.quote.USD.price);
        },
        textColor: function () {
            return this.quote.USD.percent_change_24h > 0 ? 'class="text-success"' : 'class="text-danger"';
        },
        percentChange: function () {
            return (new Intl.NumberFormat("en-US", { maximumFractionDigits: 2 }).format(this.quote.USD.percent_change_24h) + "%");
        },
    };

    return Mustache.render(tmpl, list);
}
