<?php

Class Doly {

	public function __construct()
	{
		if( ! isset(getInstance()->doly) )
		{
			getInstance()->doly = $this;
		}

		logMe('debug', 'My Doly Class Initialized');
	}

	public function hello()
	{
		echo 'Hello World !';
	}

}