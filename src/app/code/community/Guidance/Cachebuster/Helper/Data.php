<?php
/**
 * NOTICE OF LICENSE
 *
 * Copyright 2012 Guidance Solutions
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @author     Gordon Knoppe
 * @category   Guidance
 * @package    Cachebuster
 * @copyright  Copyright (c) 2012 Guidance Solutions (http://www.guidance.com)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

class Guidance_Cachebuster_Helper_Data extends Mage_Core_Helper_Data
{

    const XML_PATH_IS_ENABLED = 'system/guidance_cachebuster/is_enabled';
    const XML_PATH_FILE_EXTENSIONS = 'system/guidance_cachebuster/file_extensions';
    const XML_PATH_FILE_URL_KEYS = 'system/guidance_cachebuster/url_keys';

    protected $_fileExtensions;

    public function isEnabled()
    {
        return Mage::getStoreConfig(self::XML_PATH_IS_ENABLED);
    }

    public function enabledFileExtensions()
    {
        if (is_null($this->_fileExtensions)) {
            $config = Mage::getStoreConfig(self::XML_PATH_FILE_EXTENSIONS);
            $this->_fileExtensions = array_map('trim', explode(',', $config));
        }
        return $this->_fileExtensions;
    }

    /**
     * @return Guidance_Cachebuster_Model_Parser
     */
    public function getParser()
    {
        $urlKeys= $this->_getUrlKeys();
        $urlMap = array();

        if (!empty($urlKeys)) {

            $urlKeys = explode(',', $urlKeys);

            foreach ($urlKeys as $urlKey) {
                $urlMap[Mage::getBaseUrl($urlKey)] = Mage::getBaseDir() . '/' . $urlKey . '/';
            }
        }

        /** @var Guidance_Cachebuster_Model_Parser $parser */
        $config = array(
            'urlMap'         => $urlMap,
            'fileExtensions' => $this->enabledFileExtensions()
        );
        $parser = Mage::getModel('guidance_cachebuster/parser', $config);
        return $parser;
    }

    protected function _getUrlKeys()
    {
        $urls = Mage::getStoreConfig(self::XML_PATH_FILE_URL_KEYS);
        return $urls;
    }

}