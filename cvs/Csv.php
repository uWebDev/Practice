<?php

namespace App;

/**
 * Class Csv
 * @package App
 */
class Csv
{
    private $handle;
    private $headers;
    private $init;

    /**
     * Csv constructor.
     *
     * @param string $path
     * @throws \Exception
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new \Exception('File not exists');
        }

        $this->handle = new \SplFileObject($path);
        $this->handle->setFlags(\SplFileObject::READ_CSV);
    }

    /**
     * @param        $delimiter
     * @param string $enclosure
     * @param string $escape
     *
     * @return $this
     */
    public function setControl($delimiter, $enclosure = "\"", $escape = "\\")
    {
        $this->handle->setCsvControl($delimiter, $enclosure, $escape);

        return $this;
    }

    /**
     * @param $lineNumber
     *
     * @return $this
     * @throws \Exception
     */
    public function setHeaderLine($lineNumber)
    {
        if (!is_numeric($lineNumber)) {
            throw new \Exception('Not is numeric');
        }

        $this->init = true;
        $this->handle->seek($lineNumber);
        $this->headers = $this->getCurrentRow();
        $this->next();

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $this->init();

        return $this->headers;
    }

    /**
     * @return array|bool
     */
    public function getRow()
    {
        $this->init();

        if ($this->isEof()) {
            return false;
        }

        $row = $this->getCurrentRow();
        $isEmpty = $this->isEmpty($row);
        $this->next();

        if ($isEmpty === false) {
            return $row;
        } elseif ($isEmpty) {
            return $this->getRow();
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $data = [];
        while ($row = $this->getRow()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getCurrentRow()
    {
        return $this->handle->current();
    }

    public function __destruct()
    {
        $this->handle = null;
    }

    /**
     * @return bool
     */
    protected function isEof()
    {
        return $this->handle->eof();
    }

    protected function next()
    {
        if ($this->isEof() === false) {
            $this->handle->next();
        }
    }

    protected function init()
    {
        if (true === $this->init) {
            return;
        }
        $this->setHeaderLine(0);
    }

    /**
     * @param $row
     *
     * @return bool
     */
    protected function isEmpty($row)
    {
        return empty($row[0]);
    }
}