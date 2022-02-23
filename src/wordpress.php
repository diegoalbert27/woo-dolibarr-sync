<?php

class wp_api {
    private $user;
    private $password;
    private $url_base;

    private $api_uri = 'wp-json/wp/v2';

    public function __construct($url_base, $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->url_base = $url_base;
    }

    public function file_or_url_to_wordpress_image (String $image_path) {
        
        if (filter_var($image_path, FILTER_VALIDATE_URL)) {
            
            return ['src' => $image_path];
        
        }

        if (!file_exists($image_path)) return;

        $file = file_get_contents($image_path);

        $filename = basename($image_path);

        $media = $this->get_all_media();
        
        foreach ($media as $m) {
            $medianame = basename($m->source_url);
            $id_media = $m->id;

            if ($filename === $medianame) {                
                return ['id' => $m ];
            }
        }

        $api_response = $this->post_media($file, $filename);

        // Return the ID of the image that is now uploaded to the WordPress site.
        return ['id' => $api_response];
    }

    public function post_media($file, $filename) {
        $ep = $this->url_base . $this->api_uri . '/media';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_URL, $ep );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $file );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [
            "Content-Disposition: form-data; filename=\"$filename\"",
            'Authorization: Basic ' . base64_encode( $this->user. ':' . $this->password ),
        ] );
        
        $result = curl_exec( $ch );
        curl_close( $ch );

        $result = json_decode($result);

        return $result;
    }

    // public function update_media($id, $file, $filename) {
    //     $ep = $this->url_base . $this->api_uri . '/media' . '/' . $id ;

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt( $ch, CURLOPT_URL, $ep );
    //     curl_setopt( $ch, CURLOPT_PUT, 1 );
    //     curl_setopt( $ch, CURLOPT_POSTFIELDS, $file );
    //     curl_setopt( $ch, CURLOPT_HTTPHEADER, [
    //         "Content-Disposition: form-data; filename=\"$filename\"",
    //         'Authorization: Basic ' . base64_encode( $this->user. ':' . $this->password ),
    //     ] );
        
    //     $result = curl_exec( $ch );
    //     curl_close( $ch );

    //     $result = json_decode($result);

    //     return $result;
    // }

    public function get_all_media () {
        $ep = $this->url_base . $this->api_uri . '/media';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_URL, $ep );

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        return $result;
    }
}
