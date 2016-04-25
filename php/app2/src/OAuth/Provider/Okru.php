<?php

namespace OAuth\Provider;

use ohmy\Auth2;

/**
 * Class Okru
 * @package App\Module\User\Model\OAuth\Provider
 */
class Okru extends AbstractProvider
{

    /**
     * Okru constructor.
     *
     * @param $component
     */
    public function __construct($component)
    {
        parent::__construct($component);

        $this->fieldsMapProvider = [
            'id' => 'uid',
        ];
        $this->nameProvider = 'okru';
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        $result = false;
        $auth = Auth2::legs(3)
            ->set($this->component['authorize'])
            ->authorize('https://connect.ok.ru/oauth/authorize')
            ->access('https://api.ok.ru/oauth/token.do')
            ->finally(function ($data) use (&$dataAccount) {
                $dataAccount = $data;
            });

        if (isset($dataAccount['access_token'])) {

            $params = [
                'method' => 'users.getCurrentUser',
                'application_key' => $this->component['appkey'],
                'format' => $this->component['format'],
            ];

            $params['sig'] = $this->signServerServer($params, $dataAccount['access_token'],
                $dataAccount['client_secret']);

            $params['access_token'] = $dataAccount['access_token'];

            $auth->GET('http://api.ok.ru/fb.do?' . $this->buildQuery($params), null,
                ['User-Agent' => parent::UA]
            )->then(function ($data) use (&$dataUser) {
                $dataUser = $data->json();
            });

            if (isset($dataUser['uid'])) {
                $this->userInfo = $dataUser;
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @param array $params
     * @param       $accessToken
     * @param       $clientSecret
     *
     * @return string
     */
    protected function signServerServer(array $params, $accessToken, $clientSecret)
    {
        ksort($params);
        $arr = '';
        foreach ($params as $key => $value) {
            $arr .= "$key=$value";
        }
        return md5($arr . md5($accessToken . $clientSecret));
    }
}