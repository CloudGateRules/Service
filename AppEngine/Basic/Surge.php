<?php

header('Content-Disposition: attachment; filename='.'Surge');
require_once ('../Controller.php');

@$_GET['List']!==NULL?$Info['Lists']=$_GET['List']:$Info['Lists']=$EngineInfo['List'];
@$Rules->getRuleListInfo($Info);
@$Auth->generateAuthKey();

echo $Rules->ruleReplace('Surge',$RuleLists['General']);
echo "dns-server = 8.8.8.8, 8.8.4.4\r\n";
echo "#  \r\n";
echo "# Surge Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";
echo "[Proxy]\r\n";
echo "🇨🇳 = custom,172.0.0.1,80,aes-256-cfb,Password,".$EngineInfo['Module']."\r\n";
echo "🇳🇫 = custom,172.0.0.1,80,aes-256-cfb,Password,".$EngineInfo['Module']."\r\n";
echo "🇬🇧 = custom,172.0.0.1,80,aes-256-cfb,Password,".$EngineInfo['Module']."\r\n";
echo "[Proxy Group]\r\n";
echo "Proxy = select, 🇨🇳, 🇳🇫, 🇬🇧\r\n";

echo $Rules->ruleReplace('Surge',$RuleLists['Apple'],'DIRECT');
echo $Rules->ruleReplace('Surge',$RuleLists['Advanced'],'Proxy');
echo $Rules->ruleReplace('Surge',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Surge',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Surge',$RuleLists['KEYWORD'],'Proxy');
echo $Rules->ruleReplace('Surge',$RuleLists['IPCIDR'],'Proxy');
echo $Rules->ruleReplace('Surge',$RuleLists['Other'],'Proxy');
echo $Rules->ruleReplace('Surge',$RuleLists['Host']);
echo $Rules->ruleReplace('Surge',$RuleLists['Rewrite'],null,AUTHKEY);
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($EngineInfo['Example']),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
echo $ProxyType->getMitmInfo($CONFIGURATION,$RuleLists['MITM']);

?>