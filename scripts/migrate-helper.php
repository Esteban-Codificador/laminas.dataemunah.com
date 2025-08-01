<?php
/**
 * Script helper para facilitar migración
 */

// Funciones para convertir naming conventions
function zf1ToLaminasControllerName($zf1Name) {
    return ucfirst($zf1Name) . 'Controller';
}

function zf1ToLaminasActionName($zf1Action) {
    return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $zf1Action)))) . 'Action';
}

// Más helpers según necesites...