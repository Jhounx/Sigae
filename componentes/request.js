class Request {

    constructor() {
        this.parans = []
        this.values = []
        this.url = "../back-end/request.php";
    }

    add(param, value) {
        this.parans.push(param);
        this.values.push(value)
    }

    setURL(value) {
        this.url = value;
    }

    abort() {
        if(this.ajax != undefined) {
            this.ajax.abort();
        }
    }

    send(requestType, esperado, callback) {
        var dados = {};
        for (var i = 0; i < this.parans.length; i++) {
            dados[this.parans[i]] = this.values[i];
        }
        this.ajax = $.ajax({
            url: this.url,
            type: requestType,
            async: true,
            data: dados,
            dataType: "html"

        }).done(function (resposta) {
            var objeto = new ObjectResposta()  
            if(resposta == "NEGADO") {
                window.location.href="../?expirado=true";
                return;
            } else {
                if (esperado[0] == "JSON") {
                    try {
                        var json = JSON.parse(resposta);
                        objeto.resposta = json;
                    } catch (e) {
                        objeto.erro = resposta;
                    }
                } else {
                    if(esperado[0] == "INTEGER") {
                        if(!isNaN(resposta)) {
                            objeto.resposta = resposta;
                        } else {
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
                }
                
            }
            callback(objeto);
        }).fail(function (request) {
            if(request.statusText != "error" && request.statusText != "abort") {
                alert(request.statusText )
                callback("null request");
            }
        })
    }
}

class ObjectResposta {

    constructor() {
        this.erro = null;
        this.resposta = null
    }
}

function universalAbort() {
    if (typeof request !== 'undefined' && request != undefined) {
        request.abort()
    }
}