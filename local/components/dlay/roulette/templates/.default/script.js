$(function () {
    $(".start-roulette").click(function () {
        $(".roulette-form form").submit();
    });
    $(".roulette-form form").submit(function () {
        let form = $(this);
        let mark = true;
        form.serializeArray().forEach(function (el) {
            if (el.value == "" && mark) {
                $(".roulette-form input[name='" + el.name + "']").focus().parent().find(".error").fadeIn();
                mark = false;
                return false;
            }
        });
        if (mark) {
            $.ajax({
                url: "/local/components/dlay/roulette/templates/.default/ajax.php",
                method: "POST",
                dataType: "JSON",
                data: form.serialize(),
                success: function (data) {
                    console.log(data);
                    if (data.success == "Y") {
                        $(".wrapper_roulette .after-win").slideUp(function () {
                            $(this).remove();
                        });
                        startRoulette(data.number);
                    }
                    else alert(data.text);
                }
            });
        }
        return false;
    });
    $(".roulette-form form input").change(function () {
        $(this).parent().find(".error").fadeOut();
    });
});

function startRoulette(n) {
    x = n;
    first = 490;
    width = 150;
    $('.wrapper_roulette_inner .window').animate({
        right: x * width - 490 + (width - 50)
    }, 10000, function () {
        $(".wrapper_roulette_inner .list > .item").eq(x).addClass("selected");
        $(".wrapper_roulette .win").slideDown().text('Поздравляем! Вы выиграли!');
    });
}