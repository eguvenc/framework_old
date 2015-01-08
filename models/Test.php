<?php

/**
 * User model
 */
Class Test extends Model
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('service/db'); 
    }

    public function test()
    {
        echo 'ok';
    }

}