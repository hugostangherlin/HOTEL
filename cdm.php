<?php
$diretorio = '../../../relatorios/usuario';

if (is_writable($diretorio)) {
    echo "A pasta {$diretorio} tem permissão de escrita.";
} else {
    echo "A pasta {$diretorio} NÃO tem permissão de escrita.";
}
?>
