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
 * @copyright  Copyright (c) 2012-2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_Translator_Helper_Bing_Translator extends Mage_Core_Helper_Abstract
{

    /**
     * Translates a given text using Bing.
     *
     * @param $text
     * @param string $from
     * @param string $to
     * @return mixed
     * @throws Exception
     */
    public function translate($text, $from = 'en', $to = 'es')
    {
        try {
            $client_id = Mage::helper('core')->decrypt(Mage::getStoreConfig('translator/bing/client_id'));
            $client_secret = Mage::helper('core')->decrypt(Mage::getStoreConfig('translator/bing/client_secret'));
            $auth_url = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/';
            $scope_url = 'http://api.microsofttranslator.com';
            $grant_type = 'client_credentials';
            $auth_obj = Mage::helper('translator/bing_authentication');
            $access_token = $auth_obj->getTokens($grant_type, $scope_url, $client_id, $client_secret, $auth_url);
            $auth_header = "Authorization: Bearer ". $access_token;
            $translator_obj = Mage::helper('translator/bing_request');
            //TODO: sloppy. Tried with urlencode but it doesn't work neither.
            $text = str_replace(' ', '+', $text);
            $input_str = $this->sanitize4Bing(base64_decode($text));
            $translate_method_url = 'http://api.microsofttranslator.com/V2/Http.svc/Translate?text=' . urlencode($input_str) . '&from=' . $from . '&to=' . $to;
            $curl_response = $translator_obj->curlRequest($translate_method_url, $auth_header);
            $xml_obj =  new SimpleXMLElement($curl_response);
            return $this->sanitize4Magento((string)$xml_obj);
        } catch (Exception $e) {
            Mage::logException($e);
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Sanitize the result to be used into Bing.
     *
     * @param $string
     * @return mixed
     */
    private function sanitize4Bing($string)
    {
        if ($string) {
            $string = str_replace('""', '"', $string);
        }
        return $string;
    }

    /**
     * Sanitize the result to be used into Magento.
     *
     * @param $string
     * @return mixed
     */
    private function sanitize4Magento($string)
    {
        if ($string) {
            $string = str_replace('"', '""', $string);
        }
        return $string;
    }

}
