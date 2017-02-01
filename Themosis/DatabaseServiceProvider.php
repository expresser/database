<?php

namespace Expresser\Database\Themosis;

use Expresser\Database\Eloquent\Model;
use Expresser\Database\WpdbConnection;
use Illuminate\Database\ConnectionResolver;
use Themosis\Foundation\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('db.connections', function ($app) {
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
