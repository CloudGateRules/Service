<?php

class Auth
{

    /**
     * 生成RSA加密的AuthKey信息
     * CipherBlock->Base64->JSON->HEX->Result
     * @return mixed [AuthKey加密信息]
     */
    public function generateAuthKey()
    {
        $PublicKey     = @file_get_contents('../Key/Public');
        $AuthKeyInfo   = [
            'TimeStamp'=>$_SERVER['REQUEST_TIME'],
            'IPAddress'=>$_SERVER['HTTP_CF_CONNECTING_IP'],
            'UserAgent'=>$_SERVER['HTTP_USER_AGENT']
        ];
        $AuthKey       = json_encode($AuthKeyInfo);
        $EnCryptKey    = 'Password';
        foreach(str_split($AuthKey,117) as $CipherBlock)
        {
            openssl_public_encrypt($CipherBlock,$EnCryptKey,$PublicKey);
            $Cipher[]  = base64_encode($EnCryptKey);
        }
        $AuthKeyResult = define('AUTHKEY',bin2hex(json_encode($Cipher)));
        return $AuthKeyResult;
    }


    /**
     * 解密RSA加密的AuthKey信息
     * HEX->JSON->Base64->CipherBlock->Result
     * @param $AuthKey [AuthKey加密信息]
     * @return mixed [AuthKey解密结果]
     */
    public function decryptAuthKey($AuthKey)
    {
        $PrivateKey    = @file_get_contents('../Key/Private');
        $EnCryptKey    = 'Password';
        $AuthKey       = json_decode(pack('H*',$AuthKey));
        $AuthKeyCount  = count($AuthKey);
        $AuthKeyResult = NULL;
        for($j=0;$j<$AuthKeyCount;++$j)
        {
            openssl_private_decrypt(base64_decode($AuthKey[$j]),$EnCryptKey,$PrivateKey);
            $AuthKeyResult.=$EnCryptKey;
        }
        return $AuthKeyResult;
    }

}