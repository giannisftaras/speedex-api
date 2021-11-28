<?php

namespace SpeedexAPI;

class Voucher {

    private $voucher_data;

    public function __construct($voucher_data) {
        $this->voucher_data = $voucher_data;
    }

    # Check if the voucher provided exists in the database
    public function is_valid() {
        if (!empty($this->voucher_data[0]['voucher_code'])) {
            return true;
        }
        return false;
    }

    # Check if the voucher specified is delivered
    public function is_delivered() {
        if (!empty($this->voucher_data[0]['Rcvdate'])) {
            return true;
        }
        return false;
    }

    # Convert the Voucher object to array and return the data
    function get_data() {
        return (array) $this->voucher_data;
    }
}

class SpeedexAPI {

    # Define WSDL options
    private $wsdl = 'http://www.speedex.gr/getvoutrans/getvoutrans.asmx?WSDL';
    private $options = array();
    private $allowed_cache_types = ['WSDL_CACHE_NONE', 'WSDL_CACHE_DISK', 'WSDL_CACHE_MEMORY', 'WSDL_CACHE_BOTH'];

    public function __construct($custom_options = array()) {
        # Enable SOAP/WSDL cache at the PHP level (if supported by the server)
        ini_set('soap.wsdl_cache_enabled', 1);
        ini_set('soap.wsdl_cache_ttl', 300);

        # Define more WSDL options and customize them
        $wsdl_cache = 'WSDL_CACHE_MEMORY';
        $timeout = 5;
        $exceptions = true;
        if (!empty($custom_options['cache']) && is_int($custom_options['cache']) && $custom_options['cache'] >= 0 && $custom_options['cache'] <= 3) {
            $wsdl_cache = $this->allowed_cache_types[$custom_options['cache']];
        }
        if (!empty($custom_options['timeout']) && is_int($custom_options['timeout']) && $custom_options['timeout'] > 0 && $custom_options['timeout'] < 100) {
            $timeout = $custom_options['timeout'];
        }
        if (!empty($custom_options['exceptions']) && is_bool($custom_options['exceptions'])) {
            $exceptions = $custom_options['exceptions'];
        }

        $this->options = [
            'uri'=> 'http://schemas.xmlsoap.org/soap/envelope/',
            'style'=> 'SOAP_RPC',
            'use'=> 'SOAP_ENCODED',
            'soap_version'=> 'SOAP_1_2',
            'cache_wsdl'=> $wsdl_cache,
            'connection_timeout'=> $timeout,
            'trace'=> true,
            'encoding'=> 'UTF-8',
            'exceptions'=> $exceptions
        ];
    }

    # Get the voucher data from the SOAP API and convert them to a readable Voucher object
    public function get_voucher($voucher_id) {
        $api_resp = array();
        if (!empty($voucher_id)) {
            $voucher_resp = $this->call_voucher_api($voucher_id);
            if (!empty($voucher_resp['_myOutList']['Trace'])) {
                $api_resp[] = $voucher_resp['_myOutList']['Trace'];
            }
            if (!empty($voucher_resp['_myOutList2']['Trace'])) {
                foreach ($voucher_resp['_myOutList2']['Trace'] as $v_update) {
                    $api_resp[] = $v_update;
                }
            }
        }
        $voucher = new Voucher($api_resp);
        return $voucher;       
    }

    # Call the remote SOAP API
    private function call_voucher_api($voucher) {
        try {
            $params = array('id' => '', 'voucher_id' => $voucher, 'custCode' => '');
            $soap = new \SoapClient($this->wsdl, $this->options);
            $data = $soap->GetVoutrans_List($params);
            $result = json_decode(json_encode($data), true);
            return $result;
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }

}

?>