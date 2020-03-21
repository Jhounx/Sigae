function init_recuperarSenha() {
}

function validarSenha1() {
    $("#erro1").text("")
    $("#senha1").removeAttr("style");
    compararSenhas()
    if ($("#senha1").val().length < 6) {
        $("#erro1").text("A senha deve conter mais de 6 caracteres")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    if ($("#senha1").val().length > 30) {
        $("#erro1").text("A senha deve conter menos de 30 caracteres")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    return false;
}

function validarSenha2() {
    $("#senha2").removeAttr("style");
    $("#erro2").text("")
    compararSenhas()
    if ($("#senha2").val().length < 6) {
        $("#erro2").text("A senha deve conter mais de 6 caracteres")
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    if ($("#senha2").val().length > 30) {
        $("#erro2").text("A senha deve conter menos de 30 caracteres")
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    return false;
}

function compararSenhas() {
    if ($("#senha1").val().length > 0) {
        $("#senha1").removeAttr("style");
        $("#erro1").text("")
    }
    if ($("#senha2").val().length > 0) {
        $("#senha2").removeAttr("style");
        $("#erro2").text("")
    }
    if ($("#senha1").val() != $("#senha2").val()) {
        $("#erro1").text("Ambas as senhas devem ser iguais")
        $("#erro2").text("Ambas as senhas devem ser iguais")
        $("#senha1").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        $("#senha2").css("cssText", "border-bottom: 1px solid #F44336;-webkit-box-shadow: 0 1px 0 0 #F44336;box-shadow: 0 1px 0 0 #F44336;");
        return true;
    }
    return false;
}

function perdeuFocus(comp) {
    if (comp == "#senha1") {
        validarSenha1()
    }
    if (comp == "#senha2") {
        validarSenha2()
    }
}

function verificarSenha() {
    if (validarSenha1() == false & validarSenha2() == false) {
        alert("pronto")
    }
}

/* AJAX */

function verificarSenha() {

    $("#botaoFinalizar").addClass("disabled")
    request = new Request()
    request.add("trocarSenha", "")
    request.add("codigo", id)
    request.add("senha1", $("#senha1").val())
    request.add("senha2", $("#senha2").val())
    request.send("GET", ["OK"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if (resposta == "OK") {
                //window.location.href = "./?id=" + jsonDados["key"];
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