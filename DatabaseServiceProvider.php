<?php

namespace Expresser\Database;

class DatabaseServiceProvider extends \Themosis\Core\IgniterService
{
    public function ignite()
    {
        $this->app->bindShared('db', function ($app) {
            return new WpdbConnection;
        });
    }
}
