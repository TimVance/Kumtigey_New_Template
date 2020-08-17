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
        right: x * width - first + getRandomInRange(1, 140)
    }, 10000, function () {
        let good = $(".wrapper_roulette_inner .list > .item").eq(x);
        good.addClass("selected");
        $(".wrapper_roulette .win").slideDown().html('<h2>Поздравляем Вас с выигрышем!</h2><div>Ваш подарок:</div> ');
        good.clone().appendTo(".wrapper_roulette .win");
        $(".wrapper_roulette .win").append("<h4>Мы скоро свяжемся с Вами по указанному телефону, для вручения подарка.</h4>");
    });
}

function getRandomInRange(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}