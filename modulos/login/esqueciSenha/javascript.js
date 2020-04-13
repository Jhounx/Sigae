function init_esqueciSenha() {
    floatingLabel.evaluateInputs();
    email = new Campo("#recuperarEmail", "click", "#botaoEnviarRecuperacao")
    email.img("email")
    $("#recuperarEmail").prop("disabled", false)
    $("#recuperarEmail").on("input", function () {
        if (emailValido($(this).val())) {
            $("#botaoEnviarRecuperacao").removeAttr("disabled")
        } else {
            $("#botaoEnviarRecuperacao").attr("disabled", true)
        }
    });
}

function emailValido(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function enviarEmail() {
    $("#sucessoEmail").hide()
    $("#erroEmail").hide()
    $("#waitingEmail").show()
    $("#botaoEnviarRecuperacao").attr("disabled", true)
    request = new Request()
    request.add("enviarEmailTrocarSenha", "")
    request.add("email", $("#recuperarEmail").val())
    request.send("GET", ["OK", "EML", "INV"], (resultado) => {
        resposta = resultado.resposta;
        erro = resultado.erro;
        if (resposta != null) {
            if (resposta == "OK") {
                $("#sucessoEmail").show()
                $("#erroEmail").hide()
                $("#waitingEmail").hide()
                $("#botaoEnviarRecuperacao").removeAttr("disabled")
            }
            if (resposta == "EML") {
                $("#sucessoEmail").hide()
                $("#erroEmail").show()
                $("#waitingEmail").hide()
                $("#botaoEnviarRecuperacao").removeAttr("disabled")
            }
            if (resposta == "INV") {
                $("#sucessoEmail").hide()
                $("#erroEmail").show()
                $("#waitingEmail").hide()
                $("#botaoEnviarRecuperacao").removeAttr("disabled")
            }
        } else {
            $("#sucessoEmail").hide()
            $("#erroEmail").show()
            $("#waitingEmail").hide()
            $("#botaoEnviarRecuperacao").removeAttr("disabled")
            alert(erro)
        }
    });
}