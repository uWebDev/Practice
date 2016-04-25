<?php

namespace OAuth\Provider;

use ohmy\Auth2;

/**
 * Class Vk
 * @package App\Module\User\Model\OAuth\Provider
 */
class Vk extends AbstractProvider
{

    /**
     * Vk constructor.
     *
     * @param $component
     */
    public function __construct($component)
    {
        parent::__construct($component);

        $this->fieldsMapProvider = [
            'id' => 'id',
        ];
        $this->nameProvider = 'vk';
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        $result = false;
        $auth = Auth2::legs(3)
            ->set($this->component['authorize'])
            ->authorize('http://oauth.vk.com/authorize')
            ->access('https://oauth.vk.com/access_token')
            ->finally(function ($data) use (&$dataAccount) {
                $dataAccount = $data;
            });

        if (isset($dataAccount['access_token'])) {

            $params = [
                'user_id' => $dataAccount['user_id'],
                'v' => $this->component['version'],
                'access_token' => $dataAccount['access_token']
            ];

            $auth->GET('https://api.vk.com/method/users.get?' . $this->buildQuery($params), null,
                ['User-Agent' => parent::UA]
            )->then(function ($data) use (&$dataUser) {
                $dataUser = $data->json();
            });

            if (isset($dataUser['response'][0]['id'])) {
                $this->userInfo = array_shift($dataUser['response']);
                $result = true;
            }
        }

        return $result;
    }

}