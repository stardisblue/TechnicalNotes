<?php
/**
 * Rave <https://github.com/Classicodr/rave-core>
 * Copyright (C) 2016 Rave Team
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Rename this file : app.php once you inputted your options.
 * app.php will be automatically ignore by git.
 */
return [
    /**
     * Set false on production
     */
    'debug' => true,

    /**
     * You can have as many datasources, as long as the names are different
     *
     * The supported datasources are Mysql, SQLite, PostgreSQL
     * Respectively : `MySQLPDO`, `SQLitePDO`, `PostgreSQLPDO`
     */
    'datasources' => [
        'default' => [
            'driver' => 'MySQLPDO',
            'host' => 'localhost',
            'database' => 'my_app',
            'login' => 'my_app',
            'password' => 'secret',
            /**
             * Uncomment if the datasource is not on the default port
             */
            //'port'=> 'non_standart_port_number'
        ]
    ],

    'error' => [
        '500' => '/internal-server-error',
        '404' => '/not-found',
        '403' => '/forbidden'
    ],

];
