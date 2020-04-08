var arrayModulos = [], moduloAtual;

class Modulo {

    constructor(id, pasta, titulo, icone, css, js, linha) {
        this.id = id;
        this.pasta = pasta;
        this.titulo = titulo;
        this.icone = icone;
        this.css = css;
        this.js = js;
        this.linha = linha;
        this.isWait = false;
        arrayModulos.push(this)
    }

    wait() {
        this.isWait = true;
    }

    invoker() {

        $("modulo").remove();
        $(".content-head").empty()
        $(".breadcrumbs").hide();
        $("#carregamentoModulo").show();
        closeSide()

        var classe = this;
        $.ajax({
            url: this.pasta + "/index.html",
            type: 'get',
            dataType: 'html',
            async: true,
            success: function (data) {
                classe.render(data)
            }
        });
    }

    render(data) {
        var classe = this;
        if (this.css == true) {
            $(".content-head").append("<link rel=\"stylesheet\" href=\" " + this.pasta + "/css.css\">");
        }
        if (this.js == true) {
            $(".content-head").append("<script src=\" " + this.pasta + "/javascript.js\"></script>");
        }

        if (eval("typeof pre_init_" + classe.id) == "function") {
            window["pre_init_" + classe.id]()
        }

        setTimeout(function () {
            $(".headerPopup").empty();
            $("modulo").remove();
            $(".breadcrumbsTitulo").text(classe.titulo)
            $(".breadcrumbs-icone").text(classe.icone)
            $(".breadcrumbs").attr("data-tooltip", classe.titulo);
            $(".breadcrumbs-tooltipped").tooltip({ delay: 50 });
            $(".content").append(data)
            if(classe.isWait == true) {
                $("modulo").hide()
            } else {
                $("#carregamentoModulo").hide();
                $(".breadcrumbs").show();
            }
            moduloAtual = classe;
            if (classe.js == true) {
                if (eval("typeof init_" + classe.id) == "function") {
                    window["init_" + classe.id]()
                } else {
                    acionarErro("A função " + "'init_" + classe.id + "' não existe")
                }
            }
        }, 500);
    }

    show() {
        $("#carregamentoModulo").hide();
        $(".breadcrumbs").show();
        $("modulo").show()
    }
}

window.onpopstate = function (event) {
    event.preventDefault();
    if (window.history.state) {

        if (moduloAtual.id != window.history.state.valor) {
            linha = getLinhaByNome(window.history.state.valor)
            linha.rodar()
        }

    } else {
        linha1.rodar()
    }
};