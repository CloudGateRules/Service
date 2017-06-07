<?php


class SubmitType
{


    /**
     * 获取提交数据信息类型
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
     * 获取提交数据验证结果
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
     * 获取提交数据的规则列表和合并数据
     * @param $SubmitData [VerifyDataInfo]
     * @return mixed [RuleListInfo]
     */
    public function getSubmitInfoList($SubmitData)
    {
        if(empty($SubmitData))
        {
            exit('JSON Information Error!');
        }
        return [
            'Lists'=>@$SubmitData['Other']['List'],
            'Merge'=>@$SubmitData['Other']['Merge']
        ];
    }

}