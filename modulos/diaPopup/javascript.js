var nodeID;

function init_diaPopup() {
    $(".tituloPopup").text("Dia: " + diaP + "/" + mesP + "/" + anoP)
    $(".divAtendimentos").collapsible();
    renderAtendimentos()
}

function renderAtendimentos() {
    $(".divAtendimentos").empty()
    nodeID = 0;
    new NodeAtendimento("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    new NodeAtendimento("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
}

class NodeAtendimento {

    constructor(horario, docente, sala, disciplina, tipo, estado) {
        nodeID++;
        this.id = nodeID;
        this.aberto = false;
        $(".divAtendimentos").append("\
        <li>\
            <div id=\"nodeAtendimento-" + nodeID + "\" class=\"collapsible-header divHeaderAtendimentos\">\
                <div class=\"paiDiaPopup\">\
                    <div class=\"divMaterial\"><i id=\"icone-atendimento-" + nodeID + "\" class=\"material-icons icone-popupDia\">keyboard_arrow_right</i></div>\
                    <div>\
                        <div>"+ horario + "</div>\
                        <div>"+ disciplina + "</div>\
                    </div>\
                </div>\
            </div>\
                <div class=\"collapsible-body\">\
                    <div class=\"textoAtendimento\">Horário: <div class=\"respostasAtendimento\">"+ horario + "</div></div>\
                    <div class=\"textoAtendimento\">Docente: <div class=\"respostasAtendimento\">"+ docente + "</div></div>\
                    <div class=\"textoAtendimento\">Sala: <div class=\"respostasAtendimento\">"+ sala + "</div></div>\
                    <div class=\"textoAtendimento\">Disciplina: <div class=\"respostasAtendimento\">"+ disciplina + "</div></div>\
                    <div class=\"textoAtendimento\">Tipo: <div class=\"respostasAtendimento\">"+ tipo + "</div></div>\
                    <div class=\"textoAtendimento\">Estado: <div class=\"respostasAtendimento\">"+ estado + "</div></div>\
                    <hr>\
                    <div class=\"row justify-content-center\">\
                        <a class=\"waves-effect waves-light btn\">Se inscrever</a>\
                    </div>\
                </div>\
            </div>\
        </li>")
        var classe = this
        $("#nodeAtendimento-" + nodeID).click(function () {
            // alert("clicocu")
            classe.toggle()
        });
    }

    toggle() {
        if (this.aberto == false) {
            this.aberto = true;
            this.open()
        } else {
            this.aberto = false;
            this.close()
        }
    }

    open() {
        $("#icone-atendimento-" + this.id).css("transform", "rotateZ(90deg)");
    }

    close() {
        $("#icone-atendimento-" + this.id).css("transform", "rotateZ(0deg)");
    }
}