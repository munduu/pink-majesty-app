<?php
/**
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *https://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2007-2014 PagSeguro Internet Ltda.
 *  @license   https://www.apache.org/licenses/LICENSE-2.0
 */

/***
 * Class PagSeguroSessionnParser
 */
class PagSeguroSessionParser extends PagSeguroServiceParser
{

    /***
     * @param $str_xml
     * @return PagSeguroSession
     */
    public static function readResult($str_xml)
    {

        $parser = new PagSeguroXmlParser($str_xml);
        $data = $parser->getResult('session');

        $session = new PagSeguroSession();

        if (isset($data['id'])) {
            $session->setId($data['id']);
        }

        return $session;
    }
}
