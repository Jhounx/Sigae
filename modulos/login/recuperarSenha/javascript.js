function init_recuperarSenha() {
    eventosDigitar()
}

function ganhouFocus(a) {
    if(a == 1) {
        $(".popupShow1").show()
        $(".popupShow2").hide()
    }
    if(a == 2) {
        if(!senhaIguais()) {
            $(".popupShow2").show()
            $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
    }
}

function perdeuFocus(a) {
    if(a == 1) {
        $(".popupShow1").fadeOut(250)
    }
}

function eventosDigitar() {
    $("#senha1").on("input", function () {
        nota = notaDaSenha($("#senha1").val())
        definirNota(nota)
        senhaValida(nota)
        if(senhaIguais() && senhaValida(nota)) {
            $("#botaoFinalizar").removeAttr("disabled")
        } else {
            $("#botaoFinalizar").attr("disabled", true)
        }
    });
    $("#senha2").on("input", function () {
        nota = notaDaSenha($("#senha1").val())
        if(senhaIguais()) {
            $(".popupShow2").hide()
            $("#senha2").removeAttr("style")
        } else {
            $(".popupShow2").show()
            $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
        if(senhaIguais() && senhaValida(nota)) {
            $("#botaoFinalizar").removeAttr("disabled")
        } else {
            $("#botaoFinalizar").attr("disabled", true)
        }
    });
}

function definirNota(nota) {
    if(nota == 0) {
        $("#força").text("Inválida")
        $("#força").css("cssText", "color:crismon");
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
    }
    if(nota == 20) {
        $("#força").text("Ridícula")
        $("#força").css("cssText", "color:crismon");
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
    }
    if(nota == 50) {
        $("#força").text("Fraca")
        $("#força").css("cssText", "color:rgb(220, 167, 20)");
        $("#senha1").removeAttr("style");
    }
    if(nota == 75) {
        $("#força").text("Forte")
        $("#força").css("cssText", "color:rgb(20, 220, 110)");
        $("#senha1").removeAttr("style");
    }
    if(nota == 100) {
        $("#força").text("Muito forte")
        $("#força").css("cssText", "color:forestgreen");
        $("#senha1").removeAttr("style");
    }
    $(".determinate").css("width", nota + "%")
}

function senhaValida(nota) {
    if(nota >= 50 ) {
        return true;
    } else {
        return false;
    }
}

function senhaIguais() {
    if($("#senha1").val() == $("#senha2").val()) {
        return true;
    } else {
        return false;
    }
}

/* AJAX */
function enviarSenha() {

    $("#botaoFinalizar").addClass("disabled")
    request = new Request()
    request.add("trocarSenha", "")
    request.add("codigo", codigo)
    request.add("senha", $("#senha1").val())
    request.setURL("../../back-end/request.php")
    request.send("GET", ["OK"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if (resposta == "OK") {
                window.location.href = "../../../?senhaTrocada=true";
            }
            if (resposta == "INV") {
                dispararErro("Erro na requisição: ID inválido")
                $("#botaoFinalizar").removeClass("disabled")
            }
        } else {
            dispararErro("Erro interno no sistema. Requisição negada")
            $("#botaoFinalizar").removeClass("disabled")
            alert(erro)
        }
    });
}

function dispararErro(texto) {
    var toast = $("<i class=\"material-icons toast-erro\">cancel</i><span class=\"toastTexto\">" + texto + "</span>");
    Materialize.toast(toast, 5000);
}