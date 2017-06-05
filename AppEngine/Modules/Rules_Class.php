<?php

class Rules
{

    
    /**
     * GET RuleList Info Result
     * @param $SubmitData [SubmitDataInfo]
     */
    public function getRuleListInfo($SubmitData)
    {
        if(!empty($SubmitData))
        {
            global $RuleLists;
            $Modules   = new Modules;
            $Timestamp = $Modules->timestamp();
            $ListsData = json_decode($Modules->CURL($SubmitData.$Timestamp));
            $RuleLists = array
            (
                'General'  =>$ListsData->General,
                'Apple'    =>$ListsData->Apple,
                'Advanced' =>$ListsData->Advanced,
                'Basic'    =>$ListsData->Basic,
                'DIRECT'   =>$ListsData->DIRECT,
                'REJECT'   =>$ListsData->REJECT,
                'KEYWORD'  =>$ListsData->KEYWORD,
                'URLREGEX' =>$ListsData->URLREGEX,
                'USERAGENT'=>$ListsData->USERAGENT,
                'IPCIDR6'  =>$ListsData->IPCIDR6,
                'IPCIDR'   =>$ListsData->IPCIDR,
                'Other'    =>$ListsData->Other,
                'Host'     =>$ListsData->Host,
                'YouTube'  =>$ListsData->YouTube,
                'Rewrite'  =>$ListsData->Rewrite,
                'MITM'     =>$ListsData->MITM,
                'SKIP'     =>$ListsData->SKIP
            );
        }
    }


