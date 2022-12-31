<?php

namespace linhtv\phpmvc;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName();
}