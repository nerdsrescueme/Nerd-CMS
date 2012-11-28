<?php

namespace Application\Model\User;

class Meta extends \Auth\Model\User\Meta
{
    protected static $table = 'nerd_user_metadata';
    protected static $columns;
    protected static $constraints;
    protected static $columnNames;
    protected static $primary;
}
