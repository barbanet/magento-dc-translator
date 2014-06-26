<?php
/**
 * Dc_Translator
 * 
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_Translator
 * @copyright  Copyright (c) 2014 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version    1.0.0
 */

class Dc_Translator_Helper_Bing_Translator extends Mage_Core_Helper_Abstract
{

    public function translate($text, $from = 'en', $to = 'es') {
        try {
            $clientId     = Mage::helper('core')->decrypt(Mage::getStoreConfig('translator/bing/client_id'));
            $clientSecret = Mage::helper('core')->decrypt(Mage::getStoreConfig('translator/bing/client_secret'));
            $authUrl      = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
            $scopeUrl     = "http://api.microsofttranslator.com";
            $grantType    = "client_credentials";
            $authObj = Mage::helper('translator/bing_authentication');
            $accessToken  = $authObj->getTokens($grantType, $scopeUrl, $clientId, $clientSecret, $authUrl);
            $authHeader = "Authorization: Bearer ". $accessToken;
            $translatorObj = Mage::helper('translator/bing_request');
            //TODO: sloppy. Tried with urlencode but it doesn't work neither.
            $text = str_replace(' ', '+', $text);
            $inputStr = $this->sanitize4Bing(base64_decode($text));
            $translateMethodUrl = "http://api.microsofttranslator.com/V2/Http.svc/Translate?text=".urlencode($inputStr)."&from=" . $from . "&to=" . $to . "";
            $curlResponse = $translatorObj->curlRequest($translateMethodUrl, $authHeader);
            $xmlObj =  new SimpleXMLElement($curlResponse);
            return $this->sanitize4Magento((string)$xmlObj);
        } catch (Exception $e) {
            Mage::logException($e);
            throw new Exception($e->getMessage());
        }
    }
    
    private function sanitize4Bing($string) {
        if ($string) {
            $string = str_replace('""', '"', $string);
        }
        return $string;
    }
    
    private function sanitize4Magento($string) {
        if ($string) {
            $string = str_replace('"', '""', $string);
        }
        return $string;
    }

}