    /**
     * GET Hosts Interface Info Result
     * @param $EngineInfo [EngineInfo]
     */
    public function getHostsInfo($EngineInfo)
    {
        if(!empty($EngineInfo))
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
            $Replace1  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5,$7",$RuleList);
            $Replace2  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy,$7",$Replace1);
            $Replace3  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace2);
            $Replace4  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace3);
            $Replace5  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace4);
            $Replace6  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace5);
            $Replace7  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace6);
            $Replace8  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace7);
            $Replace9  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace8);
            $Replace10 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace9);
            $Replace11 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace10);
            $Replace12 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace11);
            $Replace13 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5,$7",$Replace12);
            $Replace14 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy,$7",$Replace13);
            $Replace15 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace14);
            $Replace16 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace15);
            $Replace17 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"URL-REGEX,$3,$5,$7",$Replace16);
            $Replace18 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"URL-REGEX,$3,$Policy,$7",$Replace17);
            $Replace19 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"URL-REGEX,$3,$5",$Replace18);
            $Replace20 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"URL-REGEX,$3,$Policy",$Replace19);
            $Replace21 = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"IP-CIDR6,$3,$5,$7",$Replace20);
            $Replace22 = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR6,$3,$Policy,$7",$Replace21);
            $Replace23 = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"IP-CIDR6,$3,$5",$Replace22);
            $Replace24 = $this->loopArrayReplace('/(\[IPCIDR6])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"IP-CIDR6,$3,$Policy",$Replace23);
            $Replace25 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace24);
            $Replace26 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace25);
            $Replace27 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace26);
            $Replace28 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace27);
            $Replace29 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace28);
            $Replace30 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace29);
            $Replace31 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace30);
            $Replace32 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace31);
            $Replace33 = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace32);
            $Replace34 = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace33);
            $Replace35 = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','$1$3$4',$Replace34);
            $Result    = $this->loopArrayToString($Replace35);
            return $Result;
        }
        elseif($Format==='Shadowrocket')
        {
            $Replace1  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace2  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace1);
            $Replace3  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace2);
            $Replace4  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace3);
            $Replace5  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace4);
            $Replace6  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace5);
            $Replace7  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace6);
            $Replace8  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace7);
            $Replace9  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace8);
            $Replace10 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace9);
            $Replace11 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace10);
            $Replace12 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace11);
            $Replace13 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5",$Replace12);
            $Replace14 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy",$Replace13);
            $Replace15 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace14);
            $Replace16 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace15);
            $Replace17 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace16);
            $Replace18 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace17);
            $Replace19 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace18);
            $Replace20 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace19);
            $Replace21 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace20);
            $Replace22 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace21);
            $Replace23 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace22);
            $Replace24 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace23);
            $Replace25 = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace24);
            $Replace26 = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace25);
            $Replace27 = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','$1$3$4',$Replace26);
            $Result    = $this->loopArrayToString($Replace27);
            return $Result;
        }
        elseif($Format==='A.BIG.T')
        {
            $Replace1  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace2  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace1);
            $Replace3  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace2);
            $Replace4  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace3);
            $Replace5  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace4);
            $Replace6  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace5);
            $Replace7  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace6);
            $Replace8  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace7);
            $Replace9  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace8);
            $Replace10 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace9);
            $Replace11 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace10);
            $Replace12 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace11);
            $Replace13 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5",$Replace12);
            $Replace14 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy",$Replace13);
            $Replace15 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace14);
            $Replace16 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace15);
            $Replace17 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace16);
            $Replace18 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace17);
            $Replace19 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace18);
            $Replace20 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace19);
            $Replace21 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace20);
            $Replace22 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace21);
            $Replace23 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace22);
            $Replace24 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace23);
            $Replace25 = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace24);
            $Replace26 = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace25);
            $Replace27 = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','$1$3$4',$Replace26);
            $Result    = $this->loopArrayToString($Replace27);
            return $Result;
        }
        elseif($Format==='Potatso')
        {
            $Replace1  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"  - DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace2  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"  - DOMAIN-SUFFIX,$3,$Policy",$Replace1);
            $Replace3  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN,$3,$5",$Replace2);
            $Replace4  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN,$3,$Policy",$Replace3);
            $Replace5  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN-SUFFIX,$3,$5",$Replace4);
            $Replace6  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN-SUFFIX,$3,$Policy",$Replace5);
            $Replace7  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN,$3,$5",$Replace6);
            $Replace8  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN,$3,$Policy",$Replace7);
            $Replace9  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"  - DOMAIN-MATCH,$3,$5",$Replace8);
            $Replace10 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"  - DOMAIN-MATCH,$3,$Policy",$Replace9);
            $Replace11 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"  - DOMAIN-MATCH,$3,$5",$Replace10);
            $Replace12 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"  - DOMAIN-MATCH,$3,$Policy",$Replace11);
            $Replace13 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"  - IP-CIDR,$2,$4",$Replace12);
            $Replace14 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"  - IP-CIDR,$2,$Policy",$Replace13);
            $Replace15 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"  - IP-CIDR,$2,$4",$Replace14);
            $Replace16 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"  - IP-CIDR,$2,$Policy",$Replace15);
            $Replace17 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"  - $3,$5,$7",$Replace16);
            $Replace18 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"  - $3,$5,$7",$Replace17);
            $Replace19 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/','',$Replace18);
            $Replace20 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/','',$Replace19);
            $Replace21 = $this->loopArrayReplace('/(<<)(.*)/','  # $2',$Replace20);
            $Replace22 = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace21);
            $Replace23 = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace22);
            $Result    = $this->loopArrayToString($Replace23);
            return $Result;
        }
        elseif($Format==='Cross')
        {
            $Replace1  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$5",$RuleList);
            $Replace2  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace1);
            $Replace3  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace2);
            $Replace4  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace3);
            $Replace5  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-SUFFIX,$3,$5",$Replace4);
            $Replace6  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-SUFFIX,$3,$Policy",$Replace5);
            $Replace7  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN,$3,$5",$Replace6);
            $Replace8  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN,$3,$Policy",$Replace7);
            $Replace9  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$5,$7",$Replace8);
            $Replace10 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"DOMAIN-KEYWORD,$3,$Policy,$7",$Replace9);
            $Replace11 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"DOMAIN-KEYWORD,$3,$5",$Replace10);
            $Replace12 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-KEYWORD,$3,$Policy",$Replace11);
            $Replace13 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$5",$Replace12);
            $Replace14 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"USER-AGENT,$3,$Policy",$Replace13);
            $Replace15 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"USER-AGENT,$3,$5",$Replace14);
            $Replace16 = $this->loopArrayReplace('/(\[USERAGENT])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"USER-AGENT,$3,$Policy",$Replace15);
            $Replace17 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$4,$6",$Replace16);
            $Replace18 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"IP-CIDR,$2,$Policy,$6",$Replace17);
            $Replace19 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"IP-CIDR,$2,$4",$Replace18);
            $Replace20 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"IP-CIDR,$2,$Policy",$Replace19);
            $Replace21 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(]\|\[)(.*?)(])/',"DOMAIN-REGEX,$3,$5",$Replace20);
            $Replace22 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"DOMAIN-REGEX,$3,$Policy",$Replace21);
            $Replace23 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(REJECT|DIRECT)(])/',"DOMAIN-REGEX,$3,$5",$Replace22);
            $Replace24 = $this->loopArrayReplace('/(\[URLREGEX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"DOMAIN-REGEX,$3,$Policy",$Replace23);
            $Replace25 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7,$9",$Replace24);
            $Replace26 = $this->loopArrayReplace('/(\[)(\$)(GEOIP)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$5,$7",$Replace25);
            $Replace27 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"$3,$Policy,$7",$Replace26);
            $Replace28 = $this->loopArrayReplace('/(\[)(\$)(FINAL)(]\|\[)(.*?)(])/',"$3,$Policy",$Replace27);
            $Replace29 = $this->loopArrayReplace('/(<<)(.*)/','# $2',$Replace28);
            $Replace30 = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace29);
            $Replace31 = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace30);
            $Result    = $this->loopArrayToString($Replace31);
            return $Result;
        }
        elseif($Format==='Potatso2')
        {
            $Replace1  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"    \"DOMAIN-SUFFIX,$3,$5\",",$RuleList);
            $Replace2  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"    \"DOMAIN-SUFFIX,$3,$Policy\",",$Replace1);
            $Replace3  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN,$3,$5\",",$Replace2);
            $Replace4  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN,$3,$Policy\",",$Replace3);
            $Replace5  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN-SUFFIX,$3,$5\",",$Replace4);
            $Replace6  = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN-SUFFIX,$3,$Policy\",",$Replace5);
            $Replace7  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN,$3,$5\",",$Replace6);
            $Replace8  = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN,$3,$Policy\",",$Replace7);
            $Replace9  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"    \"DOMAIN-MATCH,$3,$5\",",$Replace8);
            $Replace10 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(\]\|\[)(.*?)(])/',"    \"DOMAIN-MATCH,$3,$Policy\",",$Replace9);
            $Replace11 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(])/',"    \"DOMAIN-MATCH,$3,$5\",",$Replace10);
            $Replace12 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"    \"DOMAIN-MATCH,$3,$Policy\",",$Replace11);
            $Replace13 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(]\|\[)(.*?)(])/',"    \"IP-CIDR,$2,$4\",",$Replace12);
            $Replace14 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"    \"IP-CIDR,$2,$Policy\",",$Replace13);
            $Replace15 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|REJECT)(])/',"    \"IP-CIDR,$2,$4\",",$Replace14);
            $Replace16 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(])/',"    \"IP-CIDR,$2,$Policy\",",$Replace15);
            $Replace17 = $this->loopArrayReplace('/(\[)(\$)(.*?)(]\|\[)(.*?)(]\|\[)(.*?)(])/',"    \"$3,$5,$7\"",$Replace16);
            $Replace18 = $this->loopArrayReplace('/(\[)(\$)(.*?)(]\|\[)(.*?)(])/',"",$Replace17);
            $Replace19 = $this->loopArrayReplace('/(<<)(.*)/','    # $2',$Replace18);
            $Replace20 = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace19);
            $Replace21 = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace20);
            $Result    = $this->loopArrayToString($Replace21);
            return $Result;
        }
        elseif($Format==='Hosts')
        {
            $Replace1 = preg_replace('/(\d+\.\d+\.\d+\.\d+)([ \t]+)([a-zA-Z0-9_\-\/.]+)/', '$3 = $1', $RuleList);
            $Replace2 = preg_replace('/(::)/', '# ::',$Replace1);
            $Replace3 = preg_replace('/(<<)(.*)/', '# $2', $Replace2);
            $Replace4 = preg_replace('/(\[)(!)(.*?)(])/', '$1$3$4', $Replace3);
            $Result   = $Replace4;
            return $Result;
        }
        elseif($Format==='YouTube')
        {
            $Replace1 = $this->loopArrayReplace('/([a-zA-Z0-9_\-\/.%]+)/', "$1 = $SNIData", $RuleList);
            $Replace2 = $this->loopArrayReplace('/(<<)(.*)/', '# $2', $Replace1);
            $Replace3 = $this->loopArrayReplace('/(\[)(!)(.*?)(])/', '$1$3$4', $Replace2);
            $Result   = $this->loopArrayToString($Replace3);
            return $Result;
        }
        elseif($Format==='Wingy')
        {
            $Replace1   = $this->loopArrayReplace('/(<<)(.*)/','# $2',$RuleList);
            $Replace2   = $this->loopArrayReplace('/(\[)(AUTHKEY)(])/',"[$AuthKey$3",$Replace1);
            $Replace3   = $this->loopArrayReplace('/(\[)(!)(.*?)(])/','',$Replace2);
            $DIRECT1    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - s,$3",$Replace3);
            $DIRECT2    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(DIRECT)(])/',"      - s,$3",$DIRECT1);
            $DIRECT3    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - c,$3",$DIRECT2);
            $DIRECT4    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(DIRECT)(])/',"      - c,$3",$DIRECT3);
            $DIRECT     = $this->loopArrayToString($DIRECT4);
            $REJECT1    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - s,$3",$Replace3);
            $REJECT2    = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(REJECT)(])/',"      - s,$3",$REJECT1);
            $REJECT3    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - c,$3",$REJECT2);
            $REJECT4    = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(REJECT)(])/',"      - c,$3",$REJECT3);
            $REJECT     = $this->loopArrayToString($REJECT4);
            $Proxy1     = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - s,$3",$Replace3);
            $Proxy2     = $this->loopArrayReplace('/(\[SUFFIX])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"      - s,$3",$Proxy1);
            $Proxy3     = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - c,$3",$Proxy2);
            $Proxy4     = $this->loopArrayReplace('/(\[DOMAIN])(\|\[)(.*?)(]\|\[)(Proxy)(])/',"      - c,$3",$Proxy3);
            $Proxy      = $this->loopArrayToString($Proxy4);
            $KW_DIRECT1 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - k,$3",$Replace3);
            $KW_DIRECT2 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT)(])/',"      - k,$3",$KW_DIRECT1);
            $KW_DIRECT3 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy|REJECT)(].*)/','',$KW_DIRECT2);
            $KW_DIRECT  = $this->loopArrayToString($KW_DIRECT3);
            $KW_REJECT1 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - k,$3",$Replace3);
            $KW_REJECT2 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(REJECT)(])/',"      - k,$3",$KW_REJECT1);
            $KW_REJECT3 = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy|DIRECT)(].*)/','',$KW_REJECT2);
            $KW_REJECT  = $this->loopArrayToString($KW_REJECT3);
            $KW_Proxy1  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - k,$3",$Replace3);
            $KW_Proxy2  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(Proxy)(])/',"      - k,$3",$KW_Proxy1);
            $KW_Proxy3  = $this->loopArrayReplace('/(\[)(\^)(.*?)(]\|\[)(DIRECT|REJECT)(].*)/','',$KW_Proxy2);
            $KW_Proxy   = $this->loopArrayToString($KW_Proxy3);
            $IC_DIRECT1 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT)(]\|\[)(.*?)(])/',"      - $2",$Replace3);
            $IC_DIRECT2 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT)(])/',"      - $2",$IC_DIRECT1);
            $IC_DIRECT3 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy|REJECT)(].*)/','',$IC_DIRECT2);
            $IC_DIRECT  = $this->loopArrayToString($IC_DIRECT3);
            $IC_REJECT1 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(REJECT)(]\|\[)(.*?)(])/',"      - $2",$Replace3);
            $IC_REJECT2 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(REJECT)(])/',"      - $2",$IC_REJECT1);
            $IC_REJECT3 = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(DIRECT|Proxy)(].*)/','',$IC_REJECT2);
            $IC_REJECT  = $this->loopArrayToString($IC_REJECT3);
            $IC_Proxy1  = $this->loopArrayReplace('/(\[)(\d+.\d+.\d+.\d+\/\d+)(]\|\[)(Proxy)(]\|\[)(.*?)(])/',"      - $2",$Replace3);
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