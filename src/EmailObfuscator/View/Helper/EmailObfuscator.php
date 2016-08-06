<?php

namespace MBtecZfEmailObfuscator\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class        EmailObfuscator
 * @package     MBtecZfEmailObfuscator\View\Helper
 * @author      Matthias Büsing <info@mb-tec.eu>
 * @copyright   2016 Matthias Büsing
 * @license     GNU General Public License
 * @link        http://mb-tec.eu
 */
class EmailObfuscator extends AbstractHelper
{
    /**
     * @param       $sEmail
     * @param array $aParams
     *
     * @return string
     */
    public function __invoke($sEmail, array $aParams = [])
    {
        return $this->_getObfuscatedEmailLink($sEmail, $aParams);
    }

    /**
     * @param       $sEmail
     * @param array $aParams
     *
     * @return string
     */
    protected function _getObfuscatedEmailLink($sEmail, array $aParams = [])
    {
        // Tell search engines to ignore obfuscated uri
        if (!isset($aParams['rel'])) {
            $aParams['rel'] = 'nofollow';
        }

        $aNeverEncode = ['.', '@', '+']; // Don't encode those as not fully supported by IE & Chrome

        $sUrlEncodedEmail = '';
        for ($i = 0; $i < strlen($sEmail); $i++) {
            // Encode 25% of characters
            if (!in_array($sEmail[$i], $aNeverEncode) && mt_rand(1, 100) < 25) {
                $sCharCode = ord($aNeverEncode[$i]);
                $sUrlEncodedEmail .= '%';
                $sUrlEncodedEmail .= dechex(($sCharCode >> 4) & 0xF);
                $sUrlEncodedEmail .= dechex($sCharCode & 0xF);
            } else {
                $sUrlEncodedEmail .= $sEmail[$i];
            }
        }

        $sObfuscatedEmail = $this->_getObfuscatedEmailAddress($sEmail);
        $sObfuscatedEmailUrl = $this->_getObfuscatedEmailAddress('mailto:' . $sUrlEncodedEmail);

        $sLink = '<a href="' . $sObfuscatedEmailUrl . '"';
        foreach ($aParams as $mParam => $mValue) {
            $sLink .= ' ' . $mParam . '="' . htmlspecialchars($mValue). '"';
        }
        $sLink .= '>' . $sObfuscatedEmail . '</a>';

        return $sLink;
    }

    /**
     * @param $sEmail
     *
     * @return string
     */
    protected function _getObfuscatedEmailAddress($sEmail)
    {
        $aAlwaysEncode = ['.', ':', '@'];
        $sResult = '';

        // Encode string using oct and hex character codes
        for ($i = 0; $i < strlen($sEmail); $i++) {
            // Encode 25% of characters including several that always should be encoded
            if (in_array($sEmail[$i], $aAlwaysEncode) || mt_rand(1, 100) < 25) {
                if (mt_rand(0, 1)) {
                    $sResult .= '&#' . ord($sEmail[$i]) . ';';
                } else {
                    $sResult .= '&#x' . dechex(ord($sEmail[$i])) . ';';
                }
            } else {
                $sResult .= $sEmail[$i];
            }
        }

        return $sResult;
    }
}