<?php

header('Content-Disposition: attachment; filename='.'A.BIG.T.Conf');
require_once "../Controller.php";

@parse_str($Modules->GET());
@$SubmitType->getSubmitInfoVerify($SubmitType->getSubmitInfoType($JSON),$EngineInfo);
@$Rules->getRuleListInfo($SubmitType->getSubmitInfoList($CONFIGURATION));
@$Auth->generateAuthKey();

echo $Rules->ruleReplace('A.BIG.T',$RuleLists['General']);
echo $ProxyType->getArgumentsInfo('A.BIG.T',$CONFIGURATION);
echo "#  \r\n";
echo "# A.BIG.T Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";
echo @$ProxyType->getServerInfo('A.BIG.T',$CONFIGURATION);
echo @$ProxyType->getGroupInfo('A.BIG.T',$CONFIGURATION);

@$CONFIGURATION['General']['Rule']===true?$Proxy='Basic':$Proxy='Advanced';

echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Apple'],$CONFIGURATION['SUFFIX']['Apple']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists[$Proxy],$CONFIGURATION['SUFFIX']['Proxy']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['REJECT']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['KEYWORD'],$CONFIGURATION['SUFFIX']['KEYWORD']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists[$USERAGENT],$CONFIGURATION['SUFFIX']['USERAGENT']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['IPCIDR'],$CONFIGURATION['SUFFIX']['IPCIDR']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Other'],$CONFIGURATION['SUFFIX']['Other']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Host']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Rewrite'],null,AUTHKEY);

?>