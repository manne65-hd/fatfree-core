<?php

/*

	Copyright (c) 2009-2019 F3::Factory/Bong Cosca, All rights reserved.

	This file is part of the Fat-Free Framework (http://fatfreeframework.com).

	This is free software: you can redistribute it and/or modify it under the
	terms of the GNU General Public License as published by the Free Software
	Foundation, either version 3 of the License, or later.

	Fat-Free Framework is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	General Public License for more details.

	You should have received a copy of the GNU General Public License along
	with Fat-Free Framework.  If not, see <http://www.gnu.org/licenses/>.

*/

//! Custom logger
class Log {

	// default format string
	const DEFAULT_FORMAT = 'r';

	protected
		//! File name
		$file,
		//! format string
		$format;

	/**
	*	Write specified text to log file
	*	@return string
	*	@param $text string
	*	@param $format string
	**/
	function write($text,$format=FALSE) {
		$fw=Base::instance();
		$current_format = (!$format) ? $this->format : $format;
		foreach (preg_split('/\r?\n|\r/',trim($text)) as $line)
			$fw->write(
				$this->file,
				date($current_format).
				(isset($_SERVER['REMOTE_ADDR'])?
					(' ['.$_SERVER['REMOTE_ADDR'].
					(($fwd=filter_var($fw->get('HEADERS.X-Forwarded-For'),
						FILTER_VALIDATE_IP))?(' ('.$fwd.')'):'')
					.']'):'').' '.
				trim($line).PHP_EOL,
				TRUE
			);
	}

	/**
	*	Erase log
	*	@return NULL
	**/
	function erase() {
		@unlink($this->file);
	}

	/**
	*	Instantiate class
	*	@param $file string
	*	@param $format string
	**/
	function __construct($file,$format=FALSE) {
		$fw=Base::instance();
		if (!is_dir($dir=$fw->LOGS))
			mkdir($dir,Base::MODE,TRUE);
		$this->file=$dir.$file;
		$this->format = (!$format) ? self::DEFAULT_FORMAT : $format;
	}

}
