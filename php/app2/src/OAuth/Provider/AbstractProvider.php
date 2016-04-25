<?php

namespace OAuth\Provider;

use OAuth\Exception\InvalidArgumentException;

/**
 * Class AbstractProvider
 * @package App\Module\User\Model\OAuth\Provider
 */
abstract class AbstractProvider implements ProviderInterface
{
    /** User Agent */
    const UA = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.82 Safari/537.36';

    /** @var  string */
    protected $nameProvider;

    /** @var  array */
    protected $component;

    /** @var  array */
    protected $fieldsMapProvider;

    /**
     * Storage for user info
     * @var array
     */
    protected $userInfo;

    /**
     * AbstractProvider constructor.
     *
     * @param $component
     */
    public function __construct($component)
    {
        $this->component = $component;
    }

    /**
     * Get user social id or null if it is not set
     * @return string
     * @throws InvalidArgumentException
     */
    public function getId()
    {
        if (isset($this->userInfo[$this->fieldsMapProvider['id']])) {
            return $this->userInfo[$this->fieldsMapProvider['id']];
        }
        throw new InvalidArgumentException('OAuth no ID');
    }


    /**
     * Return name of auth provider
     * @return string
     */
    public function getNameProvider()
    {
        return $this->nameProvider;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function buildQuery(array $params)
    {
        return urldecode(http_build_query($params));
    }
}