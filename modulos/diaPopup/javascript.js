function init_diaPopup() {
    $(".tituloPopup").text("Dia: " + diaP + "/" + mesP + "/" + anoP)
    $(".divAtendimentos").collapsible();
    renderAtendimentos()
}

function renderAtendimentos() {
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")
    render("14:20", "Ana Carolina Sokolonski Anton", "D-108", "Informática", "Atendimento", "NÃO CONFIRMADO")
    render("16:00", "Solange Moura", "D-202", "Desenho Técnico", "Atendimento", "CONFIRMADO")
    render("18:40", "Diogo Monitor", "O-110", "Matemática", "Monitoria", "CONFIRMADO")

}

function render(horario, docente, sala, disciplina, tipo, estado) {
    $(".divAtendimentos").append("\
    <li>\
        <div class=\"collapsible-header divHeaderAtendimentos\">\
            <div class=\"paiDiaPopup\">\
                <div class=\"divMaterial\"><i class=\"material-icons icone-popupDia\">keyboard_arrow_right</i></div>\
                <div>\
                    <div>"+ horario +"</div>\
                    <div>"+ disciplina +"</div>\
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
}