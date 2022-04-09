<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as ResponseInterface;
use App\Services\User as Service;
use App\Services\Signature;
use App\Helper\Chilkat\Config as Chilkat;
use Illuminate\Support\Facades\File;

class AuthControler extends Controller
{
    use Chilkat;
    public function login(Request $request)
    {
        return ResponseInterface::responseData(
            Service::authenticateuser($request)
        );
    }

    public function signature(Request $request)
    {
        try {

            $java = $this->getJavaKeyStore();
            $jks = $java->key;
            $jksPassword = 's04m4nd1r12021';
            //  Load the Java keystore from a file.  The JKS file password is used
            //  to verify the keyed digest that is found at the very end of the keystore.
            //  It verifies that the keystore has not been modified.
            $success = $jks->LoadFile($jksPassword,storage_path('app/apibmri.dev.jks'));
            if ($success != true) {
                $jks->lastErrorText();

            }

            //  Get the private key from the JKS.
            //  The private key password may be different than the file password.
            $privKeyPassword = 'secret';
            $caseSensitive = false;
            // privKey is a CkPrivateKey
            $privKey = $jks->FindPrivateKey($privKeyPassword,'some.alias',$caseSensitive);
            if ($jks->get_LastMethodSuccess() != true) {
                throw new \Exception($jks->lastErrorText());
            }

            //  Establish the RSA object and tell it to use the private key..
            $rsa = $java->rsa;

            $success = $rsa->ImportPrivateKeyObj($privKey);

            if ($success != true) {
                print $rsa->lastErrorText() . "\n";
                exit;
            }

            //  Indicate we'll be signing the utf-8 byte representation of the string..
            $rsa->put_Charset('utf-8');

            //  Sign some plaintext using RSA-SHA256
            $binarySignature = $java->binary;
            $plaintext = 'this is the text to be signed';
            $success = $rsa->SignString($plaintext,'SHA256',$binarySignature);
            if ($rsa->get_LastMethodSuccess() != true) {
                print $rsa->lastErrorText() . "\n";
                exit;
            }

            //  Alternatively, if the signature is desired in some encoded string form,
            //  such as base64, base64-url, hex, etc.
            $rsa->put_EncodingMode('base64-url');
            $signatureStr = $rsa->signStringENC($plaintext,'SHA256');
            return ResponseInterface::responseData(
                // [
                //     'items' => $encStr,
                //     'attributes' => ''
                // ]
            );

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
