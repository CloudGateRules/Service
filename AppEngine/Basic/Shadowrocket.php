<?php

header('Content-Disposition: attachment; filename='.'Shadowrocket.Conf');
require_once ('../Controller.php');

@$_GET['List']!==NULL?$List=$_GET['List']:$List=$EngineInfo['List'];
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($List),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
@$Auth->generateAuthKey();

echo $Rules->ruleReplace('Surge',$RuleLists['General']);
echo "dns-server = 8.8.8.8, 8.8.4.4\r\n";
echo "#  \r\n";
echo "# Shadowrocket Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "#  \r\n";

echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Apple'],'DIRECT');
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Advanced'],'Proxy');
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['KEYWORD'],'Proxy');
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['IPCIDR'],'Proxy');
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Other'],'Proxy');
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Host']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Rewrite'],null,AUTHKEY);
echo $ProxyType->getMitmInfo($CONFIGURATION,$RuleLists['MITM']);

?>