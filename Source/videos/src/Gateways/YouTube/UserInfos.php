<?php

namespace Dukt\Videos\Gateways\YouTube;

use Dukt\Videos\Gateways\Common\AbstractUserInfos;

class UserInfos extends AbstractUserInfos
{
    public function __construct($response)
    {
        $this->instantiate($response);
    }

    public function instantiate($response)
    {
        $this->id = (string) $response->id;
        $this->id = substr($this->id, (strpos($this->id, ":user:") + 6));

        $this->name = (string) $response->author->name;
    }
}