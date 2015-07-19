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

class Dc_Translator_Helper_Bing_Request extends Mage_Core_Helper_Abstract
{

    /**
     * @param $url
     * @param $authHeader
     * @param string $post_data
     * @return mixed
     * @throws Exception
     */
    public function curlRequest($url, $authHeader, $post_data='')
    {
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array($authHeader,'Content-Type: text/xml'));
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, False);
        if($post_data) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        }
        $curl_response = curl_exec($curl);
        $curl_errno = curl_errno($curl);
        if ($curl_errno) {
            $curl_error = curl_error($curl);
            throw new Exception($curl_error);
        }
        curl_close($curl);
        return $curl_response;
    }

    /**
     * @param $language_code
     * @return string
     * @throws Exception
     */
    public function createReqXML($language_code)
    {
        $request_xml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
        if($language_code) {
            $request_xml .= "<string>$language_code</string>";
        } else {
            throw new Exception('Language Code is empty.');
        }
        $request_xml .= '</ArrayOfstring>';
        return $request_xml;
    }

}
