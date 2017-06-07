<?php

header('Content-Disposition: attachment; filename='.'A.BIG.T.Conf');
require_once ('../Controller.php');

@$_GET['List']!==NULL?$Info['Lists']=$_GET['List']:$Info['Lists']=$EngineInfo['List'];
@$Rules->getRuleListInfo($Info);
@$Auth->generateAuthKey();

echo $Rules->ruleReplace('A.BIG.T',$RuleLists['General']);
echo "dns-server = 8.8.8.8, 8.8.4.4\r\n";
echo "#  \r\n";
echo "# A.BIG.T Config File [CloudGate]\r\n";
echo "# Download Time: " . date("Y-m-d H:i:s") . "\r\n";
echo "# \r\n";
echo "[Proxy]\r\n";
echo '🇨🇳 = custom,172.0.0.1,80,aes-256-cfb,Password'."\r\n";
echo '🇳🇫 = custom,172.0.0.1,80,aes-256-cfb,Password'."\r\n";
echo '🇬🇧 = custom,172.0.0.1,80,aes-256-cfb,Password'."\r\n";
echo "[Proxy Group]\r\n";
echo "Proxy = select, 🇨🇳, 🇳🇫, 🇬🇧\r\n";

echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Apple'],'DIRECT');
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Advanced'],'Proxy');
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['DIRECT']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['REJECT']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['KEYWORD'],'Proxy');
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['IPCIDR'],'Proxy');
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Other'],'Proxy');
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Host']);
echo $Rules->ruleReplace('A.BIG.T',$RuleLists['Rewrite'],null,AUTHKEY);

?>