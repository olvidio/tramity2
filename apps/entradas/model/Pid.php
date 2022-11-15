<?php

namespace entradas\model;

use core\ConfigGlobal;
use web\DateTimeLocal;

class Pid
{
    private $filename;

    public function __construct()
    {
        // Si he lanzado el proceso automáticamente, escribo el id del proceso.
        // si ya existe un proceso en marcha, salgo del proceso.
        $this->filename = ConfigGlobal::$directorio . "/log/descargas.pid";

    }

    public function existePid(): bool
    {
        if (file_exists($this->filename)) {
            $fileContent = file_get_contents($this->filename);
            if (!empty($fileContent)) {
                // Comprobar que no sea por que el anterior ha dado un error y
                // no se ha borrado. Miramos que sea de hace más de 5 min.
                $delta = 5;
                $matches = [];
                preg_match('@(\d+/\d+/\d+ \d+:\d+:\d+) -- .*@', $fileContent, $matches);
                $f_iso = $matches[1];

                $oDiaFichero = new DateTimeLocal($f_iso);
                $oAhora = new DateTimeLocal('now');

                $interval = $oDiaFichero->diff($oAhora);
                $a = $interval->format('%i');

                if ($a > $delta) {
                    $ahora = date("Y/m/d H:i:s");
                    echo "$ahora ";
                    echo sprintf(_("El fichero %s no está vacío."), $this->filename);
                    echo " ";
                    echo _("Posiblemente la anterior operación finalizó con error");
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function crearPid()
    {
        if (!$this->existePid()) {
            $ahora = date("Y/m/d H:i:s");
            $mensaje = "$ahora \n";
            file_put_contents($this->filename, $mensaje, LOCK_EX);
        }
    }

    public function borrarPid()
    {
        // al finalizar borro el pid
        // Si he lanzado el proceso automáticamente.
        // Borro el pid, para que empiece el siguiente proceso.
        // Hay que asegurarse que se han acabado de escribir todos los anotados, para que no los vuelva a escribir.
        // Por esto espero 7 segundos (con 3 no basta...)

        if (file_exists($this->filename)) {
            $mensaje = "";
            file_put_contents($this->filename, $mensaje, LOCK_EX);
        }
    }

}