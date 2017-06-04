<?php

header('Content-Disposition: attachment; filename='.'Potatso2.Conf');
require_once "../Controller.php";

@$Modules->GET().parse_str($REQUEST_QUERY_URI);
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($JSON),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));

# Cloud配置信息
echo"# \r\n";
echo"# Potatso2 Config File [CloudGate]\r\n";
echo"# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo"#\r\n";

echo"[PROFILE.sample]\r\n";
echo @$ProxyType->getServerInfo('Potatso2',$CONFIGURATION);

$CONFIGURATION['General']['Rule']===true?$Proxy='Basic':$Proxy='Advanced';

# CloudGate模块
echo'[RULESET.CloudGate]'."\r\n";
echo'name = "CloudGate"'."\r\n";
echo'rules = ['."\r\n";
echo $Rules->ruleReplace('Potatso2',$RuleLists['Apple'],$CONFIGURATION['SUFFIX']['Apple']);
echo $Rules->ruleReplace('Potatso2',$RuleLists[$Proxy],$CONFIGURATION['SUFFIX']['Proxy']);
echo $Rules->ruleReplace('Potatso2',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Potatso2',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Potatso2',$RuleLists['KEYWORD'],$CONFIGURATION['SUFFIX']['KEYWORD']);
echo $Rules->ruleReplace('Potatso2',$RuleLists['IPCIDR'],$CONFIGURATION['SUFFIX']['IPCIDR']);
echo $Rules->ruleReplace('Potatso2',$RuleLists['Other'],$CONFIGURATION['SUFFIX']['Other']);
echo']'."\r\n";

?>