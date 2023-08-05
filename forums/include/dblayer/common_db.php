<?php

// Copyright (c) 2023 st1koo <https://github.com/st1koo>
 //
 // This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 // This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 // You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>. 

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;


// Load the appropriate DB layer class
switch ($db_type)
{
	case 'mysql':
		require_once PUN_ROOT.'include/dblayer/mysql.php';
		break;

	case 'mysql_innodb':
		require_once PUN_ROOT.'include/dblayer/mysql_innodb.php';
		break;

	case 'mysqli':
		require_once PUN_ROOT.'include/dblayer/mysqli.php';
		break;

	case 'mysqli_innodb':
		require_once PUN_ROOT.'include/dblayer/mysqli_innodb.php';
		break;

	case 'pgsql':
		require_once PUN_ROOT.'include/dblayer/pgsql.php';
		break;

	case 'sqlite':
		require_once PUN_ROOT.'include/dblayer/sqlite.php';
		break;

	default:
		error('\''.$db_type.'\' is not a valid database type. Please check settings in config.php.', __FILE__, __LINE__);
		break;
}


// Create the database adapter object (and open/connect to/select db)
$db = new DBLayer($db_host, $db_username, $db_password, $db_name, $db_prefix, $p_connect);
