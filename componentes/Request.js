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

    noRedirect() {
        this.noRedirect = true
    }

    abort() {
        if (this.ajax != undefined) {
            this.ajax.abort();
        }
    }

    send(requestType, esperado, tudoOK, returnErr) {
        var dados = {};
        for (var i = 0; i < this.parans.length; i++) {
            dados[this.parans[i]] = this.values[i];
        }
        var classe = this
        this.ajax = $.ajax({
            url: this.url,
            type: requestType,
            async: true,
            data: dados,
            dataType: "html"

        }).done(function (resposta) {
            if (classe.noRedirect == undefined) {
                if (resposta == "NEG") {
                    window.location.href = "../login?negado";
                    return;
                }
                if (resposta == "EXPIRADO") {
                    window.location.href = "../login?expirado";
                    return;
                }
            }
            var retorno = false;
            for (var i = 0; i < esperado.length; i++) {
                var es = esperado[i]
                if (es == 'JSON' && isJson(resposta)) {
                    retorno = true;
                    tudoOK(JSON.parse(fixJson(resposta)))
                } else if (es == 'INTEGER' && !isNaN(resposta)) {
                    retorno = true;
                    tudoOK(resposta)

                } else if (es == resposta) {
                    retorno = true;
                    tudoOK(resposta)
                }
            }
            if (retorno == false) {
                if (typeof returnErr === 'function') {
                    returnErr(resposta)
                }
            }
        }).fail(function (request) {
            if (request.statusText != "error" && request.statusText != "abort") {
                alert(request.statusText)
                callback("null request");
            }
        })
    }
}

function universalAbort() {
    if (typeof request !== 'undefined' && request != undefined) {
        request.abort()
    }
}