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

class Dc_Translator_Helper_Bing_Locale extends Mage_Core_Helper_Abstract
{

    protected $_supported_locales = array(
                                        'ar_DZ' => 'ar',
                                        'ar_EG' => 'ar',
                                        'ar_KW' => 'ar',
                                        'ar_MA' => 'ar',
                                        'ar_SA' => 'ar',
                                        'bg_BG' => 'bg',
                                        'ca_ES' => 'ca',
                                        'zh_CN' => 'zh-CHS',
                                        'zh_HK' => 'zh-CHS',
                                        'zh_TW' => 'zh-CHS',
                                        'cs_CZ' => 'cs',
                                        'da_DK' => 'da',
                                        'nl_NL' => 'nl',
                                        'nl_NL' => 'nl',
                                        'en_AU' => 'en',
                                        'en_CA' => 'en',
                                        'en_IE' => 'en',
                                        'en_NZ' => 'en',
                                        'en_GB' => 'en',
                                        'en_US' => 'en',
                                        'et_EE' => 'et',
                                        'fi_FI' => 'fi',
                                        'fr_CA' => 'fr',
                                        'fr_FR' => 'fr',
                                        'de_AT' => 'de',
                                        'de_DE' => 'de',
                                        'de_CH' => 'de',
                                        'el_GR' => 'el',
                                        'he_IL' => 'he',
                                        'hi_IN' => 'hi',
                                        'hu_HU' => 'hu',
                                        'id_ID' => 'id',
                                        'it_IT' => 'it',
                                        'it_CH' => 'it',
                                        'ja_JP' => 'ja',
                                        'ko_KR' => 'ko',
                                        'lv_LV' => 'lv',
                                        'lt_LT' => 'lt',
                                        'ms_MY' => 'ms',
                                        'nb_NO' => 'no',
                                        'nn_NO' => 'no',
                                        'fa_IR' => 'fa',
                                        'pl_PL' => 'pl',
                                        'pt_BR' => 'pt',
                                        'pt_PT' => 'pt',
                                        'ro_RO' => 'ro',
                                        'ru_RU' => 'ru',
                                        'sk_SK' => 'sk',
                                        'sl_SI' => 'sl',
                                        'es_AR' => 'es',
                                        'es_CL' => 'es',
                                        'es_CO' => 'es',
                                        'es_CR' => 'es',
                                        'es_MX' => 'es',
                                        'es_PA' => 'es',
                                        'es_PE' => 'es',
                                        'es_ES' => 'es',
                                        'es_VE' => 'es',
                                        'sv_SE' => 'sv',
                                        'th_TH' => 'th',
                                        'tr_TR' => 'tr',
                                        'uk_UA' => 'uk',
                                        'vi_VN' => 'vi'
                                    );

    public function getBingLocale($locale) {
        if (array_key_exists($locale, $this->_supported_locales)) {
            return $this->_supported_locales[$locale];
        }
        return false;
    }
    
    public function getBingLocales() {
        $_bing_locales = array();
        $_locales = Mage::getSingleton('core/locale')->getOptionLocales();
        foreach ($_locales as $_locale) {
            if (array_key_exists($_locale['value'], $this->_supported_locales)) {
                $_bing_locales[$_locale['value']] = $_locale['label'];
            }
        }
        return $_bing_locales;
    }
    
    public function validateByPackageId($package_id) {
        $_package = Mage::getModel('translator/package')->load($package_id);
        if ($_package) {
            $_bing_locale = $this->getBingLocale($_package->getLocale());
            if ($_bing_locale) {
                return $_bing_locale;
            }
        }
        return false;
    }

}
