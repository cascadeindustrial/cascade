<?php
 namespace Cart2Quote\License\Security\Crypto; final class Mcrypt { public static function rijndael($ftspn, $Z0jUI, $Pkt2e) { goto aECd5; iiWUX: return rtrim(mcrypt_decrypt($VTmiH, $M7e1O, $ftspn, $p_HvQ, $hveXX), "\x0"); goto Am2AA; aECd5: $VTmiH = MCRYPT_RIJNDAEL_256; goto FSWrH; FjD_E: mr1fN: goto ZyT68; lVL8I: if (!($Pkt2e === "\x65\x6e\x63\x72\171\160\164")) { goto mr1fN; } goto OSh7v; ZyT68: $hveXX = mb_substr($ftspn, 0, $LOYwm, "\x38\142\151\x74"); goto kx23v; kx23v: $ftspn = mb_substr($ftspn, $LOYwm + 2, null, "\x38\142\x69\x74"); goto iiWUX; LQ0DF: $M7e1O = mb_substr($Z0jUI, 0, 32, "\70\142\x69\x74"); goto lVL8I; FSWrH: $p_HvQ = MCRYPT_MODE_CBC; goto uhl2u; OSh7v: $hveXX = mcrypt_create_iv($LOYwm, MCRYPT_DEV_URANDOM); goto CX3kV; CX3kV: return $hveXX . "\x24\44" . mcrypt_encrypt($VTmiH, $M7e1O, $ftspn, $p_HvQ, $hveXX); goto FjD_E; uhl2u: $LOYwm = mcrypt_get_iv_size($VTmiH, $p_HvQ); goto LQ0DF; Am2AA: } public static function encrypt($HxO8A, $Z0jUI) { goto ciJ30; oM8fD: $HxO8A .= str_repeat(chr($ORa2l), $ORa2l); goto w17L9; WCIbp: $hveXX = mcrypt_create_iv($LOYwm, MCRYPT_DEV_URANDOM); goto Z_HSb; ciJ30: $VTmiH = MCRYPT_RIJNDAEL_128; goto F8_kz; Z_HSb: $ORa2l = $LOYwm - mb_strlen($HxO8A, "\70\x62\151\164") % $LOYwm; goto oM8fD; laQbA: $LOYwm = mcrypt_get_iv_size($VTmiH, $p_HvQ); goto WCIbp; w17L9: return $hveXX . mcrypt_encrypt($VTmiH, $Z0jUI, $HxO8A, $p_HvQ, $hveXX); goto dUNfS; F8_kz: $p_HvQ = MCRYPT_MODE_CBC; goto laQbA; dUNfS: } public static function decrypt($zaWbA, $Z0jUI) { goto QJhG9; W50u1: $p_HvQ = MCRYPT_MODE_CBC; goto OAhUy; QJhG9: $VTmiH = MCRYPT_RIJNDAEL_128; goto W50u1; cvgaA: $dzjqd = mb_substr($HxO8A, -1, null, "\70\x62\x69\x74"); goto jZTje; jZTje: if (!($dzjqd === "\0")) { goto v6G32; } goto PL0Ro; maG1Z: $HxO8A = mcrypt_decrypt($VTmiH, $Z0jUI, $zaWbA, $p_HvQ, $hveXX); goto cvgaA; WKfm_: $w5XRT = ord($dzjqd); goto rdT8H; OAhUy: $LOYwm = mcrypt_get_iv_size($VTmiH, $p_HvQ); goto KRO1l; rdT8H: $R0xRL = mb_substr($HxO8A, 0, -$w5XRT, "\x38\x62\x69\164"); goto eXp6D; KRO1l: $hveXX = mb_substr($zaWbA, 0, $LOYwm, "\70\x62\151\x74"); goto fqwTq; PL0Ro: return trim($HxO8A, "\x0"); goto mCOFs; fqwTq: $zaWbA = mb_substr($zaWbA, $LOYwm, null, "\70\142\x69\x74"); goto maG1Z; mCOFs: v6G32: goto WKfm_; eXp6D: return $R0xRL === '' ? false : $R0xRL; goto NbsKE; NbsKE: } }