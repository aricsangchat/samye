<?php
/**
 * Plugin Name: Accumalation Counter
 * Plugin URI: http://www.samye.org
 * Description: [totalAccumalations] Shortcode for Total count for global accumalations.
 * Version: 1.0
 * Author: Aric Sangchat
 * Author URI: http://www.samye.org
 */

    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

    function countAccumalations() {
        $consumer_key = 'ck_732b61dcc89b1d74eedaad93df6a2d56892de9b2';
        $consumer_secret = 'cs_b93f17ceec46d463f0c5064a6bbe0c963e3c6db1';
        $url = 'https://samyeinstitute.org/wp-json/gf/v2/forms/42/entries';
        $basecount = 0;
        $incrementBy = 2000;
        $totalEntries = 0;
        $totalArr = array();
        
        //Use helper to get oAuth authentication parameters in URL.
        //Download helper library from: https://docs.gravityforms.com/wp-content/uploads/2017/01/class-oauth-request.php_.zip
        require_once( 'class-oauth-request.php' );
        $oauth1 = new OAuth_Request( $url, $consumer_key, $consumer_secret, 'GET', array(
            'paging[offset]' => '0',
            'paging[page_size]' => '1'
        ) );

        $full_url1 = $oauth1->get_url();

        //Send request
        $response1 = wp_remote_request( $full_url1,
            array('method'  => 'GET',
                'headers' => array('Content-type' => 'application/json')
            )
        );

        $totalEntries = json_decode($response1['body'])->total_count;
        // var_dump($totalEntries);

        while ($basecount <= $totalEntries) {
            $oauth2 = new OAuth_Request( $url, $consumer_key, $consumer_secret, 'GET', array(
                'paging[offset]' => $basecount,
                'paging[page_size]' => $incrementBy
            ) );
            $full_url2 = $oauth2->get_url();

            $basecount += $incrementBy;
            // var_dump($basecount);

            $response2 = wp_remote_request( $full_url2,
                array('method'  => 'GET',
                    'headers' => array('Content-type' => 'application/json')
                )
            );

            if ( wp_remote_retrieve_response_code( $response2 ) != 200 || ( empty( wp_remote_retrieve_body( $response2 ) ) ) ){
                // If not a 200, HTTP request failed.
                return 'There was an error getting total count for Global Accumalations, please check back later.';
            }

            $sum = 0;
            foreach(json_decode($response2['body'], true)['entries'] as $key=>$value){
                //var_dump($value[1]);
            if(isset($value[1]))
                $sum += (int)$value[1];
            }
            // var_dump($sum);

            array_push($totalArr, $sum);
        }

        //var_dump($totalArr);

        
        // Check the response code.
        if ( wp_remote_retrieve_response_code( $response1 ) != 200 || ( empty( wp_remote_retrieve_body( $response1 ) ) ) ){
            // If not a 200, HTTP request failed.
            return 'There was an error getting total count for Global Accumalations, please check back later.';
        }

        return array_sum($totalArr);
    }

    add_shortcode( 'totalAccumalations', 'countAccumalations' );

