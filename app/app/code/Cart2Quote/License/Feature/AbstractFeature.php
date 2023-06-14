<?php
 namespace Cart2Quote\License\Feature; abstract class AbstractFeature { private static $allowedInstanceRequesters = array(\Cart2Quote\Features\Feature\FeatureList::class, \Cart2Quote\License\Model\License::class); protected $license; protected $lite; protected $starter; protected $business; protected $enterprise; protected $trial; protected $unreachable; protected $opensource; protected $corporate; protected $configs = array(); protected $plugins = array(); protected $defaultAllowedStates = array(\Cart2Quote\License\Model\License::ACTIVE_STATE, \Cart2Quote\License\Model\License::PENDING_STATE, \Cart2Quote\License\Model\License::UNREACHABLE); private function __construct() { $this->init(); } public static final function getInstance($HrnWx) { goto pq7gj; aH8g4: return $ggadi[$vit9q]; goto O_oai; nXEWd: $vit9q = get_called_class(); goto ndy41; gPLxf: goto QuGss; goto PBTCt; i_2tl: return null; goto gPLxf; PBTCt: q5iz_: goto HYACT; ndy41: if (isset($ggadi[$vit9q])) { goto LLG7M; } goto S1my1; HYACT: $ggadi[$vit9q] = new $vit9q(); goto bNaqW; bNaqW: QuGss: goto kA7i9; kA7i9: LLG7M: goto aH8g4; pq7gj: static $ggadi = array(); goto nXEWd; S1my1: if (in_array(get_class($HrnWx), self::$allowedInstanceRequesters)) { goto q5iz_; } goto i_2tl; O_oai: } public final function isAllowed() { return $this->isAllowedForState() && $this->isAllowedForType(); } private final function isAllowedForState() { return in_array(\Cart2Quote\License\Model\License::getInstance()->getLicenseState(), $this->allowedStates()); } protected abstract function allowedStates(); private final function isAllowedForType() { $wcnaz = \Cart2Quote\License\Model\License::getInstance()->getEdition(); return $this->getEditionsLevel($wcnaz) >= $this->allowedEdition(); } public final function isAllowedForEdition($wcnaz = "\154\151\x74\145") { $eUjdm = \Cart2Quote\License\Model\License::getInstance()->getEdition(); return $this->getEditionsLevel($eUjdm) >= $this->getEditionsLevel($wcnaz); } private final function getEditionsLevel($wcnaz) { goto ac57D; TMiB0: return $this->{$wcnaz}; goto jsnPB; ac57D: if (!isset($this->{$wcnaz})) { goto jVfsb; } goto TMiB0; jsnPB: jVfsb: goto G3r_L; G3r_L: return 0; goto UwJwN; UwJwN: } protected abstract function allowedEdition(); public final function getPlugins() { goto kWBaz; rHaKj: return $this->plugins; goto xp9Qb; xd0tp: pC5tb: goto rHaKj; kWBaz: foreach (array_keys($this->plugins) as $r7bSw) { goto AYkjT; G1SjZ: LeaZR: goto sd0WY; AYkjT: foreach ($this->plugins[$r7bSw]["\x70\154\165\147\x69\x6e\163"] as $qboJc => $sB2Dt) { goto wBk5y; E6ob4: LmnQI: goto XRgY5; kgbSm: $this->plugins[$r7bSw]["\x70\x6c\165\x67\x69\156\x73"][$IqtfW->getRandomString(12)] = $sB2Dt; goto Yag8c; wBk5y: $IqtfW = new \Magento\Framework\Math\Random(); goto kgbSm; Yag8c: unset($this->plugins[$r7bSw]["\x70\154\165\x67\x69\x6e\163"][$qboJc]); goto E6ob4; XRgY5: } goto G1SjZ; sd0WY: wlszj: goto Fu3cP; Fu3cP: } goto xd0tp; xp9Qb: } public final function getConfigs() { return $this->configs; } private final function init() { goto ebd76; bnWSU: AC_os: goto mdlVi; ebd76: $hxsMG = ["\154\x69\164\x65" => 0, "\x73\164\141\162\164\x65\162" => 5, "\x62\165\163\x69\156\x65\x73\163" => 10, "\x65\x6e\164\145\162\x70\162\151\x73\145" => 20, "\164\x72\x69\x61\154" => 20, "\165\x6e\162\145\x61\143\x68\x61\142\154\x65" => 20, "\157\x70\x65\156\163\x6f\165\162\143\145" => 40, "\143\157\162\x70\x6f\x72\x61\x74\145" => 50]; goto sczcF; sczcF: foreach ($hxsMG as $wcnaz => $GAqPY) { $this->{$wcnaz} = $GAqPY; kdYc9: } goto bnWSU; mdlVi: } }