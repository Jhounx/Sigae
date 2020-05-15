function popupsSistema() {
    /*sobre = new Popup("sobre", "../modulos/sobre", "Sobre o SiGAÊ", "500px");
    sobre.setCss(true)
    sobre.setJS(true)
    sobre.setScroll(true)
    sobre.invoker()

    usuario = new Popup("usuario", "../modulos/usuario", "", "520px", "560px");
    usuario.setCss(true)
    usuario.setJS(true)
    usuario.invoker()*/
}

function mudarDadosShow() {
    getModulo("mudarDados").show()
}

function alterarSenhaShow() {
    mandou = false;
    Swal.fire({
        title: "Deseja continuar?",
        html: '<div style="text-align:center">Podemos enviar um e-mail para que <br>possamos continuar a alteração da senha?<br><br>Seu Email: <b>' + getEmail() + '</b></div>',
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        allowOutsideClick: false,
        preConfirm: function () {
            return new Promise(function () {
                if (mandou == false) {
                    Swal.getCancelButton().style.display = "none";
                    Swal.showLoading()
                    $(".swal2-title").text("Enviando email...")
                    $(".swal2-content > div").hide()

                    request = new Request()
                    request.add("enviarEmailTrocarSenha", "")
                    request.add("email", getEmail())
                    request.send("GET", ["OK", "EML", "INV"], (resposta) => {
                        Swal.hideLoading();
                        Swal.getCancelButton().style.display = "block";
                        if (resposta == "OK") {
                            $(".swal2-title").text("O email foi enviado!")
                            $(".swal2-cancel").hide()
                            $(".swal2-confirm").text("Fechar")
                            mandou = true
                        }
                        if (resposta == "EML" || resposta == "INV") {
                            Swal.showValidationMessage("Não foi possível enviar o email")
                        }
                    }, (erro) => {
                        Swal.showValidationMessage("Erro grave")
                        alert(erro)
                    });
                } else {
                    Swal.close()
                }

            });
        }
    })
}

function sobreSigae() {
    sobre.show()
}

/* Modulos separados */

function usuarioShow(id) {
    usuario.show()
    ajaxUsuario(id)
}

function invoker_agendarAtendimento() {
    setParam("modulo", "agendarAtendimento", true)
    getModulo("agendarAtendimento").invoker()
}

function invoker_atendimento(id) {
    if (id == undefined && !paramExist("id")) {
        acionarErro("Nenhum ID foi declarado")
        irInicio()
    } else {
        if (paramExist("id")) {
            id = getParam("id")
        } else {
            setParam("modulo", "atendimento&id=" + id, true)
        }
        getModulo("atendimento").invoker()
    }
}