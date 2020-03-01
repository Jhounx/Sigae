var arrayModulos = [], moduloAtual;

class Modulo {

    id; pasta; titulo; icone; css; js; linha;

    constructor(id, pasta, titulo, icone, css, js, linha) {
        this.id = id;
        this.pasta = pasta;
        this.titulo = titulo;
        this.icone = icone;
        this.css = css;
        this.js = js;
        this.linha = linha;
        arrayModulos.push(this)
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
            $("modulo").remove();
            $("#carregamentoModulo").hide();
            $(".breadcrumbs").show();
            $(".breadcrumbsTitulo").text(classe.titulo)
            $(".breadcrumbs-icone").text(classe.icone)
            $(".breadcrumbs").attr("data-tooltip", classe.titulo);
            $(".breadcrumbs-tooltipped").tooltip({ delay: 50 });
            $(".content").append(data)
            moduloAtual = classe;
            if (classe.js == true) {
                if (eval("typeof init_" + classe.id) == "function") {
                    window["init_" + classe.id]()
                } else {
                    erro("A função " + "'init_" + classe.id + "' não existe")
                }
            }
        }, 500);

    }
}