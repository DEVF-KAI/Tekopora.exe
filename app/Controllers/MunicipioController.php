<?php
// app/Controllers/MunicipiosController.php

class MunicipiosController {
    public function municipios() {
        $idProvincia = $_GET['prov'] ?? 19; 

        // RECUPERACIÓN DE DATOS: Cargamos el array desde el archivo externo
        $todosLosMunicipios = require __DIR__ . '/../Data/MunicipiosData.php';
        
        // Seleccionamos solo la provincia que necesitamos
        $datosProvincia = $todosLosMunicipios[$idProvincia] ?? null;

        ob_start();
        require __DIR__ . '/../../views/municipios.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../views/layouts/app_layout.php';
    }
}