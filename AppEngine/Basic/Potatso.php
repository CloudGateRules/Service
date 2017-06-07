<?php

header('Content-Disposition: attachment; filename='.'Potatso.Conf');
require_once ('../Controller.php');

@$_GET['List']!==NULL?$Info['Lists']=$_GET['List']:$Info['Lists']=$EngineInfo['List'];
@$Rules->getRuleListInfo($Info);
@$Auth->generateAuthKey();

echo"ruleSets:\r\n";
echo"# \r\n";
echo"# Potatso Config File [CloudGate]\r\n";
echo"# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo"#\r\n";
echo"- name: CloudGate\r\n";
echo"  rules: \r\n";

echo $Rules->ruleReplace('Potatso',$RuleLists['Apple'],'DIRECT');
echo $Rules->ruleReplace('Potatso',$RuleLists['Advanced'],'Proxy');
echo $Rules->ruleReplace('Potatso',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('Potatso',$RuleLists['REJECT']);
echo $Rules->ruleReplace('Potatso',$RuleLists['KEYWORD'],'Proxy');
echo $Rules->ruleReplace('Potatso',$RuleLists['IPCIDR'],'Proxy');
echo $Rules->ruleReplace('Potatso',$RuleLists['Other'],'Proxy');

?>