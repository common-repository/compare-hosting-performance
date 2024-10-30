
jQuery(document).ready(function () {

    //Тест по кнопке в админке
    jQuery('#startTest').on('click', function () {
        jQuery('#load').show();
        jQuery("#startTest").fadeOut(500);
        jQuery.ajax({
            type: "POST",
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'getTest'

            },
            success: function (result) {
                jQuery('#load').hide();
                jQuery("#startTest").fadeIn(300);
                jQuery("#tableRes").append(result);

            },
            error: function () {
                jQuery('#load').hide();
                jQuery("#startTest").fadeIn(300);
                jQuery('#load').append('<p>Произошла ошибка</p>');
            }
        });
    });

    var urlVar = window.location.search;

    //post до сервера статистики + отрисовка данных в админке
    if (urlVar.indexOf('jornal') + 1) {

        jQuery('#load').show();
        jQuery.ajax({
            type: "POST",
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'getPageZixn'

            },
            success: function (result) {
                jQuery('#load').hide();
                jQuery("#frameResult").append(result);

            },
            error: function () {
                jQuery('#load').hide();
                jQuery('#frameResult').append('<p>Произошла ошибка2</p>');
            }
        });
    }


});

