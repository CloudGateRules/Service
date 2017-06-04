<?php


class SubmitType
{


    /**
     * GET Submit Info Type Result
     * @param $SubmitData [SubmitDataInfo]
     * @return mixed [DecodeResult]
     */
    public function getSubmitInfoType($SubmitData)
    {
        if(!empty($SubmitData))
        {
            if(preg_match('/(http|https)/',$SubmitData))
            {
                $Modules = new Modules;
                $Timestamp  = $Modules->timestamp();
                $SubmitType = json_decode($Modules->CURL($SubmitData.$Timestamp),true);
                return $SubmitType;
            }
            elseif(strlen($SubmitData)>1000)
            {
                $SubmitType = json_decode(base64_decode($SubmitData),true);
                return $SubmitType;
            }
        }
        return NULL;
    }


    /**
     * GET Submit Info Verify Result
     * @param $SubmitData [DecodeDataInfo]
     * @param $EngineInfo [EngineDataInfo]
     */
    public function getSubmitInfoVerify($SubmitData,$EngineInfo)
    {
        if(!empty($SubmitData)&&isset($EngineInfo))
        {
            if($SubmitData['Configuration']['Version']<$EngineInfo['Version'])
            {
                $Host = $EngineInfo['Host'];
                $SubmitVersion = $SubmitData['Configuration']['Version'];
                header('Location: '."$Host://".$_SERVER['SERVER_NAME']."/Old/$SubmitVersion".$_SERVER['REQUEST_URI']);
            }
            elseif(!empty($SubmitData['Other']['List']))
            {
                global $CONFIGURATION;
                $CONFIGURATION = $SubmitData;
            }
        }
    }


    /**
     * GET Submit Info List Result
     * @param $SubmitData [VerifyDataInfo]
     * @return mixed [RuleListInfo]
     */
    public function getSubmitInfoList($SubmitData)
    {
        empty($SubmitData)?exit('JSON Information Error!'):true;
        $SubmitInfoList = @$SubmitData['Other']['List'];
        return $SubmitInfoList;
    }

}