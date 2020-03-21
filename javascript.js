function init() {
    $("#login").on("input", function () {
        if ($(this).val().length >= 11) {
            $("#senha").focus();
        }
    });
    $("#login").mask("00000000000");
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

function rightAlertas() {
    var parametro = get_parametro("expirado")
    if (parametro != undefined) {
        Swal.fire({
            position: 'top-end',
            html: '<h2 class="expirado">Sua sessão é inválida ou já foi expirada</h2>',
            width: '350px',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 2000,
            backdrop: `transparent`,
            background: 'rgb(50, 50, 50)'
        })
        window.history.pushState("", "", "./");
        return;
    }
    var parametro = get_parametro("reg")
    if (parametro != undefined) {
        Swal.fire({
            position: 'top-end',
            html: '<div style="display:inline-flex"><i class="material-icons iconeOk">add</i><h2 class="expirado">Sua conta foi registrada com sucesso!</h2></div>',
            width: '350px',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 20000,
            backdrop: `transparent`,
            background: 'rgb(50, 50, 50)'
        })
        //window.history.pushState("", "", "./");
        return;
    }
}

class Campo {

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
        var classe = this
        var input = this.input
        $(input).focus(function () {
            $(input).css("background-image", "url(./icones/" + img + ".png)");
            classe.removeErro()
        });
        $(input).focusout(function () {
            $(input).css("background-image", "url(./icones/" + img + "-disable.png)");
        });
    }

    erro() {
        $(this.input).attr("style", "border: 1px solid rgb(220, 20, 60) !important");
    }

    removeErro() {
        $(this.input).css("border", "");
    }
}