<?php

namespace OAuth\Provider;

use ohmy\Auth2;

/**
 * Class Yandex
 * @package App\Module\User\Model\OAuth\Provider
 */
class Yandex extends AbstractProvider
{

    /**
     * Yandex constructor.
     *
     * @param $component
     */
    public function __construct($component)
    {
        parent::__construct($component);

        $this->fieldsMapProvider = [
            'id' => 'id',
        ];
        $this->nameProvider = 'yandex';
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        $result = false;
        $auth = Auth2::legs(3)
            ->set($this->component['authorize'])
            ->authorize('https://oauth.yandex.ru/authorize')
            ->access('https://oauth.yandex.ru/token')
            ->finally(function ($data) use (&$dataAccount) {
                $dataAccount = $data;
            });

        if (isset($dataAccount['access_token'])) {

            $params = [
                'format' => $this->component['format'],
                'oauth_token' => $dataAccount['access_token']
            ];

            $auth->GET('https://login.yandex.ru/info?' . $this->buildQuery($params), null,
                ['User-Agent' => parent::UA]
            )->then(function ($data) use (&$dataUser) {
                $dataUser = $data->json();
            });

            if (isset($dataUser['id'])) {
                $this->userInfo = $dataUser;
                $result = true;
            }
        }

        return $result;
    }

}