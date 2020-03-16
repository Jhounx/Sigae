function init() {
    initMaterialize()
    sideMenus()
    definirVersao()
}

function initMaterialize() {
    $(".side-nav.fixed").css("display", "block");
    $(".button-collapse").sideNav({
        onOpen: function() {
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
    sobre = new Popup("sobre", "../modulos/sobre", "Sobre o SiGAÊ", "500px", "560px");
    sobre.setCss(true)
    sobre.setJS(true)
    sobre.setScroll(true)
    sobre.invoker()
}

function finalizarSessao() {
    //document.cookie = "sessao" + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/;';
    window.location.href = "./?finalizarSessao";
}