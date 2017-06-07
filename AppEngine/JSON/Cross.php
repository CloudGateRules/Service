<?php

header('Content-Disposition: attachment; filename='.'Cross.Conf');
require_once "../Controller.php";

@parse_str($Modules->GET());
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($JSON),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
@$Auth->generateAuthKey();

echo "#  \r\n";
echo "# Cross Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";
echo @$ProxyType->getServerInfo('Cross',$CONFIGURATION);
echo @$ProxyType->getGroupInfo('Cross',$CONFIGURATION);
echo "[DNS]\r\n";
echo @$ProxyType->getArgumentsInfo('Cross',$CONFIGURATION);

@$CONFIGURATION['General']['Rule']===true?$Proxy='Basic':$Proxy='Advanced';
@$CONFIGURATION['General']['USERAGENT']===true?$USERAGENT='USERAGENT':$USERAGENT='SKIP';
@$CONFIGURATION['General']['URLREGEX']===true?$URLREGEX='URLREGEX':$URLREGEX='SKIP';

echo "[RULE]\r\n";
echo $Rules->ruleReplace('Cross',$RuleLists['Apple'],$CONFIGURATION['SUFFIX']['Apple']);
echo $Rules->ruleReplace('Cross',$RuleLists[$Proxy],$CONFIGURATION['SUFFIX']['Proxy']);
echo $Rules->ruleReplace('Cross',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Cross',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Cross',$RuleLists['KEYWORD'],$CONFIGURATION['SUFFIX']['KEYWORD']);
echo $Rules->ruleReplace('Cross',$RuleLists[$URLREGEX],$CONFIGURATION['SUFFIX']['URLREGEX']);
echo $Rules->ruleReplace('Cross',$RuleLists[$USERAGENT],$CONFIGURATION['SUFFIX']['USERAGENT']);
echo $Rules->ruleReplace('Cross',$RuleLists['IPCIDR'],$CONFIGURATION['SUFFIX']['IPCIDR']);
echo $Rules->ruleReplace('Cross',$RuleLists['Other'],$CONFIGURATION['SUFFIX']['Other']);
echo "[HOST]\r\n";
echo $Rules->ruleReplace('Cross',$RuleLists['Host']);
echo "[URL REWRITE]\r\n";
echo $Rules->ruleReplace('Cross',$RuleLists['Rewrite'],null,AUTHKEY);
echo $ProxyType->getMitmInfo($CONFIGURATION,$RuleLists['MITM']);

?>