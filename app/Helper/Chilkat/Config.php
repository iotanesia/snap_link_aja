<?php

namespace App\Helper\Chilkat;

use CkCrypt2;

include('chilkat_9_5_0.php');

trait Config {

    protected $crypt;

    public function __construct(CkCrypt2 $crypt){
        $this->crypt = $crypt;
    }

    public function getCkCrypt2()
    {
        return $this->crypt;
    }

}
