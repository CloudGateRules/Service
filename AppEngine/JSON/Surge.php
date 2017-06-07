<?php

header('Content-Disposition: attachment; filename='.'Surge');
require_once "../Controller.php";

@parse_str($Modules->GET());
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($JSON),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
@$Auth->generateAuthKey();

echo "#!MANAGED-CONFIG {$EngineInfo['Host']}://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."\r\n";
echo $Rules->ruleReplace('Surge',$RuleLists['General']);
echo $ProxyType->getArgumentsInfo('Surge',$CONFIGURATION);
echo "#  \r\n";
echo "# Surge Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";
echo @$ProxyType->getServerInfo('Surge',$CONFIGURATION);
echo @$ProxyType->getGroupInfo('Surge',$CONFIGURATION);

@$CONFIGURATION['General']['Rule']===true?$Proxy='Basic':$Proxy='Advanced';
@$CONFIGURATION['General']['USERAGENT']===true?$USERAGENT='USERAGENT':$USERAGENT='SKIP';
@$CONFIGURATION['General']['URLREGEX']===true?$URLREGEX='URLREGEX':$URLREGEX='SKIP';
@$CONFIGURATION['General']['IPCIDR6']===true?$IPCIDR6='IPCIDR6':$IPCIDR6='SKIP';

echo $Rules->ruleReplace('Surge',$RuleLists['Apple'],$CONFIGURATION['SUFFIX']['Apple']);
echo $Rules->ruleReplace('Surge',$RuleLists[$Proxy],$CONFIGURATION['SUFFIX']['Proxy']);
echo $Rules->ruleReplace('Surge',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Surge',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Surge',$RuleLists['KEYWORD'],$CONFIGURATION['SUFFIX']['KEYWORD']);
echo $Rules->ruleReplace('Surge',$RuleLists[$URLREGEX],$CONFIGURATION['SUFFIX']['URLREGEX']);
echo $Rules->ruleReplace('Surge',$RuleLists[$USERAGENT],$CONFIGURATION['SUFFIX']['USERAGENT']);
echo $Rules->ruleReplace('Surge',$RuleLists[$IPCIDR6],$CONFIGURATION['SUFFIX']['IPCIDR6']);
echo $Rules->ruleReplace('Surge',$RuleLists['IPCIDR'],$CONFIGURATION['SUFFIX']['IPCIDR']);
echo $Rules->ruleReplace('Surge',$RuleLists['Other'],$CONFIGURATION['SUFFIX']['Other']);
echo $Rules->ruleReplace('Surge',$RuleLists['Host']);
echo $Rules->ruleReplace('Surge',$RuleLists['Rewrite'],null,AUTHKEY);
echo $ProxyType->getMitmInfo($CONFIGURATION,$RuleLists['MITM']);

?>