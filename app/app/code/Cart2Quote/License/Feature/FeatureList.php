<?php
 namespace Cart2Quote\License\Feature; abstract class FeatureList { const GREATER_THAN = "\x3e"; const SMALLER_THAN = "\x3c"; const GREATER_THAN_OR_EQUAL = "\x3e\x3d"; const SMALLER_THAN_OR_EQUAL = "\74\75"; private static $SrC9O = "\x43\141\x72\164\62\x51\165\x6f\164\145\x5c\x46\145\141\x74\165\x72\145\x73\134\106\x65\x61\164\x75\x72\145"; private static $g9ECT = array(\Magento\Framework\ObjectManager\Config\Reader\Dom::class, \Cart2Quote\License\Plugin\App\ConfigPlugin::class, \Cart2Quote\License\Plugin\Model\ConfigPlugin::class, \Cart2Quote\License\Model\License::class); private $JuHwn = array(); private $vuIxM = array(); private final function __construct() { $this->init(); $this->loadPlugins(); } private final function loadPlugins() { goto gTRCL; QDh5i: Dl_cd: goto hkPGK; gTRCL: $SjvCL = new \Magento\Framework\Math\Random(); goto DhLus; hkPGK: return $this->vuIxM; goto xMuBZ; DhLus: $this->vuIxM = ["\x4d\x61\x67\145\x6e\x74\157\134\x46\162\x61\x6d\x65\x77\x6f\x72\153\x5c\101\x70\160\134\x43\x6f\156\x66\x69\147" => ["\160\x6c\x75\x67\x69\x6e\163" => [$SjvCL->getRandomString(12) => ["\163\x6f\x72\164\117\x72\x64\145\x72" => 99999, "\151\x6e\x73\164\x61\156\143\x65" => \Cart2Quote\License\Plugin\App\ConfigPlugin::class]]], "\x4d\x61\147\145\156\164\157\x5c\x43\157\156\146\x69\x67\x5c\x4d\x6f\144\x65\x6c\x5c\x43\157\x6e\146\x69\147" => ["\160\154\x75\147\x69\156\x73" => [$SjvCL->getRandomString(12) => ["\x73\157\x72\x74\x4f\x72\144\x65\x72" => 1, "\x69\156\163\164\x61\x6e\143\145" => \Cart2Quote\License\Plugin\Model\ConfigPlugin::class]]]]; goto u_RGL; u_RGL: foreach ($this->JuHwn as $Gcb3T) { goto dP2Kg; dP2Kg: $l4W52 = $Gcb3T->getPlugins(); goto QzjXa; QzjXa: if (!is_array($l4W52)) { goto q8yPG; } goto cCMPr; hu4B2: q8yPG: goto SjS4E; cCMPr: $this->vuIxM = array_merge_recursive($this->vuIxM, $l4W52); goto hu4B2; SjS4E: nqtLc: goto ZWpa4; ZWpa4: } goto QDh5i; xMuBZ: } protected abstract function init(); public static final function getInstance($XYjK1) { goto nDGnm; nhRBo: return $rwqX_[$l9foa]; goto tqI9Q; ja5c_: t5U3U: goto Ps7fV; Ps7fV: M9Ew2: goto nhRBo; Sf1z6: aZ29X: goto i5jYw; zQc6e: foreach ($txeeP->getAllPlugins() as $mZGml) { goto lKXND; QHTbs: m3V4p: goto gqP6E; lKXND: foreach ($mZGml["\x70\154\x75\x67\151\156\163"] as $CBhbR) { goto uq0kK; OdgwA: M3FUF: goto Q7sJi; Q7sJi: Tvr0q: goto Ua_at; uq0kK: if (in_array($CBhbR["\151\156\x73\164\141\x6e\143\145"], self::$g9ECT)) { goto M3FUF; } goto mq1xm; mq1xm: self::$g9ECT[] = $CBhbR["\x69\156\x73\164\141\156\x63\145"]; goto OdgwA; Ua_at: } goto vx12f; vx12f: WsAjo: goto QHTbs; gqP6E: } goto cyGXX; cyGXX: gKJQc: goto svCwp; svCwp: if (in_array(get_class($XYjK1), self::$g9ECT)) { goto aZ29X; } goto XStPF; nDGnm: static $rwqX_ = array(); goto Zm73O; Zm73O: $l9foa = get_called_class(); goto cTKJu; i5jYw: $rwqX_[$l9foa] = $txeeP; goto ja5c_; IslpX: goto t5U3U; goto Sf1z6; cTKJu: if (isset($rwqX_[$l9foa])) { goto M9Ew2; } goto hNp59; XStPF: return null; goto IslpX; hNp59: $txeeP = new $l9foa(); goto zQc6e; tqI9Q: } public final function getFeature($gOyuU) { goto C6HrX; A_Lgi: Salzn: goto J8Vk0; C6HrX: if (isset($this->JuHwn[$gOyuU])) { goto Salzn; } goto fl6Tm; fl6Tm: return null; goto A_Lgi; J8Vk0: return $this->JuHwn[$gOyuU]; goto aHIPv; aHIPv: } public final function getFeatureByPlugin($kcV_B) { goto Jwh1v; MLwer: WHGpB: goto TBNSH; TBNSH: return null; goto IA2Sq; Jwh1v: foreach ($this->JuHwn as $Gcb3T) { goto ayiHm; ITZz1: foreach ($l4W52 as $mZGml) { goto YUubM; YUubM: foreach ($mZGml["\160\x6c\165\147\151\x6e\163"] as $CBhbR) { goto fINqB; HMxfP: return $Gcb3T; goto tnGlO; fINqB: if (!($CBhbR["\151\156\x73\x74\141\156\143\145"] == $kcV_B)) { goto pZ_BM; } goto HMxfP; tnGlO: pZ_BM: goto z0Qr_; z0Qr_: l7IlR: goto yLgV7; yLgV7: } goto PNw8a; wVRKK: VL9xu: goto NbpXt; PNw8a: ka6N8: goto wVRKK; NbpXt: } goto UT1cx; JFY6G: nG0tO: goto L_SVO; ayiHm: $l4W52 = $Gcb3T->getPlugins(); goto ITZz1; UT1cx: J54Fs: goto JFY6G; L_SVO: } goto MLwer; IA2Sq: } public final function getAllPlugins() { return $this->vuIxM; } public final function isConfigAllowed($kV00f, &$p4VKO, $HUs2e = false) { foreach ($this->JuHwn as $Gcb3T) { goto qMItR; wFE0K: if (!(in_array($p4VKO, $Vs0tx) || $this->compareValue($p4VKO, $Vs0tx))) { goto ChiJp; } goto nT2nz; Q_N52: $RhzPL = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Backend\App\Action\Context::class); goto rfQ2h; J8I1z: $p4VKO = $TxP9B[$kV00f]["\x72\x65\x76\145\x72\164\x56\x61\154\165\145"]; goto LDa7n; nT2nz: if ($Gcb3T->isAllowed()) { goto Br2Nc; } goto J8I1z; bHVFV: OB_30: goto LFPuh; Yg5eZ: ChiJp: goto bHVFV; LFPuh: Pr4nh: goto BEszR; qMItR: $TxP9B = $Gcb3T->getConfigs(); goto O8tzJ; O8tzJ: if (!array_key_exists($kV00f, $TxP9B)) { goto OB_30; } goto r9b33; LDa7n: if (!$HUs2e) { goto scgK3; } goto Q_N52; r6Wc0: scgK3: goto kvQGQ; r9b33: $Vs0tx = $TxP9B[$kV00f]["\x6e\x6f\x74\x41\x6c\154\x6f\x77\x65\x64\126\x61\154\x75\x65"]; goto wFE0K; rfQ2h: $RhzPL->getMessageManager()->addComplexErrorMessage("\154\x69\143\145\x6e\163\145\x4d\145\163\163\x61\147\145", ["\x6d\145\x73\163\141\x67\145" => $TxP9B[$kV00f]["\x6d\x65\163\x73\141\147\145"]]); goto r6Wc0; kvQGQ: Br2Nc: goto Yg5eZ; BEszR: } KVLWs: } protected final function add(\Cart2Quote\License\Feature\AbstractFeature $Gcb3T) { goto GF4TN; GF4TN: $gOyuU = get_class($Gcb3T); goto vrbJD; hm9sa: $this->JuHwn[$gOyuU] = $Gcb3T; goto Sd847; ud69Y: if (isset($this->JuHwn[$gOyuU])) { goto W8AR1; } goto hm9sa; IJk55: lHM01: goto Lx6eL; vrbJD: if (!(strpos($gOyuU, self::$SrC9O) !== false)) { goto lHM01; } goto ud69Y; Sd847: W8AR1: goto IJk55; Lx6eL: } private final function compareValue($p4VKO, $Vs0tx) { goto udmhE; veBFw: U7pzO: goto m4RAa; udmhE: foreach ($Vs0tx as $TfSRg => $TSiyq) { goto U2stP; y4KK4: Zv73B: goto tgITq; ew5Rj: QHA6K: goto y4KK4; RPqiA: switch ($TfSRg) { case self::SMALLER_THAN_OR_EQUAL: return $p4VKO <= $TSiyq; case self::SMALLER_THAN: return $p4VKO < $TSiyq; case self::GREATER_THAN_OR_EQUAL: return $p4VKO >= $TSiyq; case self::GREATER_THAN: return $p4VKO > $TSiyq; } goto BIcv2; BIcv2: dEKNm: goto Sunpr; U2stP: if (is_int($TfSRg)) { goto QHA6K; } goto eQPSv; Sunpr: R4X7L: goto ew5Rj; eQPSv: $p4VKO = (int) $p4VKO; goto RPqiA; tgITq: } goto veBFw; m4RAa: return false; goto D_ia3; D_ia3: } }