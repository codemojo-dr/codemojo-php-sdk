<?php

namespace CodeMojo\OAuth2\Storage;

/**
 * Class PersistentStorage
 * @package DRewards\OAuth2\Storage
 */
class PersistentStorage {

    /**
     * @var mixed
     */
    private $data;
    /**
     * @var string
     */
    private $path;

    /**
     * TokenStorage constructor.
     */
    public function __construct()
    {
        $this->path = sys_get_temp_dir() . sha1("/drewards_oauth_token.json");
        if(file_exists($this->path)) {
            $this->data = json_decode(base64_decode(file_get_contents($this->path)), true);
        }
    }

    /**
     * @param $token
     * @param $expiry
     * @return int
     */
    public function storeAccessToken($token, $expiry){
        $this->data['oauth2_access_token'] = $token;
        $this->data['oauth2_expiry'] = time() + $expiry;
        return file_put_contents($this->path,base64_encode(json_encode($this->data)));
    }

    /**
     * @return string
     */
    public function getAccessToken(){
        if(!isset($this->data['oauth2_access_token'])){
            return "";
        }
        return $this->data['oauth2_access_token'];
    }

    /**
     * @return bool
     */
    public function accessTokenMightHaveExpired(){
        return (!isset($this->data['oauth2_expiry']) || (time() > $this->data['oauth2_expiry']));
    }


}