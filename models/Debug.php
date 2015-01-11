<?php

/**
 * Debug model
 */
Class Debug extends Model
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
        echo 'oksssss';
    }

}