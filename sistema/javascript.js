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
            $("html, body").css({"height": h });
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

    nomeModulo = "inicio"
    if (window.location.search != "") {
        window.history.pushState({ pagina: 0, valor: nomeModulo, url: window.location.search }, nomeModulo, "");
        window.history.replaceState({ pagina: 0, valor: nomeModulo, url: window.location.search }, nomeModulo, "");
    }
}

function posInit() {
    $(".linkLinha").click(function (e) {
        e.preventDefault()
    });
    popupsSistema()
    popupsParam()

    window.onpopstate = function (event) {
        event.preventDefault();
        if (window.history.state) {
            valor = window.history.state.value
            if(valor != undefined && valor.startsWith("--")) {
                arr = valor.substring(2)
                if (moduloAtual.id != arr) {
                    linha = getLinhaByNome(arr)
                    if(linha != undefined) {
                        linha.rodar(true)
                    }
                }
            }
        } else {
            this.removeAllParans()
            linha1.rodar()
        }
    };
}

function popupsParam() {
    if(paramExist("senhaTrocada")) {
        parametro = getParam("senhaTrocada")
        Swal.fire({
            position: "top-end",
            type: "success",
            title: "Senha alterada com sucesso!",
            showConfirmButton: false,
            timer: 2000
        })
        removeParam("senhaTrocada")
    }

}

function reload() {
    window.location.href = window.location.href;
}

function finalizarSessao() {
    window.location.href = "./?finalizarSessao";
}