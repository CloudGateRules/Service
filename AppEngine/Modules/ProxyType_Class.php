<?php

class ProxyType
{

    /**
     * GET Split Arguments Info Result
     * @param $SubmitData [RuleListDataInfo]
     * @return array [SplitDataInfo]
     */
    public function getSplitArgumentsInfo($SubmitData)
    {
        $Modules = new Modules;
        $Result =
            [
                'Status'=>
                    [
                        'DNS'=>@$Modules->splitStringToArray($SubmitData['Arguments']['DNS'],2)[0],
                        'External'=>@$Modules->splitStringToArray($SubmitData['Arguments']['External'],2)[0],
                        'MacOS'=>@$Modules->splitStringToArray($SubmitData['Arguments']['MacOS'],5)[0],
                        'MITM'=>@$Modules->splitStringToArray($SubmitData['Arguments']['MITM'],3)[0],
                        'WIFIAccess'=>@$SubmitData['General']['WIFIAccess'],
                        'IPV6'=>@$SubmitData['General']['IPV6'],
                        'Replica'=>@$SubmitData['General']['Replica'],
                        'EnhancedMode'=>@$SubmitData['General']['EnhancedMode'],
                        'ExcludeHost'=>@$SubmitData['General']['ExcludeHost']
                    ],
                'Info'=>
                    [
                        'DNS'=>@$Modules->splitStringToArray($SubmitData['Arguments']['DNS'],2)[1],
                        'External'=>@$Modules->splitStringToArray($SubmitData['Arguments']['External'],2)[1],
                        'MacOS'=>
                            [
                                @$Modules->splitStringToArray($SubmitData['Arguments']['MacOS'],5)[1],
                                @$Modules->splitStringToArray($SubmitData['Arguments']['MacOS'],5)[2],
                                @$Modules->splitStringToArray($SubmitData['Arguments']['MacOS'],5)[3],
                                @$Modules->splitStringToArray($SubmitData['Arguments']['MacOS'],5)[4]
                            ],
                        'MITM'=>
                            [
                                @$Modules->splitStringToArray($SubmitData['Arguments']['MITM'],3)[0],
                                @$Modules->splitStringToArray($SubmitData['Arguments']['MITM'],3)[1],
                                @$Modules->splitStringToArray($SubmitData['Arguments']['MITM'],3)[2]
                            ]
                    ]
            ];
        return $Result;
    }


    /**
     * GET Arguments Info Result
     * @param $Format [ConfigurationFormatInfo]
     * @param $SubmitData [SubmitDataInfo]
     */
    public function getArgumentsInfo($Format,$SubmitData)
    {
        $ArgumentsInfo = $this->getSplitArgumentsInfo($SubmitData);
        if($Format==='Surge')
        {
            echo @$ArgumentsInfo['Status']['DNS']==true?'dns-server = '.$ArgumentsInfo['Info']['DNS']."\r\n":false;
            echo @$ArgumentsInfo['Status']['External']==true?'external-controller-access = '.$ArgumentsInfo['Info']['External']."\r\n":false;
            echo @$ArgumentsInfo['Status']['WIFIAccss']===true?"allow-wifi-access = true\r\n":false;
            echo @$ArgumentsInfo['Status']['IPV6']===true?"ipv6 = true\r\n":false;
            echo @$ArgumentsInfo['Status']['Replica']===true?"replica = true\r\n":false;
            echo @$ArgumentsInfo['Status']['EnhancedMode']===true?"enhanced-mode-by-rule = true\r\n":false;
            echo @$ArgumentsInfo['Status']['ExcludeHost']===true?"exclude-simple-hostnames = true\r\n":false;
            if(@$ArgumentsInfo['Status']['MacOS']==true)
            {
                echo 'interface = '.@$ArgumentsInfo['Info']['MacOS'][0]."\r\n";
                echo 'socks-interface = '.@$ArgumentsInfo['Info']['MacOS'][1]."\r\n";
                echo 'port = '.@$ArgumentsInfo['Info']['MacOS'][2]."\r\n";
                echo 'socks-port = '.@$ArgumentsInfo['Info']['MacOS'][3]."\r\n";
            }
        }
        elseif($Format==='Shadowrocket')
        {
            echo @$ArgumentsInfo['Status']['DNS']==='true'?'dns-server = '.$ArgumentsInfo['Info']['DNS']."\r\n":false;
        }
        elseif($Format==='A.BIG.T')
        {
            echo @$ArgumentsInfo['Status']['DNS']==='true'?'dns-server = '.$ArgumentsInfo['Info']['DNS']."\r\n":false;
        }
        elseif($Format==='Cross')
        {
            echo @$ArgumentsInfo['Status']['DNS']==='true'?$ArgumentsInfo['Info']['DNS']."\r\n":false;
        }
    }


