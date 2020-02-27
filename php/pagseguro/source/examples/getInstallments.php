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
 * Class with a main method to illustrate the usage of the service PagSeguroInstallmentService
 */
class GetInstallments
{

    public static function main()
    {

      $amount = 30.00; //Required
      $cardBrand = "visa"; //Optional
      $maxInstallmentNoInterest = 2; //Optional

       try {

           /**
            * #### Credentials #####
            * Replace the parameters below with your credentials
            * You can also get your credentials from a config file. See an example:
            * $credentials = new PagSeguroAccountCredentials("vendedor@lojamodelo.com.br",
            *   "E231B2C9BCC8474DA2E260B6C8CF60D3");
            */

           //$credentials = PagSeguroConfig::getAccountCredentials();
		   $credentials = new PagSeguroAccountCredentials("otaviollneto@gmail.com","1B170AF278854D43A2B3DEC8E1363950");

           // Application authentication
           //$credentials = PagSeguroConfig::getApplicationCredentials();
           //$credentials->setAuthorizationCode("E231B2C9BCC8474DA2E260B6C8CF60D3");

            $installments = PagSeguroInstallmentService::getInstallments(
                $credentials,
                $amount,
                $cardBrand,
                $maxInstallmentNoInterest
            );

            self::printInstallment($installments);

        } catch (Exception $e) {
            die($e->getMessage());
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }

    public static function printInstallment($installments)
    {

        if ($installments) {
			
            echo "<h2>Parcelamento</h2>";
            foreach ($installments->getInstallments() as $installment) {
				
				$jurosn = $installment->getInterestFree();
				if($jurosn==='false'){
					$juros = 'com juros';
				}else{
					$juros = 'sem juros';
				}
				
                echo "<p> <strong> Bandeira: </strong> ". $installment->getCardBrand()."<br> ";
                echo "<strong> Parcelas: </strong> ". $installment->getQuantity()." x ";
                echo "R$ ". $installment->getInstallmentAmount()." ".$juros."<br> ";
                echo "<strong> Total: </strong> R$ ". $installment->getTotalAmount()."<br> ";
                //echo "<strong> Parcelas Sem Juros: </strong> ". $installment->getInterestFree()."</p> ";
            }
        }
      echo "<pre>";
    }
}

GetInstallments::main();
