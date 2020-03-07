function init() {
    $("#login").on("input", function () {
        if ($(this).val().length >= 11) {
            $("#senha").focus();
        }
    });
    $('#login').mask('00000000000');
    floatingLabel.init();
    comoCriar = new Popup("comoCriar", "../modulos/login/comoCriar", "Como realizar meu registro?", "700px", "82%");
    comoCriar.setScroll(true)
    comoCriar.invoker()
    esqueciSenha = new Popup("esqueciSenha", "../modulos/login/esqueciSenha", "Esqueci minha senha", "500px", "460px");
    esqueciSenha.setScroll(false)
    esqueciSenha.setJS(true)
    esqueciSenha.invoker()
    $(function () {
        var width = $(window).width();
        var height = $(window).height();
        if (width <= 730 || height <= 528) {
            var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
            $("html, body").css({ "width": w, "height": h });
        }
    });
}

function enviarRecuperacao() {
    $("#sucessoEmail").hide()
    $("#erroEmail").hide()
    $("#waitingEmail").show()
    setTimeout(function () {
        var email = $('#recuperarEmail').val();
        if (email == "pedrocmota1@hotmail.com") {
            $("#sucessoEmail").show()
            $("#erroEmail").hide()
            $("#waitingEmail").hide()
        } else {
            $("#sucessoEmail").hide()
            $("#erroEmail").show()
            $("#waitingEmail").hide()
        }
    }, 1000);
}

function mainEmail() {
    $("#sucessoEmail").hide()
    $("#erroEmail").hide()
    $("#waitingEmail").hide()
}

class Campo {

    // input; enterClick; enterComponent

    constructor(input, enterClick, enterComponent) {
        this.input = input;
        this.enterClick = enterClick;
        this.enterComponent = enterComponent;
        var input, enterClick, enterComponent
        $(input).on('keypress', function (e) {
            if (e.which == 13) {
                event.preventDefault();
                if (enterClick == "focus") {
                    $(enterComponent).focus();
                }
                if (enterClick == "click") {
                    $(enterComponent).focus();
                    $(enterComponent).click();
                }
            }
        });
    }

    img(img) {
        var input = this.input
        $(input).focus(function () {
            $(input).css("background-image", "url(./icones/" + img + ".png)");
        });
        $(input).focusout(function () {
            $(input).css("background-image", "url(./icones/" + img + "-disable.png)");
        });
    }
}