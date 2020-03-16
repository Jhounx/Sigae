class Request {

    constructor() {
        this.parans = []
        this.values = []
    }

    add(param, value) {
        this.parans.push(param);
        this.values.push(value)
    }

    send(requestType, esperado, callback) {
        var dados = {};
        for (var i = 0; i < this.parans.length; i++) {
            dados[this.parans[i]] = this.values[i];
        }
        $.ajax({
            url: "../back-end/request.php",
            type: requestType,
            async: true,
            data: dados,
            dataType: "html"

        }).done(function (resposta) {
            var objeto = new ObjectResposta()  
            if (esperado[0] == "JSON") {
                try {
                    var json = JSON.parse(resposta);
                    objeto.resposta = json;
                } catch (e) {
                    objeto.erro = resposta;
                }
            } else {
                for (var i = 0; i < esperado.length; i++) {
                    if (esperado[i] == resposta) {
                        objeto.resposta = resposta;
                    }
                }
                if (objeto.resposta == null) {
                    objeto.erro = resposta;
                }
            }
            callback(objeto);
        }).fail(function () {
            callback("null request");
        })
    }
}

class ObjectResposta {

    constructor() {
        this.erro = null;
        this.resposta = null
    }
}