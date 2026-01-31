<?php
// utils_pdf.php

function php_call_python_generate($data_array, $python_path = 'python') {
    // $data_array => array com os dados que o script python espera
    $tmp = sys_get_temp_dir();
    $jsonFile = tempnam($tmp, 'pdf_');
    // grava JSON com unicode sem escapar
    file_put_contents($jsonFile, json_encode($data_array, JSON_UNESCAPED_UNICODE));

    // caminho para o script python (ajuste se for diferente)
    $script = __DIR__ . DIRECTORY_SEPARATOR . 'pdf_generator' . DIRECTORY_SEPARATOR . 'generate_pdf.py';
    // monta comando de forma segura
    $cmd = escapeshellcmd($python_path) . ' ' . escapeshellarg($script) . ' ' . escapeshellarg($jsonFile);

    // executa
    $output = array();
    $ret = 1;
    exec($cmd . ' 2>&1', $output, $ret); // redireciona stderr para stdout para debug

    // remove arquivo JSON temporário
    @unlink($jsonFile);

    if ($ret !== 0) {
        // retorna array com erro para depuração
        return array('success' => false, 'error_output' => $output, 'ret' => $ret);
    }

    // sucesso: primeira linha do output é o caminho absoluto do PDF
    $pdfpath = isset($output[0]) ? $output[0] : null;
    return array('success' => true, 'path' => $pdfpath);
}
