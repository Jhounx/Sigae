var jsonDados, tipoString;

function init() {
    initMaterialize()
    sideMenus()
    definirVersao()
    initSigae()
}

function initDados(data) {
    jsonDados = JSON.parse(data);
}

function initSigae() {
    if (jsonDados["tipo"] == "ALU") {
        tipoString = "Discente";
        $("#linha3").hide()
        $("#linha6").hide()
    }
    if (jsonDados["tipo"] == "DOC") {
        tipoString = "Docente";
        $("#linha4").hide()
        $("#linha5").hide()
    }
    if (jsonDados["tipo"] == "MON") {
        tipoString = "Monitor";
        $("#linha4").hide()
        $("#linha5").hide()
    }
    if (jsonDados["tipo"] == "ADM") {
        tipoString = "Administrador";
        $("#linha3").hide()
        $("#linha4").hide()
        $("#linha5").hide()
    }
    $(".nome").text(jsonDados["nomePreferencia"])
    $(".tipo").text(tipoString)
    $(window).on('resize', function () {
        $(function () {
            var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
            $("html, body").css({ "height": h });
        });
    });
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
            complete: function () { selectAtual() }
        });
        $('.menuLiSelect').click(function (event) {
            event.stopPropagation();
        });
    });
}

function posInit() {
    $(".linkLinha").click(function (e) {
        e.preventDefault()
    });
    popupsSistema()
    popupsParam()

    window.onpopstate = function (event) {
        event.preventDefault();
        if (paramExist("modulo")) {
            modulo = getParam("modulo")
            if (getLinhaByNome(modulo) != undefined) {
                linha = getLinhaByNome(modulo)
                linha.rodar()
            } else {
                f = "invoker_" + modulo
                this.eval(f + "()")
            }
        } else {
            linha1.rodar()
        }
    };
}

function popupsParam() {
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

function reload() {
    window.location.href = window.location.href;
}

function finalizarSessao() {
    window.location.href = "./?finalizarSessao";
}