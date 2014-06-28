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
 * @version    1.0.1
 */

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('translator/package')};
CREATE TABLE {$this->getTable('translator/package')} (
    package_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    locale CHAR(5) NOT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (package_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('translator/key')};
CREATE TABLE {$this->getTable('translator/key')} (
    key_id int(11) unsigned NOT NULL auto_increment,
    package_id int(11) unsigned NOT NULL,
    package_module VARCHAR(255) DEFAULT NULL,
    package_key TEXT NOT NULL,
    package_value TEXT,
    created_at datetime default NULL,
    updated_at datetime default NULL,
    PRIMARY KEY (key_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('translator/key')} ADD CONSTRAINT FK_TRANSLATOR_KEY_PACKAGE_ID FOREIGN KEY (package_id) REFERENCES {$installer->getTable('translator/package')} (package_id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE {$this->getTable('translator/key')} ADD INDEX IDX_TRANSLATOR_KEY(package_module);

DROP TABLE IF EXISTS {$this->getTable('translator/file')};
CREATE TABLE {$this->getTable('translator/file')} (
    file_id int(11) unsigned NOT NULL auto_increment,
    package_module VARCHAR(255) default NULL,
    file_name VARCHAR(255) default NULL,
    PRIMARY KEY (file_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('translator/file')} ADD INDEX IDX_TRANSLATOR_FILE(package_module);

");

$installer->endSetup();
