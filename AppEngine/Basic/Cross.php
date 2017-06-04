<?php

header('Content-Disposition: attachment; filename='.'Cross.Conf');
require_once ('../Controller.php');

@$_GET['List']!==NULL?$List=$_GET['List']:$List=$EngineInfo['List'];
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($List),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
@$Auth->generateAuthKey();

echo "#  \r\n";
echo "# Cross Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";
echo "[PROXY]\r\n";
echo '🇨🇳 = 172.0.0.1, 80, aes-256-cfb, Password, upstream-proxy=false, upstream-proxy-auth=false, obfs-http=false'."\r\n";
echo "[Proxy Group]\r\n";
echo "Proxy = default=DIRECT, cellular=🇨🇳\r\n";
echo "[DNS]\r\n";
echo "8.8.8.8, 8.8.4.4\r\n";

echo "[RULE]\r\n";
echo $Rules->ruleReplace('Cross',$RuleLists['Apple'],'DIRECT');
echo $Rules->ruleReplace('Cross',$RuleLists['Advanced'],'PROXY');
echo $Rules->ruleReplace('Cross',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Cross',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Cross',$RuleLists['KEYWORD'],'PROXY');
echo $Rules->ruleReplace('Cross',$RuleLists['URLREGEX'],'PROXY');
echo $Rules->ruleReplace('Cross',$RuleLists['USERAGENT'],'PROXY');
echo $Rules->ruleReplace('Cross',$RuleLists['IPCIDR'],'PROXY');
echo $Rules->ruleReplace('Cross',$RuleLists['Other'],'Proxy');
echo "[HOST]\r\n";
echo $Rules->ruleReplace('Cross',$RuleLists['Host'],'PROXY');
echo "[URL REWRITE]\r\n";
echo $Rules->ruleReplace('Cross',$RuleLists['Rewrite'],null,AUTHKEY);
echo $ProxyType->getMitmInfo($CONFIGURATION,$RuleLists['MITM']);

?>