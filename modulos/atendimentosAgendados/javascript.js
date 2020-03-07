function init_atendimentosAgendados() {
    atendimentoModulo = new Modulo("atendimentoModulo", "../modulos/atendimento", "Atendimento de: ", "schedule", true, true, null);
}

function irAtendimento(id) {
    atendimentoModulo.invoker()
    
}