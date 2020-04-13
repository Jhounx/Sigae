var pagina = 1;

function init_docentes() {
    $(".selectFiltro").selectpicker();
    
    if(get_parametro("pagina") != undefined) {
        pagina = get_parametro("pagina");
    }
    
}

function numeroPaginas() {
    
}

function carregarPagina(pagina) {

}