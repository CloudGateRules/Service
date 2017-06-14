<?php

class Rules
{

    
    /**
     * 获取规则列表信息结果
     * @param $SubmitData [SubmitDataInfo]
     */
    public function getRuleListInfo($SubmitData)
    {
        if(!empty($SubmitData))
        {
            global $RuleLists;
            $Modules   = new Modules;
            $Timestamp = $Modules->timestamp();
            if($SubmitData['Merge']!==NULL)
            {
            	$ListsData = json_decode($Modules->CURL($SubmitData['Lists'].$Timestamp),true);
            	$MergeData = json_decode($Modules->CURL($SubmitData['Merge'].$Timestamp),true);
            }
            else{
            	$ListsData = json_decode($Modules->CURL($SubmitData['Lists'].$Timestamp),true);
                $MergeData = [];
            }
            $RuleLists = array_merge_recursive($ListsData,$MergeData);
        }
    }


    /**
     * 获取Hosts接口数据信息
     * @param $EngineInfo [EngineInfo]
     */
    public function getHostsInfo($EngineInfo)
    {
        if(is_string($EngineInfo))
        {
            global $HostsList;
            $Modules   = new Modules;
            $Timestamp = $Modules->timestamp();
            $HostsInfo = json_decode($Modules->CURL($EngineInfo.$Timestamp));
            $HostsList = array
            (
                'Interface' =>$Modules->CURL($HostsInfo->Interface->HostsAPI.$Timestamp),
                'YouTubeIP' =>$HostsInfo->Hosts->Default_YouTubeIP
            );
        }
    }


    /**
     * Loop Array Replace Result
     * @param $Pattern
     * @param $Replacement
     * @param $Subject
     * @return null
     */
    public function loopArrayReplace($Pattern,$Replacement,$Subject)
    {
        $Result = NULL;
        for($i=0;$i<count($Subject);++$i)
        {
            $Result[$i] = @preg_replace($Pattern,$Replacement,$Subject[$i]);
        }
        return $Result;
    }


