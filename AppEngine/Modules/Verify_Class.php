<?php

class Verify
{

    /**
     * 发送AuthKey信息至验证模块
     * @param $AuthKey [AuthKey加密信息]
     * @return true [true]
     */
    public function sendVerifyAuthKey($AuthKey)
    {
        if(!empty($AuthKey))
        {
            $AuthKey = preg_replace('/(%5b|\[|]|%5d)/','',$AuthKey);
            $this->verifyAuthKeyValid($AuthKey);
            return true;
        }
        else{
            header('HTTP/1.1 400 Bad Request');
            exit('AuthKey Is Empty! -1');
        }
    }


    /**
     * 解密并验证AuthKey正确性
     * @param $Data [AuthKey加密信息]
     * @return true [true]
     */
    public function verifyAuthKeyValid($Data)
    {
        $Auth = new Auth;
        $DeCryptInfo = json_decode($Auth->decryptAuthKey($Data));
        $REMOTEADDR = $_SERVER['REMOTE_ADDR'];
        if($DeCryptInfo!==NULL)
        {
            header('X-DownloadIP: '.$DeCryptInfo->IPAddress);
            header('X-RequestsIP: '.$_SERVER['REMOTE_ADDR']);
            header('X-DownloadTS: '.$DeCryptInfo->TimeStamp);
            if($REMOTEADDR!==$DeCryptInfo->IPAddress)
            {
                header('HTTP/1.1 400 Bad Request');
                exit('Request IPAddress And Download Configuration IPAddress Not Match! -2');
            }
        }
        else{
            header('HTTP/1.1 400 Bad Request');
            exit('AuthKey Verify Failed! -3');
        }
        return true;
    }

}