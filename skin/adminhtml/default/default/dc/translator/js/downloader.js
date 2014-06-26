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

var DcDownloader = Class.create();
DcDownloader.prototype = {
    initialize: function(url, locale) {
        this.url = url;
        this.locale = locale;
        this.source = $('downloadable');
        this.files = null;
    },
    getFiles: function() {
        var checkedList = new Array();
        $$("input:checkbox[name='downloadable'][checked]").each(function(element, index) {
            if (element.checked == true) {
                checkedList.push(element.value)
            }
        });
        this.files = checkedList;
    },
    download: function() {
        this.getFiles();
        this.getRequest();
    },
    getRequest: function() {
        var parameters = 'files/' + this.files + '/locale/' + this.locale; 
        setLocation(this.url + parameters);
    },
    selectAll: function () {
        $$('input[name=downloadable]').each(function(check) {
            check.checked = true;
        });
    },
    unselectAll: function () {
        $$('input[name=downloadable]').each(function(check) {
            check.checked = false;
        });
    }
};