    /**
     * Loop Array To String Result
     * @param $Subject [Subject]
     * @return null|string [NULL/String]
     */
    public function loopArrayToString($Subject)
    {
        $Result = NULL;
        for($i=0;$i<count($Subject);++$i)
        {
            $Result.=$Subject[$i]."\r\n";
        }
        $Result = preg_replace('/[\$$\r\n]+$/',"\r\n",$Result);
        $Result = preg_replace('/(\$\$\r\n)/',', ',$Result);
        return $Result;
    }

    
    /**
     * Loop Rule Replace Result
     * @param $Format
     * @param $RuleList
     * @param null $Policy
     * @param null $AuthKey
     * @param null $SNIData
     * @return mixed|null|string
     */
    public function ruleReplace($Format,$RuleList,$Policy=null,$AuthKey=null,$SNIData=null)
    {
        if($Format==='Surge')
        {
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5,$7",$RuleList);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"URL-REGEX,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"URL-REGEX,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"URL-REGEX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"URL-REGEX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"IP-CIDR6,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR6,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"IP-CIDR6,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"IP-CIDR6,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[PROCESS])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"PROCESS-NAME,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[PROCESS])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"PROCESS-NAME,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[PROCESS])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"PROCESS-NAME,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[PROCESS])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"PROCESS-NAME,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','$1$3$4',$Replace);
            $Result  = $this->loopArrayToString($Replace);
            return $Result;
        }
        elseif($Format==='Shadowrocket')
        {
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','$1$3$4',$Replace);
            $Result  = $this->loopArrayToString($Replace);
            return $Result;
        }
        elseif($Format==='A.BIG.T')
        {
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','$1$3$4',$Replace);
            $Result  = $this->loopArrayToString($Replace);
            return $Result;
        }
        elseif($Format==='Potatso')
        {
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"  - DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"  - DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN-SUFFIX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"  - DOMAIN-MATCH,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"  - DOMAIN-MATCH,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN-MATCH,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN-MATCH,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"  - IP-CIDR,$2,$4",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"  - IP-CIDR,$2,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"  - IP-CIDR,$2,$4",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"  - IP-CIDR,$2,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"  - $3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"  - $3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/','',$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/','',$Replace);
            $Replace = $this->loopArrayReplace('/(<<)(.*)/','  # $2',$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace);
            $Result  = $this->loopArrayToString($Replace);
            return $Result;
        }
        elseif($Format==='Cross')
        {
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"DOMAIN-REGEX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-REGEX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"DOMAIN-REGEX,$3,$5",$Replace);
            $Replace = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-REGEX,$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace);
            $Replace = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace);
            $Result  = $this->loopArrayToString($Replace);
            return $Result;
        }
        elseif($Format==='Potatso2')
        {
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"    \"DOMAIN-SUFFIX,$3,$5\",",$RuleList);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"    \"DOMAIN-SUFFIX,$3,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN,$3,$5\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN,$3,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN-SUFFIX,$3,$5\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN-SUFFIX,$3,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN,$3,$5\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN,$3,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"    \"DOMAIN-MATCH,$3,$5\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"    \"DOMAIN-MATCH,$3,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN-MATCH,$3,$5\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN-MATCH,$3,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"    \"IP-CIDR,$2,$4\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"    \"IP-CIDR,$2,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"    \"IP-CIDR,$2,$4\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"    \"IP-CIDR,$2,$Policy\",",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"    \"$3,$5,$7\"",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(\$)(.*?)(]\|\[)(.*?)(])/',"",$Replace);
            $Replace = $this->loopArrayReplace('/(<<)(.*)/','    # $2',$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace);
            $Replace = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace);
            $Result  = $this->loopArrayToString($Replace);
            return $Result;
        }
        elseif($Format==='Hosts')
        {
            $Replace = preg_replace('/(\d+\.\d+\.\d+\.\d+)([ \t]+)([a-zA-Z0-9_\-\/.]+)/', '$3 = $1', $RuleList);
            $Replace = preg_replace('/(::)/', '# ::',$Replace);
            $Replace = preg_replace('/(<<)(.*)/', '# $2', $Replace);
            $Replace = preg_replace('/(\[)(!)(.*?)(])/', '$1$3$4', $Replace);
            $Result  = $Replace;
            return $Result;
        }
        elseif($Format==='YouTube')
        {
            $Replace = $this->loopArrayReplace('/([a-zA-Z0-9_\-\/.%]+)/', "$1 = $SNIData", $RuleList);
            $Replace = $this->loopArrayReplace('/(<<)(.*)/', '# $2', $Replace);
            $Replace = $this->loopArrayReplace('/(\[)(!)(.*?)(])/', '$1$3$4', $Replace);
            $Result  = $this->loopArrayToString($Replace);
            return $Result;
        }
        elseif($Format==='Wingy')
        {
            $Replace    = $this->loopArrayReplace('/(<<)(.*)/','# $2',$RuleList);
            $Replace    = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace);
            $Replace    = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace);
            $DIRECT1    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - s,$3",$Replace);
            $DIRECT2    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT)(])/',"      - s,$3",$DIRECT1);
            $DIRECT3    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - c,$3",$DIRECT2);
            $DIRECT4    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT)(])/',"      - c,$3",$DIRECT3);
            $DIRECT     = $this->loopArrayToString($DIRECT4);
            $REJECT1    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - s,$3",$Replace);
            $REJECT2    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(REJECT)(])/',"      - s,$3",$REJECT1);
            $REJECT3    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - c,$3",$REJECT2);
            $REJECT4    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(REJECT)(])/',"      - c,$3",$REJECT3);
            $REJECT     = $this->loopArrayToString($REJECT4);
            $Proxy1     = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - s,$3",$Replace);
            $Proxy2     = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"      - s,$3",$Proxy1);
            $Proxy3     = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - c,$3",$Proxy2);
            $Proxy4     = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"      - c,$3",$Proxy3);
            $Proxy      = $this->loopArrayToString($Proxy4);
            $KW_DIRECT1 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - k,$3",$Replace);
            $KW_DIRECT2 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT)(])/',"      - k,$3",$KW_DIRECT1);
            $KW_DIRECT3 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy|REJECT)(].*)/','',$KW_DIRECT2);
            $KW_DIRECT  = $this->loopArrayToString($KW_DIRECT3);
            $KW_REJECT1 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - k,$3",$Replace);
            $KW_REJECT2 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(REJECT)(])/',"      - k,$3",$KW_REJECT1);
            $KW_REJECT3 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy|DIRECT)(].*)/','',$KW_REJECT2);
            $KW_REJECT  = $this->loopArrayToString($KW_REJECT3);
            $KW_Proxy1  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - k,$3",$Replace);
            $KW_Proxy2  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"      - k,$3",$KW_Proxy1);
            $KW_Proxy3  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(].*)/','',$KW_Proxy2);
            $KW_Proxy   = $this->loopArrayToString($KW_Proxy3);
            $IC_DIRECT1 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - $2",$Replace);
            $IC_DIRECT2 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT)(])/',"      - $2",$IC_DIRECT1);
            $IC_DIRECT3 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy|REJECT)(].*)/','',$IC_DIRECT2);
            $IC_DIRECT  = $this->loopArrayToString($IC_DIRECT3);
            $IC_REJECT1 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - $2",$Replace);
            $IC_REJECT2 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(REJECT)(])/',"      - $2",$IC_REJECT1);
            $IC_REJECT3 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|Proxy)(].*)/','',$IC_REJECT2);
            $IC_REJECT  = $this->loopArrayToString($IC_REJECT3);
            $IC_Proxy1  = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - $2",$Replace);
            $IC_Proxy2  = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"      - $2",$IC_Proxy1);
            $IC_Proxy3  = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(].*)/',"      - $2",$IC_Proxy2);
            $IC_Proxy   = $this->loopArrayToString($IC_Proxy3);
            $Result = [
                'DIRECT'=>$DIRECT,
                'REJECT'=>$REJECT,
                'Proxy' =>$Proxy,
                'KEYWORD_DIRECT'=>$KW_DIRECT,
                'KEYWORD_REJECT'=>$KW_REJECT,
                'KEYWORD_Proxy' =>$KW_Proxy,
                'IPCIDR_DIRECT' =>$IC_DIRECT,
                'IPCIDR_REJECT' =>$IC_REJECT,
                'IPCIDR_Proxy'  =>$IC_Proxy
            ];
            return $Result;
        }
        return NULL;
    }

}