<?php
    function countAccumalations() {
        $consumer_key = 'ck_b977f3d07a22a19206f44f3430aebd08492dc8e5';
        $consumer_secret = 'cs_119b341d529df7e35dfb0feb53a939b733652e76';
        $url = 'http://localhost:8888/wp-json/gf/v2/forms/42/entries';
        
        //Use helper to get oAuth authentication parameters in URL.
        //Download helper library from: https://docs.gravityforms.com/wp-content/uploads/2017/01/class-oauth-request.php_.zip
        require_once( 'class-oauth-request.php' );
        $oauth = new OAuth_Request( $url, $consumer_key, $consumer_secret, 'GET', array(
            'paging[offset]' => '28',
            'paging[page_size]' => '30'
        ) );
        $full_url = $oauth->get_url();
        var_dump( $full_url);
        //Send request
        $response = wp_remote_request( $full_url,
            array('method'  => 'GET',
                'headers' => array('Content-type' => 'application/json')
            )
        );
        
        var_dump(json_decode($response['body'], true)['entries'][0][1]);
        
        // Check the response code.
        if ( wp_remote_retrieve_response_code( $response ) != 200 || ( empty( wp_remote_retrieve_body( $response ) ) ) ){
            // If not a 200, HTTP request failed.
            die( 'There was an error attempting to access the API.' );
        }

        return $response;
    }

    add_shortcode( 'totalAccumalations', 'countAccumalations' );
?>