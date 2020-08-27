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
    // Dadata
    $(".roulette-form input[name='fio']").keyup(function () {
        let container = $(".fio-field .suggestions");
        var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/fio";
        var token = "4d74e910fb52b66075703d9c1b4b4509acea1067";
        var query = $(this).val();
        var options = {
            method: "POST",
            mode: "cors",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Token " + token
            },
            body: JSON.stringify({query: query})
        }
        fetch(url, options)
            .then(response => response.text())
            .then(result => {
                let count = 5;
                container.html('');
                let arrResult = JSON.parse(result);
                arrResult.suggestions.forEach(function(el) {
                    if (count > 0) {
                        container.append('<span class="suggestions-item">' + el.value + '</span>');
                        count--;
                    }
                });
            })
            .catch(error => console.log("error", error));
    });

    $(document).on("click", ".suggestions-item", function () {
        $(".fio-field input[name='fio']").val($(this).text());
        $(".fio-field .suggestions").html("");
    });

    $(document).on('click', function (e) {
        var el = $(".suggestions");
        if ($(e.target).closest(el).length) return;
        $(".fio-field .suggestions").html("");
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
        $(".wrapper_roulette .win").append("<h4>Информация о вашем призе отправлена на email. </br> Вам необходимо распечатать уведомление для получения приза.</br> Мы свяжемся с вами по указанному телефону для уточнения даты и времени для вручения приза</h4>");
    });
}

function getRandomInRange(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}