<?php

namespace App;

/**
 * Class App
 * @package App
 */
class App
{
    private $model;

    /**
     * App constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function run($path)
    {
        try {
            $res = $this->model->getRandomRow();
        } catch (\Exception $e) {
            $this->isFile($path);
            $this->model->generateTable($path);
            $res = $this->model->getRandomRow();
        }
        $status = abs($res['status'] - 1);
        $this->model->update($res['id'], $status);

        return $res['name'] . ';' . $status;
    }

    protected function isFile($path)
    {
        if (!file_exists($path)) {
            throw new \Exception('File not exists');
        }
    }
}

