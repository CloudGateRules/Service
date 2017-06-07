<?php

header('Content-Disposition: attachment; filename='.'Shadowrocket');
require_once "../Controller.php";

@parse_str($Modules->GET());
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($JSON),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
@$Auth->generateAuthKey();

echo $Rules->ruleReplace('Shadowrocket',$RuleLists['General']);
echo $ProxyType->getArgumentsInfo('Shadowrocket',$CONFIGURATION);
echo "#  \r\n";
echo "# Shadowrocket Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";

@$CONFIGURATION['General']['Rule']===true?$Proxy='Basic':$Proxy='Advanced';

echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Apple'],$CONFIGURATION['SUFFIX']['Apple']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists[$Proxy],$CONFIGURATION['SUFFIX']['Proxy']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['KEYWORD'],$CONFIGURATION['SUFFIX']['KEYWORD']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['IPCIDR'],$CONFIGURATION['SUFFIX']['IPCIDR']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Other'],$CONFIGURATION['SUFFIX']['Other']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Host']);
echo $Rules->ruleReplace('Shadowrocket',$RuleLists['Rewrite'],null,AUTHKEY);
echo $ProxyType->getMitmInfo($CONFIGURATION,$RuleLists['MITM']);

?>