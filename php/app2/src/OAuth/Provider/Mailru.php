<?php

namespace OAuth\Provider;

use ohmy\Auth2;

/**
 * Class Mailru
 * @package App\Module\User\Model\OAuth\Provider
 */
class Mailru extends AbstractProvider
{

    /**
     * Mailru constructor.
     *
     * @param $component
     */
    public function __construct($component)
    {
        parent::__construct($component);

        $this->fieldsMapProvider = [
            'id' => 'uid',
        ];
        $this->nameProvider = 'mailru';
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        $result = false;

        $auth = Auth2::legs(3)
            ->set($this->component['authorize'])
            ->authorize('https://connect.mail.ru/oauth/authorize')
            ->access('https://connect.mail.ru/oauth/token')
            ->finally(function ($data) use (&$dataAccount) {
                $dataAccount = $data;
            });

        if (isset($dataAccount['access_token'])) {

            $params = [
                'app_id' => $dataAccount['client_id'],
                'method' => 'users.getInfo',
                'session_key' => $dataAccount['access_token'],
                'secure' => '1',
                'format' => $this->component['format'],
            ];

            $params['sig'] = $this->signServerServer($params, $dataAccount['client_secret']);

            $auth->GET('http://www.appsmail.ru/platform/api?' . $this->buildQuery($params), null,
                ['User-Agent' => parent::UA]
            )->then(function ($data) use (&$dataUser) {
                $dataUser = $data->json();
            });

            if (isset($dataUser[0]['uid'])) {
                $this->userInfo = array_shift($dataUser);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @param array  $params
     * @param string $secretKey
     *
     * @return string
     */
    protected function signServerServer(array $params, $secretKey)
    {
        ksort($params);
        $arr = '';
        foreach ($params as $key => $value) {
            $arr .= "$key=$value";
        }
        return md5($arr . $secretKey);
    }
}