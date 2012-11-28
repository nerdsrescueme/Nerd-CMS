<?php

namespace Application\Model;

class User extends \Auth\Model\User
{
    protected static $table = 'nerd_users';
    protected static $columns;
    protected static $constraints;
    protected static $columnNames;
    protected static $primary;
}
