var jsonDados

function init_sigae(mainJson) {
    $(window).on("load", function() {
        jsonDados = JSON.parse(mainJson);
        $(".nome").text(getNome())
        $(".tipo").text(getTipoNome(getTipo()))

        resizer = function () {
            var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
            $("html, body").css({ "height": h });
        }
        resizer()
        $(window).on('resize', function () {
            resizer()
        });

        initMaterialize()
        loadMainJson()
        definirVersao()
        setTimeout(function () {
            if (moduloParametro == null) {
                mostrarInterface()
            }
        }, 400);
        setTimeout(function () {
            alertasTop()
        }, 800);
    })
}

function initMaterialize() {
    $(document).ready(function () {
        $(".side-nav.fixed").css("display", "block");
        $(".button-collapse").sideNav({
            onOpen: function () {
                $("body").addClass("noScrollSide")
            },
            onClose: function () {
                $("body").removeClass("noScrollSide")
                $(".hamburger").removeClass('is-active');
                $(".footer").css('display', "block");
            },
            draggable: false
        });
        $('.hamburger').click(function () {
            $(this).toggleClass('is-active');
        });
        $('.consoleModal').modal({
            ready: function () { renderConsole() },
            complete: function () { selectModuloAtual() }
        });
        $('.menuLiSelect').click(function (event) {
            event.stopPropagation();
        });
        $('.linkLinha').click(function (e) {
            e.preventDefault()
        });
    })

    window.onpopstate = function (event) {
        event.preventDefault();
        if (paramExist("modulo")) {
            modulo = getParam("modulo")
            m = getLinha(modulo)
            if (m != this.undefined) {
                m.rodar()
            } else {
                f = "invoker_" + modulo
                this.eval(f + "()")
            }
        } else {
            getLinha("inicio").rodar()
        }
    };
}

function alertasTop() {
    if (paramExist("senhaTrocada")) {
        Swal.fire({
            position: "top-end",
            type: "success",
            title: "Senha alterada com sucesso!",
            showConfirmButton: false,
            timer: 2000
        })
        removeParam("senhaTrocada")
    }
    if (paramExist("negado")) {
        Swal.fire({
            position: "top-end",
            type: "error",
            title: "Você não tem permissão para isso!",
            showConfirmButton: false,
            timer: 2000
        })
        removeParam("negado")
    }
}

function mostrarInterface() {
    $("#scriptJson").remove()
    $(".divCarregamento").hide()
    $(".tudo").fadeIn(1200)
    moduloParametro = null
}