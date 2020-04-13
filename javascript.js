function init() {
    $("#login").on("input", function () {
        if ($(this).val().length >= 11) {
            $("#senha").focus();
        }
    });
    $("#login").mask("00000000000");
    floatingLabel.init();
    $(function () {
        var width = $(window).width();
        var height = $(window).height();
        if (width <= 730 || height <= 528) {
            var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
            $("html, body").css({ "width": w, "height": h });
        }
    });

    login = new Campo("#login", "focus", "#senha")
    senha = new Campo("#senha", "click", "#botao")
    login.img("usuario")
    senha.img("senha")
}

function posInit() {
    comoCriar = new Popup("comoCriar", "../modulos/login/comoCriar", "Como realizar meu registro?", "700px", "85%");
    comoCriar.setScroll(true)
    comoCriar.invoker()
    esqueciSenha = new Popup("esqueciSenha", "../modulos/login/esqueciSenha", "Esqueci minha senha", "500px", "460px");
    esqueciSenha.setScroll(false)
    esqueciSenha.setJS(true)
    esqueciSenha.invoker()

    sobre = new Popup("sobre", "../modulos/sobre", "Sobre o SiGAÊ");
    sobre.setCss(true)
    sobre.setJS(true)
    sobre.setScroll(true)
    sobre.invoker()
}

function logar() {
    loginstr = $("#login").val()
    senhastr = $("#senha").val()
    if (loginstr != "" & senhastr != "") {
        $("#erro").hide()
        $("#waiting").show()
        request = new Request()
        request.add("login", loginstr)
        request.add("senha", senhastr)
        esperado = ["CON", "MAT", "SEN", "REG", "INA"] /* JÁ ESTA LOGADO, CONSEGUIU LOGAR, MATRICULA ERRADA, SENHA ERRADA, NAO CONFIRMOU REGISTRO, CONTA INATIVA */
        request.send("POST", esperado, (resultado) => {
            $("#erro").show()
            if (resultado == undefined) {
                $(".erroTexto").text("Erro grave: Requisição falha")
            } else {
                resposta = resultado.resposta;
                erro = resultado.erro;
                if (resposta != null) {
                    if (resposta == "INA") {
                        $(".erroTexto").text("Essa conta está inativa")
                        $("#waiting").hide()
                        $("#erro").show()
                    }
                    if (resposta == "REG") {
                        $("#erro").hide()
                        window.location.href = "./registrar";
                    }
                    if (resposta == "MAT") {
                        $(".erroTexto").text("A matrícula inserida não pertence a uma conta")
                        $("#waiting").hide()
                        $("#erro").show()
                    }
                    if (resposta == "SEN") {
                        $(".erroTexto").text("Sua senha está incorreta. Confira-a")
                        $("#waiting").hide()
                        $("#erro").show()
                    }
                    if (resposta == "CON") {
                        $("#erro").hide()
                        window.location.href = "./sistema";
                    }
                } else {
                    $("#waiting").hide()
                    $(".erroTexto").text("Erro grave: Requisição confusa")
                    alert(erro)
                }
            }
        });
    } else {
        if (loginstr == "") {
            login.erro()
        }
        if (senhastr == "") {
            senha.erro()
        }
    }
}

function main() {
    $("#erro").hide(500)
    $("#waiting").hide(500)
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
            html: '<div class="container d-flex h-100 divContainer" style="display:inline-flex"><i class="material-icons iconeErro">lock</i><div class="expirado align-self-center">Sua sessão é inválida ou foi expirada!</div></div>',
            width: '380px',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 2000,
            backdrop: `transparent`,
            background: 'rgb(50, 50, 50)',
            onOpen: function () {
                $(".swal2-header").addClass("header2")
                $(".swal2-container.swal2-top-end.swal2-shown > div").addClass("content2")
            },
            onClose: function () {
                $(".swal2-header").removeClass("header2")
                $(".swal2-container.swal2-top-end.swal2-shown > div").removeClass("content2")
            }
        })
        window.history.pushState("", "", "./");
        return;
    }
    var parametro = get_parametro("reg")
    if (parametro != undefined) {
        Swal.fire({
            position: 'top-end',
            html: '<div class="container d-flex h-100 divContainer" style="display:inline-flex"><i class="material-icons iconeOk">done</i><div class="expirado align-self-center">Sua conta foi registrada com sucesso!</div></div>',
            width: '380px',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 2000,
            backdrop: `transparent`,
            background: 'rgb(50, 50, 50)',
            onOpen: function () {
                $(".swal2-header").addClass("header2")
                $(".swal2-container.swal2-top-end.swal2-shown > div").addClass("content2")
            },
            onClose: function () {
                $(".swal2-header").removeClass("header2")
                $(".swal2-container.swal2-top-end.swal2-shown > div").removeClass("content2")
            }
        })
        window.history.pushState("", "", "./");
        return;
    }
    var parametro = get_parametro("senhaTrocada")
    if (parametro != undefined) {
        Swal.fire({
            position: 'top-end',
            html: '<div class="container d-flex h-100 divContainer" style="display:inline-flex"><i class="material-icons iconeOk">done</i><div class="expirado align-self-center">Sua senha foi alterada com sucesso!</div></div>',
            width: '380px',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 2000,
            backdrop: `transparent`,
            background: 'rgb(50, 50, 50)',
            onOpen: function () {
                $(".swal2-header").addClass("header2")
                $(".swal2-container.swal2-top-end.swal2-shown > div").addClass("content2")
            },
            onClose: function () {
                $(".swal2-header").removeClass("header2")
                $(".swal2-container.swal2-top-end.swal2-shown > div").removeClass("content2")
            }
        })
        window.history.pushState("", "", "./");
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
                    document.activeElement.blur();
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