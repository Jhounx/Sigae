class Request {

    constructor() {
        this.parans = []
        this.values = []
    }

    add(param, value) {
        this.parans.push(param);
        this.values.push(value)
    }

    send(type, funcao) {
        var dados = {};
        for (var i = 0; i < this.parans.length; i++) {
            dados[this.parans[i]] = this.values[i];
        }
        $.ajax({
            url: "./back-end/request.php",
            type: type,
            async: true,
            data: dados,
            dataType: "html"

        }).done(function (resposta) {
            window ["logarResposta"](resposta);
        }).fail(function () {
            window [funcao]("-1");
        })
    }
}