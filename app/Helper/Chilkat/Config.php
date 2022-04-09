<?php

namespace App\Helper\Chilkat;

use CkByteData;
use CkCrypt2;
use CkJavaKeyStore;
use CkRsa;

include('chilkat_9_5_0.php');

trait Config {

    protected $crypt;
    protected $key;
    protected $rsa;
    protected $binary;

    public function __construct(CkCrypt2 $crypt,CkJavaKeyStore $key,CkRsa $rsa,CkByteData $binary){
        $this->crypt = $crypt;
        $this->key = $key;
        $this->rsa = $rsa;
        $this->binary = $binary;
    }

    public function getCkCrypt2()
    {
        return $this->crypt;
    }

    public function getJavaKeyStore()
    {
        $attr = new \stdClass;
        $attr->key = $this->key;
        $attr->rsa = $this->rsa;
        $attr->binary = $this->binary;
        return $attr;
    }

}
