$(function () {
    $(".start-roulette").click(function () {
        $(".roulette-form form").submit();
        /*
        x = 10;
        first = 490;
        width = 150;
        $('.window').animate({
            right: x * width- 490 + (width - 50)
        }, 10000, function () {
            $(".wrapper_roulette_inner .list > .item").eq(x).addClass("selected");
        });
         */
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
                data: form.serialize(),
                success: function (data) {
                    console.log(data);
                }
            });
        }
        return false;
    });
    $(".roulette-form form input").change(function () {
        $(this).parent().find(".error").fadeOut();
    });
});