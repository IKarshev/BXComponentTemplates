<?php
namespace Kontur\Sbplevoberezniybank;

use Kontur\Acquiringlevoberezniybank\DataTable;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Entity;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;


class Main{
	
	function __construct() {
		$this->options = new \Bitrix\Main\Config\Option();
		$this->module_id = "kontur.sbplevoberezniybank";
		$this->test_request_url = "https://nskbl.ahmad.ftc.ru:10400";
		$this->main_request_url = "https://zkc2b.koronacard.ru";
	}

	/**
	 * Возвращает настройки
	 */
	public function get_option(){
		return $this->options::getForModule( $this->module_id );
	}

	/**
	 * Сохраняет/изменяет настройки на отправленные в формате:
	 * array(
	 * 	"property_code1"=>"value1",
	 *	"property_code2"=>"value2",
	 * );
	 */
	public function save_option( array $new_settings ){

		$settings = array_merge($this->get_option() , $new_settings );


        foreach ($settings as $arkey => $arItem) {

            // delete old
            if ( !isset($new_settings[$arkey]) ){
                $this->options::delete( $this->module_id, array("name"=>$arkey) );
            }else{
                $this->options::set($this->module_id, $arkey, is_array($arItem) ? implode(",", $arItem):$arItem);
            };
            
        };
	}

    public function fill_params( array $aTabs ){
        $new_settings = $aTabs;

        foreach ($aTabs as $Tabskey => $TabItem) {
            
            foreach ($TabItem["OPTIONS"] as $optionskey => $optionsItem) {
                if ( is_array($optionsItem) ){
                
                    $option_type = $new_settings[$Tabskey]["OPTIONS"][$optionskey][3][0];
                    $option_id = $new_settings[$Tabskey]["OPTIONS"][$optionskey][0];
                
                    if ( !isset($this->get_option()[$option_id]) ){
                        switch ($option_type) {
                            case 'checkbox':
                                $default_type_value = "N";
                                break;
                            case 'text':
                                $default_type_value = "";
                                break;
                        };
                        // set value
                        $new_settings[$Tabskey][OPTIONS][$optionskey][2] = $default_type_value;
                    };
                };
            };
        };

        return $new_settings;
    }

	/**
	 * true — если выбран тестовый api
	 * false — если выбран боевой api
	 */
	public function is_test(){
		$is_test = isset( $this->get_option()["test_api"] ) && $this->get_option()["test_api"] == "Y" ? true : false;
		return $is_test;
	}

	/**
	 * Возвращает актуальный url на сервис
	 * в зависимости от настройки "Использовать тестовый api"
	 */
	public function get_url(){

		if ( $this->is_test() ){
			$this->url = $this->test_request_url;
		}else{
			$this->url = $this->main_request_url;
		};

		return $this->url;
	}
	
	/**
	 * $query_prefix — Префикс для формирования запроса
	 * $query_data — Массив с данными для запроса 
	 * $return_query_result — true если возвращать результат запроса, false если возвращать url запроса
	*/
	public function query_get( $query_prefix, $query_data, $return_query_result=false ){
		$url = $this->get_url()."/";
		if ( isset($query_data, $query_prefix) && count($query_data) > 0 ){
			if ( $query_prefix!="" ){
				$url .= $query_prefix;
			};

			$query_string = "?";
			foreach ($query_data as $arkey => $arItem) {
				$query_string .= $arkey."=".$arItem."&";
			}
			$query_string = substr($query_string,0,-1);
			$url .= $query_string;

			if( $return_query_result ){
				if( $curl = curl_init() ) {
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
					$out = curl_exec($curl);
					$response = $out;
					curl_close($curl);
	
					return json_decode($response, true);
				} else{
					return false;
				};
			} else{
				return $url;
			};


		} else{
			return false;
		};
	}

	/**
	 * $query_prefix — Префикс для формирования запроса
	 * $query_data — Массив с данными для запроса 
	*/
	public function query_post( $query_prefix="", $query_data ){

		$query_data = array_merge( $query_data, array(
			"extEntityId" => $this->get_option()["extEntityId"],
			"merchantId" => $this->get_option()["merchantId"],
		));

		$url = $this->get_url()."/";
		if ( isset($query_data) && count($query_data) > 0 ){
			if ( $query_prefix!="" ){
				$url .= $query_prefix;
			};

			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => json_encode( $query_data ),
				CURLOPT_HTTPHEADER => array("Content-Type:application/json"),
				CURLOPT_SSL_VERIFYPEER => false,
			));
			$response = curl_exec($ch);
			curl_close($ch);
			return json_decode($response, true);

		} else{
			return false;
		};
	}

	/** register qr-code
	 * $price — цена в копейках
	 * $qr_code_type — тип qr-кода ( "01"- статический, "02"- динамический, "03"- для подписки, )
	 * $paymentPurpose — Назначение платежа
	 * $expDt — время жизни динамического qr-кода в минутах
	*/
	public function register_qrcode( $price, $qr_code_type, $paymentPurpose="", $expDt=5 ){
		if( !isset($price, $qr_code_type) ){
			return false;
		};

		$site_url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

		$query = array(
			"amount" => $price,
			"qrcType" => $qr_code_type,
			"paymentPurpose" => $paymentPurpose,
			"expDt" => $expDt,
			"redirectUrl" => $site_url,
		);

		$request = $this->query_post("qr", $query);
		$request["qr_code_img"] = $this->get_url()."/qr/image/".$request["qrcId"];

		return $request;
	}

}
