<?php

namespace OAuth\Provider;

use ohmy\Auth2;

/**
 * Class Google
 * @package App\Module\User\Model\OAuth\Provider
 */
class Google extends AbstractProvider
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
        $this->nameProvider = 'google';
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        $result = false;
        $auth = Auth2::legs(3)
            ->set($this->component['authorize'])
            ->authorize('https://accounts.google.com/o/oauth2/auth')
            ->access('https://accounts.google.com/o/oauth2/token')
            ->finally(function ($data) use (&$dataAccount) {
                $dataAccount = $data;
            });

        if (isset($dataAccount['access_token'])) {
            $auth->GET('https://www.googleapis.com/plus/v1/people/me?access_token=' . $dataAccount['access_token'], null,
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