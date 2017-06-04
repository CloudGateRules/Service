<?php

class Modules
{


    /**
     * Request Query URI
     */
    public function GET()
    {
        global $REQUEST_QUERY_URI;
        $QUERY_URI = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $REQUEST_QUERY_URI = substr($QUERY_URI,(strpos($QUERY_URI,'?')+1));
    }


    /**
     * @return string
     */
    public function timestamp()
    {
        $timestamp = '?TimeStramp='.sha1(time());
        return $timestamp;
    }


    /**
     * @param $data
     * @param int $limit
     * @return array
     */
    public function splitStringToArray($data,$limit=1)
    {
        $splitResult = explode(',',$data,$limit);
        return $splitResult;
    }


    /**
     * @param $FileURL
     * @return mixed
     */
    public function CURL($FileURL)
    {
        if(!empty($FileURL))
        {
            $CURLHeader = @get_headers($FileURL,true);
            $CURLHeader['Content-Length']<'524288'&&$CURLHeader['Accept-Ranges']==='bytes'?$CURLFile=$FileURL:$CURLFile=false;
            $CURL = curl_init();
            curl_setopt($CURL,CURLOPT_URL,$CURLFile);
            curl_setopt($CURL,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($CURL,CURLOPT_TIMEOUT,30);
            $CURLContent = curl_exec($CURL);
            curl_close($CURL);
            $CURLContent===false&&$CURLHeader===false?exit('CURLContent is error! or timeout!'):false;
            return $CURLContent;
        }else{
            exit('curl fileurl is empty! exit process!');
        }
    }

}