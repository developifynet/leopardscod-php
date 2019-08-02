<?php

namespace Developifynet\LeopardsCOD;

use Illuminate\Support\Traits\Macroable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LeopardsCODClient
{
    use Macroable;

    /**
     * API Key for Leopards COD APIs
     *
     * @var null
     */
    private $_api_key = null;

    /**
     * API Password for Leopards COD APIs
     *
     * @var null
     */
    private $_api_password = null;

    /**
     * Varianble responsible for Test Mode enable/disable
     *
     * @var int
     */
    private $_enable_test_mode = 0;

    /**
     * Leopards COD API endpoint
     *
     * @var string
     */
    private $_connect_url = 'http://new.leopardscod.com/webservice/';


    /**
     * LeopardsCODClient constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        if(
                ( isset($data['api_key']) && $data['api_key'] )
            &&  ( isset($data['api_password']) && $data['api_password'] )
        ) {
            $this->__init($data);
        }
    }

    /**
     * Set API Credentials
     *
     * @param array $data
     * @param array|mixed
     */
    private function __init($data = []) {
        // Error handling variables
        $status = 1;
        $error_msg = array();

        $credentials = $this->getCredentials();

        if(!isset($data['api_key']) || !$data['api_key']) {
            if(!$credentials['api_key']) {
                $status = false;
                $error_msg[] = 'API Key is required';
            }
        } else {
            $this->_api_key = $data['api_key'];
        }

        if(!isset($data['api_password']) || !$data['api_password']) {
            if(!$credentials['api_password']) {
                $status = false;
                $error_msg[] = 'API Password is required';
            }
        } else {
            $this->_api_password = $data['api_password'];
        }

        if(isset($data['enable_test_mode'])) {
            $this->_enable_test_mode = $data['enable_test_mode'];
        }

        return array(
            'status' => $status,
            'error_msg' => $error_msg,
        );
    }

    /*
     * Set API Credentials for APIs
     *
     * @param: array|mixed $data
     * @return: array|mixed
     */
    public function setCredentials($data = []) {
        $this->__init($data);

        return $this;
    }

    /*
     * Get API Credentials
     *
     * @param: void
     * @return: boolean
     */
    public function getCredentials() {
        return [
            'api_key' => $this->_api_key,
            'api_password' => $this->_api_password,
            'enable_test_mode' => $this->_enable_test_mode
        ];
    }

    /*
     * Check Test mode enable/ disable
     *
     * @param: void
     * @return: boolean
     */
    public function isTestMode() {

        return $this->_enable_test_mode ? true : false;
    }

    /*
     * Set API Credentials for APIs
     *
     * @param: array|mixed $data
     * @return: array
     */
    public function getAllCities($data = []) {

        $response = array(
            'status' => true,
            'city_list' => array(),
            'error_msg' => null
        );

        $init = $this->__init($data);

        if(!$init['status']) {
            // Set 'status' to false
            $response['status'] = false;
            $response['error_msg'] = implode(', ', $init['error_msg']);

            return $response;
        }

        /**
         * URL Suffix
         */
        $url_suffix = $this->isTestMode() ? 'getAllCitiesTest/format/json/' : 'getAllCities/format/json/';

        $call = $this->_callendpoint('GET', $url_suffix);

        if($call['status']) {
            if($call['response']['status']) {
                $response['city_list'] = $call['response']['city_list'];
            } else {
                $response['status'] = false;
                $response['error_msg'] = isset($call['response']['error']) ? $call['response']['error'] : 'Something went wrong';
            }
        }

        return $response;
    }

    /*
     * Book Packet into Leopards COD system
     *
     * @param: array|mixed $data
     * @return: array
     */
    public function bookPacket($data = []) {

        // Error handling variables
        $status = 1;
        $error_msg = array();

        $response = array(
            'status' => true,
            'track_number' => null,
            'slip_link' => null,
            'error_msg' => null
        );

        if(!count($data)) {
            // Set 'status' to false
            $response['status'] = false;
            $response['error_msg'] = 'Packet Data is required';

            return $response;
        }

        $init = $this->__init($data);

        if(!$init['status']) {
            // Set 'status' to false
            $response['status'] = false;
            $response['error_msg'] = implode(', ', $init['error_msg']);

            return $response;
        }

        /**
         * Obtain Packet Fields to mapping
         */
        $fields_mapping = $this->packetFields();

        /*
         * Variable will contain data after mapping
         */
        $packetData = array();

        foreach($fields_mapping['fields'] as $field) {
            /**
             * If field found in optional fields and data is not provided, set empty field
             */
            if(in_array($field, $fields_mapping['optional'])) {
                if(!isset($data[$field])) {
                    $packetData[$field] = '';
                    continue;
                }
            }

            /**
             * Validate provided fields as per mapping
             */
            if(!isset($data[$field]) || !$data[$field]) {
                $status = false;
                $error_msg[] = ucwords(str_replace('_', ' ', $field)) . ' is required.';
            } else {
                $packetData[$field] = $data[$field];
            }
        }

        if(!$status) {
            // One or more information is needed
            return array(
                'status' => $status,
                'sms_data' => '',
                'error_msg' => implode(', ', $error_msg),
            );
        } else {

            /**
             * URL Suffix
             */
            $url_suffix = $this->isTestMode() ? 'bookPacketTest/format/json/' : 'bookPacket/format/json/';

            $call = $this->_callendpoint('POST', $url_suffix, $packetData);

            if($call['response']['status']) {
                $response['track_number'] = $call['response']['track_number'];
                $response['slip_link'] = $call['response']['slip_link'];
            } else {
                $response['status'] = false;
                $response['error_msg'] = isset($call['response']['error']) ? $call['response']['error'] : 'Something went wrong';
            }

            return $response;
        }
    }


    /*
     * Track booked packet from Leopards COD system
     *
     * @param: array|mixed $data
     * @return: boolean
     */
    public function trackPacket($data = []) {

        // Error handling variables
        $status = 1;
        $error_msg = array();

        $response = array(
            'status' => true,
            'packet_list' => [],
            'error_msg' => null
        );

        if(!count($data)) {
            // Set 'status' to false
            $response['status'] = false;
            $response['error_msg'] = 'Tracking Data is required';

            return $response;
        }

        $init = $this->__init($data);

        if(!$init['status']) {
            // Set 'status' to false
            $response['status'] = false;
            $response['error_msg'] = implode(', ', $init['error_msg']);

            return $response;
        }

        /**
         * Obtain Packet Fields to mapping
         */
        $fields_mapping = $this->trackPacketFields();

        /*
         * Variable will contain data after mapping
         */
        $trackData = array();

        foreach($fields_mapping['fields'] as $field) {
            /**
             * If field found in optional fields and data is not provided, set empty field
             */
            if(in_array($field, $fields_mapping['optional'])) {
                if(!isset($data[$field])) {
                    $trackData[$field] = '';
                    continue;
                }
            }

            /**
             * Validate provided fields as per mapping
             */
            if(!isset($data[$field]) || !$data[$field]) {
                $status = false;
                $error_msg[] = ucwords(str_replace('_', ' ', $field)) . ' is required.';
            } else {
                $trackData[$field] = $data[$field];
            }
        }

        if(!$status) {
            // One or more information is needed
            return array(
                'status' => $status,
                'packet_list' => '',
                'error_msg' => implode(', ', $error_msg),
            );
        } else {

            /**
             * URL Suffix
             */
            $url_suffix = $this->isTestMode() ? 'trackBookedPacketTest/format/json/' : 'trackBookedPacket/format/json/';

            $call = $this->_callendpoint('GET', $url_suffix, $trackData);

            if($call['response']['status']) {
                $response['packet_list'] = $call['response']['packet_list'];
            } else {
                $response['status'] = false;
                $response['error_msg'] = isset($call['response']['error']) ? $call['response']['error'] : 'Something went wrong';
            }

            return $response;
        }
    }

    /**
     * Send requests to Leopards COD server
     *
     * @param string $request_type
     * @param $url_suffix
     * @param array $data
     * @return array
     */
    private function _callendpoint($request_type = 'GET', $url_suffix, $data = []) {

        $client = new Client();

        $status = true;
        $error_msg = '';
        $response_data = '';

        try {

            /**
             * Retreive Crednetials
             */
            $body = $this->getCredentials();
            unset($body['enable_test_mode']);

            /**
             * If data provided, embed with credentials
             */
            if(count($data)) {
                $body = array_merge($body, $data);
            }

            if($request_type == 'GET') {
                $response = $client->request($request_type, $this->_connect_url . $url_suffix, ['query' => $body]);
            } else {
                $response = $client->request($request_type, $this->_connect_url . $url_suffix, ['form_params' => $body]);
            }


            if($response->getStatusCode() == 200) {
                $responseBody = $response->getBody();
                if($responseBody) {
                    try {
                        $response_data = \GuzzleHttp\json_decode($responseBody, true);
                    } catch (\Exception $e) {
                        $status = false;
                        $error_msg = 'Service is temporarily unavailable. Your patience is requested.';
                    }
                } else {
                    $status = false;
                    $error_msg = 'Unable to connect with server.';
                }
            } else {
                $status = false;
                $error_msg = $response->getReasonPhrase();
            }
        } catch (GuzzleException $e) {
            $status = false;
            $error_msg = $e->getMessage();
        }

        return array(
            'status' => $status,
            'response' => $response_data,
            'error_msg' => $error_msg
        );
    }

    /**
     * Packet fields are stored here
     *
     * @return array
     */
    private function packetFields() {
        return array(
            'fields' => [
                'booked_packet_weight',
                'booked_packet_vol_weight_w',
                'booked_packet_vol_weight_h',
                'booked_packet_vol_weight_l',
                'booked_packet_no_piece',
                'booked_packet_collect_amount',
                'booked_packet_order_id',
                'origin_city',
                'destination_city',
                'shipment_name_eng',
                'shipment_email',
                'shipment_phone',
                'shipment_address',
                'consignment_name_eng',
                'consignment_email',
                'consignment_phone',
                'consignment_phone_two',
                'consignment_phone_three',
                'consignment_address',
                'special_instructions',
                'shipment_type'
            ],
            'optional' => [
                'booked_packet_vol_weight_w',
                'booked_packet_vol_weight_h',
                'booked_packet_vol_weight_l',
                'booked_packet_order_id',
                'consignment_phone_two',
                'consignment_phone_three',
                'shipment_type'
            ]
        );
    }

    /**
     * Track Packet fields are stored here
     *
     * @return array
     */
    private function trackPacketFields() {
        return array(
            'fields' => [
                'track_numbers'
            ],
            'optional' => []
        );
    }
}

