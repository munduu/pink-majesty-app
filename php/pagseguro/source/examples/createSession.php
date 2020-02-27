<?php //

/*
 * ***********************************************************************
 Copyright [2014] [PagSeguro Internet Ltda.]

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 * ***********************************************************************
 */

require_once "../PagSeguroLibrary/PagSeguroLibrary.php";

/**
 * Class with a main method to illustrate the usage of the service PagSeguroSessionService
 */
class CreateSession
{

    public static function main()
    {
        try {

            /**
             * @todo
             * #### Credentials #####
             * Replace the parameters below with your credentials
             * You can also get your credentials from a config file. See an example:
             * $credentials = PagSeguroConfig::getAccountCredentials();
             */

            // seller authentication
            $credentials = new PagSeguroAccountCredentials("otaviollneto@gmail.com", "EA444B434FA748C1B3D0AE5F767BF2C4");

            // application authentication
            //$credentials = PagSeguroConfig::getApplicationCredentials();

            //$credentials->setAuthorizationCode("7AEE8D21D4E745FDAD635D97081312BB");

            $session = PagSeguroSessionService::getSession($credentials);

            self::printSession($session);

        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }

    public static function printSession($sessionID)
    {

        if ($sessionID) {
            echo "<h2>Session</h2>";
            echo "<p><strong>ID: </strong> ".$sessionID ."</p> ";
        }

      echo "<pre>";
    }
}

CreateSession::main();
