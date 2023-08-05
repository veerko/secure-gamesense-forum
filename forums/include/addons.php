<?php

// Copyright (c) 2023 st1koo <https://github.com/st1koo>
 //
 // This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 // This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 // You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>. 

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;


/**
 * Class flux_addon_manager
 *
 * This class is responsible for loading the addons and storing their hook listeners.
 */
class flux_addon_manager
{
	var $hooks = array();

	var $loaded = false;

	function load()
	{
		$this->loaded = true;

		$d = dir(PUN_ROOT.'addons');
		if (!$d) return;

		while (($addon_file = $d->read()) !== false)
		{
			if (!is_dir(PUN_ROOT.'addons/'.$addon_file) && preg_match('%(\w+)\.php$%', $addon_file))
			{
				$addon_name = 'addon_'.substr($addon_file, 0, -4);

				include PUN_ROOT.'addons/'.$addon_file;
				$addon = new $addon_name;

				$addon->register($this);
			}
		}
		$d->close();
	}

	function bind($hook, $callback)
	{
		if (!isset($this->hooks[$hook]))
			$this->hooks[$hook] = array();

		if (is_callable($callback))
			$this->hooks[$hook][] = $callback;
	}

	function hook($name)
	{
		if (!$this->loaded)
			$this->load();

		$callbacks = isset($this->hooks[$name]) ? $this->hooks[$name] : array();

		// Execute every registered callback for this hook
		foreach ($callbacks as $callback)
		{
			list($addon, $method) = $callback;
			$addon->$method();
		}
	}
}


/**
 * Class flux_addon
 *
 * This class can be extended to provide addon functionality.
 * Subclasses should implement the register method which will be called so that they have a chance to register possible
 * listeners for all hooks.
 */
class flux_addon
{
	function register($manager)
	{ }
}
