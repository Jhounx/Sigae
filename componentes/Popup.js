class Popup {

    // name; file; largura; altura; titulo; css; js; scroll; html;

    constructor(name, file, titulo, largura, altura) {
        this.name = name;
        this.file = file;
        this.titulo = titulo;
        this.largura = largura;
        this.altura = altura;
    }

    setCss(boolean) {
        this.css = boolean;
    }

    setJS(boolean) {
        this.js = boolean;
    }

    setScroll(boolean) {
        this.scroll = boolean;
    }

    setTitle(titulo) {
        this.titulo = titulo
    }

    invoker() {
        var classe = this;
        $.ajax({
            url: this.file + "/index.html",
            type: 'get',
            dataType: 'html',
            async: false,
            success: function (data) {
                classe.html = data;
            }
        });
        if (this.css == true) {
            $("body").append("<link rel=\"stylesheet\" href=\" " + this.file + "/css.css\">");
        }
        if (this.js == true) {
            $("body").append("<script src=\" " + this.file + "/javascript.js\"></script>");
        }
    }

    show() {
        var classe = this, alturaScroll;
        alturaScroll = $('body').scrollTop();
        Swal.fire({
            title: '<img src="../icones/sigae.svg" width="150" height="91"><br><h1 class="tituloPopup" style="font-size: 18px">' + this.titulo + '</h1>',
            animation: false,
            cancelButtonText: 'Fechar',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            allowEnterKey: false,
            onOpen: function() {
                $("body").scrollTop(alturaScroll);
                if (classe.js == true) {
                    window["init_" + classe.name]()
                }
            },
            onClose: function() {
                $("body").animate({ scrollTop: alturaScroll }, 100);
                try {
                    selectAtual()
                    window["close_" + classe.name]()
                } catch(ex) {}
            },
            html: `${classe.html}`
        })
        if(this.altura != null) {
            $(".swal2-popup").css("cssText", "height: " + this.altura + " !important; width: " + this.largura + " !important; display:flex");
        } else {
            $(".swal2-popup").css("cssText", "width: " + this.largura + " !important; display:flex");
        }
        if (this.scroll == true) {
            $(".swal2-content").css("cssText", "overflow-y: auto; !important");
        } else {
            $(".swal2-content").css("cssText", "overflow-y: hidden");
        }
    }
}