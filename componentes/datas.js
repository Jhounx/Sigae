/* Datas */

function dataHoje(string) {
    if (string) {
        return moment().format("DD/MM/YYYY");
    } else {
        return Date.parse(moment());
    }
}

function stringDateToDate(str) {
    return Date.parse(moment(str, "DD/MM/YYYY"));
}

/* Somatórias */

function somarDia(hj, dias, string) {
    if (hj == null) {
        hj = moment().format("DD/MM/YYYY");
    }
    if (string) {
        return moment(hj, "DD-MM-YYYY").add(dias, 'days').format('DD/MM/YYYY');
    } else {
        return moment(hj, "DD-MM-YYYY").add(dias, 'days').toDate()
    }
}

function somarAno(hj, anos, string) {
    if (hj == null) {
        hj = moment().format("DD/MM/YYYY");
    }
    if (string) {
        return moment(hj, "DD-MM-YYYY").add(anos, 'years').format('DD/MM/YYYY');
    } else {
        return moment(hj, "DD-MM-YYYY").add(anos, 'years').toDate()
    }
}

/* Horários */

function diferencaHorario(h1, h2) {
    m1 = moment(h1, "hh:mm")
    m2 = moment(h2, "hh:mm")
    var totalHoras = (m2.diff(m1, 'hours'));
    var totalMinutos = m2.diff(m1, 'minutes');
    var minutos = totalMinutos % 60;
    if(minutos == 0) {
        return totalHoras + " horas"
    } else {
        return totalHoras + " horas e  " + minutos + " minutos"
    }
}

function compararHorario(h1, h2) {
    return moment(h1, "hh:mm").isBefore(moment(h2, "hh:mm"));
}