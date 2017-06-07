<?php

header('Content-Disposition: attachment; filename='.'Potatso.Conf');
require_once "../Controller.php";

@parse_str($Modules->GET());
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($JSON),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
@$Token->tokenGenerate();

echo "proxies:\r\n";
echo @$ProxyType->getServerInfo('Potatso',$CONFIGURATION);
echo "ruleSets:\r\n";
echo "#  \r\n";
echo "# Potatso Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "#  \r\n";
echo "- name: CloudGate\r\n";
echo "  rules:\r\n";

@$CONFIGURATION['General']['Rule']===true?$Proxy='Basic':$Proxy='Advanced';

echo $Rules->ruleReplace('Potatso',$RuleLists['Apple'],$CONFIGURATION['SUFFIX']['Apple']);
echo $Rules->ruleReplace('Potatso',$RuleLists[$Proxy],$CONFIGURATION['SUFFIX']['Proxy']);
echo $Rules->ruleReplace('Potatso',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Potatso',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Potatso',$RuleLists['KEYWORD'],$CONFIGURATION['SUFFIX']['KEYWORD']);
echo $Rules->ruleReplace('Potatso',$RuleLists['IPCIDR'],$CONFIGURATION['SUFFIX']['IPCIDR']);
echo $Rules->ruleReplace('Potatso',$RuleLists['Other'],$CONFIGURATION['SUFFIX']['Other']);

?>