    /**
     * GET Server Info Result
     * @param $Format [ConfigurationFormatInfo]
     * @param $SubmitData [SubmitDataInfo]
     */
    public function getServerInfo($Format,$SubmitData)
    {
        $ServerInfoArrayResult = $SubmitData['Info']['SERVER'];
        $ServerInfoCountResult = @count($ServerInfoArrayResult);
        $ServerMatchResult = NULL;
        if($Format==='Surge')
        {
            echo "[Proxy]\r\n";
            for($j=0;$j<$ServerInfoCountResult;++$j)
            {
                @preg_match_all('/\[Surge]\|.*/',$ServerInfoArrayResult[$j],$ServerMatchResult[$j]);
                @$Filter[] = array_filter($ServerMatchResult[$j][0]);
                @$ReFilter = array_filter($Filter);
                @$Replace1 = preg_replace('/(\[Surge]\|)/','',$ReFilter[$j][0]);
                @$Replace2 = preg_replace('/\[Module\]/',$SubmitData['Other']['Module'],$Replace1);
                echo $Replace2."\r\n";
            }
        }
        elseif($Format==='A.BIG.T')
        {
            echo "[Proxy]\r\n";
            for($j=0;$j<$ServerInfoCountResult;++$j)
            {
                preg_match_all('/\[A.BIG.T]\|.*/',$ServerInfoArrayResult[$j],$ServerMatchResult[$j]);
                $Filter[] = array_filter($ServerMatchResult[$j][0]);
                $ReFilter = array_filter($Filter);
                $Replace1 = preg_replace('/(\[A.BIG.T]\|)/','',$ReFilter[$j][0]);
                echo $Replace1."\r\n";
            }
        }
        elseif($Format==='Potatso2')
        {
            for($j=0;$j<$ServerInfoCountResult;++$j)
            {
                preg_match_all('/\[Potatso2]\|.*/',$ServerInfoArrayResult[$j],$ServerMatchResult[$j]);
                $Filter[] = array_filter($ServerMatchResult[$j][0]);
                $ReFilter = array_filter($Filter);
                $Replace1 = preg_replace('/(\[Potatso2]\|)/','',$ReFilter[$j][0]);
                $Replace2 = preg_replace('/(,)/',"\r\n",$Replace1);
                echo $Replace2."\r\n";
            }
        }
        elseif($Format==='Potatso')
        {
            for($j=0;$j<$ServerInfoCountResult;++$j)
            {
                preg_match_all('/\[Potatso]\|.*/',$ServerInfoArrayResult[$j],$ServerMatchResult[$j]);
                $Filter[] = array_filter($ServerMatchResult[$j][0]);
                $ReFilter = array_filter($Filter);
                $Replace1 = preg_replace('/(\[Potatso]\|)/','',$ReFilter[$j][0]);
                $Replace2 = preg_replace('/(,)/',"\r\n",$Replace1);
                echo $Replace2."\r\n";
            }
        }
        elseif($Format==='Cross')
        {
            echo "[PROXY]\r\n";
            for($j=0;$j<$ServerInfoCountResult;++$j)
            {
                preg_match_all('/\[Cross]\|.*/',$ServerInfoArrayResult[$j],$ServerMatchResult[$j]);
                $Filter[] = array_filter($ServerMatchResult[$j][0]);
                $ReFilter = array_filter($Filter);
                $Replace1 = preg_replace('/(\[Cross]\|)/','',$ReFilter[$j][0]);
                echo $Replace1."\r\n";
            }
        }
    }


    /**
     * GET Group Info Result
     * @param $Format [ConfigurationFormatInfo]
     * @param $SubmitData [SubmitDataInfo]
     */
    public function getGroupInfo($Format,$SubmitData)
    {
        $GroupInfoArrayResult = $SubmitData['Info']['Group'];
        $GroupInfoCountResult = @count($GroupInfoArrayResult);
        $GroupMatchResult = NULL;
        if($Format==='Surge')
        {
            echo "[Proxy Group]\r\n";
            for($i=0;$i<$GroupInfoCountResult;++$i)
            {
                @preg_match_all('/\[Surge]\|.*/',$GroupInfoArrayResult[$i],$GroupMatchResult[$i]);
                @$Filter[] = array_filter($GroupMatchResult[$i][0]);
                @$ReFilter = array_filter($Filter);
                @$Replace1 = preg_replace('/(\[Surge]\|)/','',$ReFilter[$i][0]);
                echo $Replace1."\r\n";
            }
        }
        elseif($Format==='A.BIG.T')
        {
            echo "[Proxy Group]\r\n";
            for($i=0;$i<$GroupInfoCountResult;++$i)
            {
                preg_match_all('/\[A.BIG.T]\|.*/',$GroupInfoArrayResult[$i],$GroupMatchResult[$i]);
                $Filter[] = array_filter($GroupMatchResult[$i][0]);
                $ReFilter = array_filter($Filter);
                $Replace1 = preg_replace('/(\[A.BIG.T]\|)/','',$ReFilter[$i][0]);
                echo $Replace1."\r\n";
            }
        }
        elseif($Format==='Cross')
        {
            echo "[PROXY ALIAS]\r\n";
            for($i=0;$i<$GroupInfoCountResult;++$i)
            {
                preg_match_all('/\[Cross]\|.*/',$GroupInfoArrayResult[$i],$GroupMatchResult[$i]);
                $Filter[] = array_filter($GroupMatchResult[$i][0]);
                $ReFilter = array_filter($Filter);
                $Replace1 = preg_replace('/(\[Cross]\|)/','',$ReFilter[$i][0]);
                echo $Replace1."\r\n";
            }
        }
    }


    /**
     * GET MITM Info Result
     * @param $SubmitData [SubmitDataInfo]
     * @param $RuleList [RuleListInfo]
     */
    public function getMitmInfo($SubmitData,$RuleList)
    {
        $Rules   = new Rules;
        $Modules = new Modules;
        $ArgumentsInfo = $this->getSplitArgumentsInfo($SubmitData);
        if(@$ArgumentsInfo['Status']['MITM']==='true')
        {
            echo "[MITM]\r\n";
            echo 'enable = '.@$ArgumentsInfo['Info']['MITM'][0]."\r\n";
            echo 'hostname = '.@$Rules->ruleReplace('Surge',$RuleList);
            echo 'ca-passphrase = '.@$ArgumentsInfo['Info']['MITM'][1]."\r\n";
            echo 'ca-p12 = '.@$Modules->CURL($ArgumentsInfo['Info']['MITM'][2])."\r\n";
        }
    }
}