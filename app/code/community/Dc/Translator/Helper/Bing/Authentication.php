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

class Dc_Translator_Helper_Bing_Authentication extends Mage_Core_Helper_Abstract
{

    /**
     * @param $grant_type
     * @param $scope_url
     * @param $client_id
     * @param $client_secret
     * @param $auth_url
     * @return mixed
     * @throws Exception
     */
    public function getTokens($grant_type, $scope_url, $client_id, $client_secret, $auth_url)
    {
        try {
            $curl = curl_init();
            $params = array (
                 'grant_type'    => $grant_type,
                 'scope'         => $scope_url,
                 'client_id'     => $client_id,
                 'client_secret' => $client_secret
            );
            $params = http_build_query($params);
            curl_setopt($curl, CURLOPT_URL, $auth_url);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $string_response = curl_exec($curl);
            $curl_errno = curl_errno($curl);
            if($curl_errno){
                $curl_error = curl_error($curl);
                throw new Exception($curl_error);
            }
            curl_close($curl);
            $object_response = json_decode($string_response);
            if (isset($object_response->error)) {
                throw new Exception($object_response->error_description);
            }
            return $object_response->access_token;
        } catch (Exception $e) {
            Mage::logException($e);
            throw new Exception($e->getMessage());
        }
    }

}
