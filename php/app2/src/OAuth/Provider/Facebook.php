<?php

namespace OAuth\Provider;

use ohmy\Auth2;

/**
 * Class Facebook
 * @package App\Module\User\Model\OAuth\Provider
 */
class Facebook extends AbstractProvider
{

    /**
     * Facebook constructor.
     *
     * @param $component
     */
    public function __construct($component)
    {
        parent::__construct($component);

        $this->fieldsMapProvider = [
            'id' => 'id',
        ];
        $this->nameProvider = 'facebook';
    }

    /**
     * @return bool
     */
    public function authenticate()
    {
        $result = false;
        $auth = Auth2::legs(3)
            ->set($this->component['authorize'])
            ->authorize('https://www.facebook.com/dialog/oauth')
            ->access('https://graph.facebook.com/oauth/access_token')
            ->finally(function ($data) use (&$dataAccount) {
                $dataAccount = $data;
            });

        if (isset($dataAccount['access_token'])) {
            $auth->GET('https://graph.facebook.com/v' . $this->component['version']
                . '/me?access_token=' . $dataAccount['access_token'], null, ['User-Agent' => parent::UA]
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