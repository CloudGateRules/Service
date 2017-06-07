<?php

header('Content-Disposition: attachment; filename='.'Hosts');
require_once ('../Controller.php');

@$_GET['List']!==NULL?$Info['Lists']=$_GET['List']:$Info['Lists']=$EngineInfo['List'];
@$Rules->getRuleListInfo($Info);
@$Rules->getHostsInfo($EngineInfo['Hosts']);
@$Auth->generateAuthKey();

echo '#!MANAGED-CONFIG '.$EngineInfo['Host'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].' interval=86400'."\r\n";
echo $Rules->ruleReplace('Surge',$RuleLists['General']);
echo "dns-server = 8.8.8.8, 8.8.4.4\r\n";
echo "#  \r\n";
echo "# Hosts Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";

echo $Rules->ruleReplace('Surge',$RuleLists['Apple'],'DIRECT');
echo $Rules->ruleReplace('Surge',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Surge',$RuleLists['KEYWORD'],'DIRECT');
echo $Rules->ruleReplace('Surge',$RuleLists['IPCIDR'],'DIRECT');
echo $Rules->ruleReplace('Surge',$RuleLists['Other'],'DIRECT');
echo $Rules->ruleReplace('Surge',$RuleLists['Host']);
echo $Rules->ruleReplace('Hosts',$HostsList['Interface']);
echo $Rules->ruleReplace('YouTube',$RuleLists['YouTube'],null,null,$HostsList['YouTubeIP']);
echo $Rules->ruleReplace('Surge',$RuleLists['Rewrite'],null,AUTHKEY);
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($EngineInfo['Example']),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
echo $ProxyType->getMitmInfo($CONFIGURATION,$RuleLists['MITM']);

?>