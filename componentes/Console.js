arrayConsole = []

function abrirConsole() {
    $(".consoleModal").modal("open");
}

function fecharConsole() {
    $(".consoleModal").modal("close");
}

function renderConsole() {
    $(".conteudoConsole").empty()
    for (var i = 0; i < arrayConsole.length; i++) {
        $(".conteudoConsole").append("<div class=\"consoleContent\">" + arrayConsole[i] + "</div>")
    }
}

function acionarErro(texto) {
    var toast = $("<i class=\"material-icons toast-erro\">cancel</i><span class=\"toastTexto\">" + texto + "</span>").add($('<button onclick="abrirConsole()" class="btn-flat toast-action">Abrir console</button>'));
    Materialize.toast(toast, 4000);
    var date = new Date();
    var textoFinal = "<div class=\"conteudoConsoleHorario console-erro\">[Erro] " + date.getHours() + ":" + date.getMinutes() + "</div>&nbsp; - " + texto;
    arrayConsole.push(textoFinal)
    $(".noAlertas").hide()
}

function acionarAlerta(texto) {
    var toast = $("<i class=\"material-icons toast-erro\">add_alert</i><span class=\"toastTexto\">" + texto + "</span>").add($('<button onclick="abrirConsole()" class="btn-flat toast-action">Abrir console</button>'));
    Materialize.toast(toast, 4000);
    var date = new Date();
    var textoFinal = "<div class=\"conteudoConsoleHorario\">[Alerta] " + date.getHours() + ":" + date.getMinutes() + "</div>&nbsp; - " + texto;
    arrayConsole.push(textoFinal)
    $(".noAlertas").hide()
}

function limparConsole() {
    $(".conteudoConsole").empty()
    arrayConsole = []
    $(".noAlertas").show()
}