function popupsSistema() {
    sobre = new Popup("sobre", "../modulos/sobre", "Sobre o SiGAÊ", "500px");
    sobre.setCss(true)
    sobre.setJS(true)
    sobre.setScroll(true)
    sobre.invoker()
}

function mudarDadosShow() {
    mudarDados.show()
}

function alterarSenhaShow() {
    $mandou = false;
    Swal.fire({
        title: "Deseja continuar?",
        html: '<div style="text-align:center">Podemos enviar um e-mail para que <br>possamos continuar a alteração da senha?</div>',
        type: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
        allowOutsideClick: false,
        preConfirm: function () {
            return new Promise(function () {
                if ($mandou == false) {
                    Swal.getCancelButton().style.display = "none";
                    Swal.showLoading()
                    $(".swal2-title").text("Enviando email...")
                    $(".swal2-content > div").hide()

                    request = new Request()
                    request.add("emailTrocarSenha", "")
                    request.add("email", getEmail())
                    request.setURL("../back-end/email/requestEmail.php")
                    request.send("GET", ["OK", "EML", "INV"], (resultado) => {
                        resposta = resultado.resposta;
                        erro = resultado.erro;
                        Swal.hideLoading();
                        Swal.getCancelButton().style.display = "block";
                        if (resposta != null) {
                            if (resposta == "OK") {
                                $(".swal2-title").text("O email foi enviado!")
                                $(".swal2-cancel").hide()
                                $(".swal2-confirm").text("Fechar")
                                $mandou = true
                            }
                            if (resposta == "EML" || resposta == "INV") {
                                Swal.showValidationMessage("Não foi possível enviar o email")
                            }
                        } else {
                            Swal.showValidationMessage("Erro grave")
                            alert(erro)
                        }
                    });
                } else {
                    Swal.close()
                }

            });
        }
    })
}