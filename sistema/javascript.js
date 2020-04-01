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
    }
    if (jsonDados["tipo"] == "DOC") {
        tipoString = "Docente";
        $("#linha4").hide()
    }
    if (jsonDados["tipo"] == "ADM") {
        tipoString = "Administrador";
        $("#linha3").hide()
        $("#linha4").hide()
    }
    $(".nome").text(jsonDados["nomePreferencia"])
    $(".tipo").text(tipoString)
}

function initMaterialize() {
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
    $(document).ready(function () {
        $('.hamburger').click(function () {
            $(this).toggleClass('is-active');
        });
        $('.consoleModal').modal({
            ready: function () { renderConsole() },
            complete: function () { selectAtual() }
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
}

function popupsParam() {
    $param = get_parametro("senhaTrocada")
    if($param != undefined) {
        Swal.fire({
            position: "top-end",
            type: "success",
            title: "Senha alterada com sucesso!",
            showConfirmButton: false,
            timer: 2000
        })
        removeParamByKey("senhaTrocada")
    }

}

function reload() {
    window.location.href = window.location.href;
}

function finalizarSessao() {
    window.location.href = "./?finalizarSessao";
}