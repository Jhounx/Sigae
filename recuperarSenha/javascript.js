function init() {
    $("#senha1").on("input", function () {
        if ($("#senha1").val().length > 0) {
            nota = notaDaSenha($("#senha1").val())
            definirNota(nota)
            senhaValida(nota)
            if (senhaIguais() && senhaValida(nota)) {
                $("#botaoFinalizar").removeAttr("disabled")
            } else {
                $("#botaoFinalizar").attr("disabled", true)
            }
        } else {
            $("#senha1").removeAttr("style")
        }
    });
    $("#senha2").on("input", function () {
        nota = notaDaSenha($("#senha1").val())
        if (senhaIguais()) {
            $(".popupShow2").hide()
            $("#senha2").removeAttr("style")
        } else {
            $(".popupShow2").show()
            $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
        if (senhaIguais() && senhaValida(nota)) {
            $("#botaoFinalizar").removeAttr("disabled")
        } else {
            $("#botaoFinalizar").attr("disabled", true)
        }
    });
}

function ganhouFocus(a) {
    if (a == 1) {
        $(".popupShow1").show()
        $(".popupShow2").hide()
    }
    if (a == 2) {
        if (!senhaIguais()) {
            $(".popupShow2").show()
            $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        }
    }
}

function perdeuFocus(a) {
    if (a == 1) $(".popupShow1").fadeOut(250)
}

function definirNota(nota) {
    if (nota == 0) {
        $("#força").text("Inválida")
        $("#força").css("cssText", "color:crismon");
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
    }
    if (nota == 20) {
        $("#força").text("Ridícula")
        $("#força").css("cssText", "color:crismon");
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
    }
    if (nota == 50) {
        $("#força").text("Fraca")
        $("#força").css("cssText", "color:rgb(220, 167, 20)");
        $("#senha1").removeAttr("style");
    }
    if (nota == 75) {
        $("#força").text("Forte")
        $("#força").css("cssText", "color:rgb(20, 220, 110)");
        $("#senha1").removeAttr("style");
    }
    if (nota == 100) {
        $("#força").text("Muito forte")
        $("#força").css("cssText", "color:forestgreen");
        $("#senha1").removeAttr("style");
    }
    $(".determinate").css("width", nota + "%")
}

function senhaValida(nota) {
    return (nota >= 50)
}

function senhaIguais() {
    return ($("#senha1").val() == $("#senha2").val())
}

/* AJAX */
function enviarSenha() {
    $("#botaoFinalizar").addClass("disabled")
    request = new Request()
    request.noRedirect()
    request.add("trocarSenha", "")
    request.add("codigo", getParam("codigo"))
    request.add("senha", $("#senha1").val())
    request.send("GET", ["OK", "INV"], (resposta) => {
        if (resposta == "OK") {
            window.location.href = "../login?senhaTrocada";
        }
        if (resposta == "INV") {
            dispararErro("Erro na requisição: ID inválido")
            $("#botaoFinalizar").removeClass("disabled")
        }
    }, (erro) => {
        dispararErro("Erro interno no sistema. Requisição negada")
        $("#botaoFinalizar").removeClass("disabled")
        alert(erro)
    });
}