<?php

declare(strict_types=1);

namespace SimpleSes;

class Config
{
    /** @var string */
    private $awsAccessKey;
    /** @var string */
    private $awsSecretKey;
    /** @var string */
    private $awsRegion;

    public function __construct(string $awsAccessKey, string $awsSecretKey, string $awsRegion)
    {
        $this->awsAccessKey   = $awsAccessKey;
        $this->awsSecretKey   = $awsSecretKey;
        $this->awsRegion      = $awsRegion;
    }

    public function getAwsAccessKey() : string
    {
        return $this->awsAccessKey;
    }

    public function getAwsSecretKey() : string
    {
        return $this->awsSecretKey;
    }

    public function getAwsRegion() : string
    {
        return $this->awsRegion;
    }
}
