<?php

namespace Expresser\Database;

class WpdbConnection extends \Illuminate\Database\MySqlConnection
{
    protected $fetchMode = ARRAY_A;

    protected $wpdb;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;

        parent::__construct($this->wpdb->dbh->get_pdo(), $this->wpdb->dbname, $this->wpdb->prefix);
    }

    public function selectFromWriteConnection($query, $bindings = [])
    {
        return parent::select($query, $bindings, false);
    }

    public function select($query, $bindings = [])
    {
        if (count($bindings) > 0) {
            $bindings = $this->prepareBindings($bindings);

            $query = $this->wpdb->prepare($query, $bindings);
        }

        return $this->wpdb->get_results($query, $this->getFetchMode());
    }

    public function insert($query, $bindings = [])
    {
        return $this->statement($query, $bindings);
    }

    public function update($query, $bindings = [])
    {
        return $this->affectingStatement($query, $bindings);
    }

    public function delete($query, $bindings = [])
    {
        return $this->affectingStatement($query, $bindings);
    }

    public function statement($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($me, $query, $bindings) {
            if (count($bindings) > 0) {
                $bindings = $me->prepareBindings($bindings);

                $query = $me->wpdb->prepare($query, $bindings);
            }

            $result = $me->wpdb->query($query);

            return $result !== false ?: $result;
        });
    }

    public function affectingStatement($query, $bindings = [])
    {
        return $this->run($query, $bindings, function ($me, $query, $bindings) {
            if (count($bindings) > 0) {
                $bindings = $me->prepareBindings($bindings);

                $query = $me->wpdb->prepare($query, $bindings);
            }

            return $me->wpdb->query($query);
        });
    }

    public function unprepared($query)
    {
        return $this->run($query, [], function ($me, $query) {
            return $this->statement($query);
        });
    }

    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new WpdbGrammar());
    }
}
