<?php

namespace Expresser\Database\Themosis;

use Expresser\Database\Eloquent\Model;
use Expresser\Database\WpdbConnection;
use Illuminate\Database\ConnectionResolver;
use Themosis\Core\IgniterService;

class DatabaseServiceProvider extends IgniterService
{
    public function ignite()
    {
        $this->app->bindShared('db.connections', function ($app) {
            $resolver = new ConnectionResolver([
                'wpdb' => new WpdbConnection,
            ]);

            $resolver->setDefaultConnection('wpdb');

            return $resolver;
        });

        $this->app->bindShared('db', function ($app) {
            return $app['db.connections']->connection();
        });

        Model::setConnectionResolver($this->app['db.connections']);
    }
}
