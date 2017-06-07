<?php

header('Content-Disposition: attachment; filename='.'Potatso2.Conf');
require_once ('../Controller.php');

@$_GET['List']!==NULL?$Info['Lists']=$_GET['List']:$Info['Lists']=$EngineInfo['List'];
@$Rules->getRuleListInfo($Info);
@$Auth->generateAuthKey();

echo"# \r\n";
echo"# Potatso2 Config File [CloudGate]\r\n";
echo"# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo"#\r\n";

echo'[RULESET.CloudGate]'."\r\n";
echo'name = "CloudGate"'."\r\n";
echo'rules = ['."\r\n";
echo $Rules->ruleReplace('Potatso2',$RuleLists['Apple'],'DIRECT');
echo $Rules->ruleReplace('Potatso2',$RuleLists['Advanced'],'Proxy');
echo $Rules->ruleReplace('Potatso2',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Potatso2',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Potatso2',$RuleLists['KEYWORD'],'Proxy');
echo $Rules->ruleReplace('Potatso2',$RuleLists['IPCIDR'],'Proxy');
echo $Rules->ruleReplace('Potatso2',$RuleLists['Other'],'Proxy');
echo']'."\r\n";

?>