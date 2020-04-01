<?php
function logar($login, $senha) {
    include('./main.php');
    $senha_md5 = md5($senha);
    $queryString = "
    select id, matricula, senha, estado from alunos
    where matricula='$login'
    union
    select id, matricula, senha, estado from docentes
    where matricula= '$login'
    union
    select id, matricula, senha, estado from admins
    where matricula= '$login' limit 1
    ";
    $query = mysqli_query($conn, $queryString);
    $array = mysqli_fetch_assoc($query);
    $numeroLinha = mysqli_num_rows($query);
    if ($numeroLinha == 1) {
        if ($senha_md5 == $array['senha']) {
            if ($array['estado'] == 'INA') {
                return 'INA';
            }
            if ($array['estado'] == 'REG') {
                return 'REG';
            }
            if ($array['estado'] == 'ATV') {
                session_name('sessao');
                session_set_cookie_params(3600 * 24);
                session_start();
                $_SESSION['permissaoSistema'] = $array['id'];
                $_SESSION['tipo'] = 'ALUNO';

                return 'CON';
            }
        } else {
            return 'SEN';
        }
    } else {
        return 'MAT';
    }
}

function logout() {
    session_start();
    session_destroy();
    session_write_close();
}
