<?php

Class Tiny_Shm {

    public function read($storeKey)
    {
        $key    = crc32($storeKey);
        $shm_id = shmop_open($key, "a", 0644, 0); 

        if ($shm_id)
        {
            $size = shmop_size($shm_id);
            $data = shmop_read($shm_id, 0, $size); // Now lets read the string back

            if ( ! $data)
            {
                shmop_delete($shm_id);
                shmop_close($shm_id);
                
                return;
            }

            shmop_close($shm_id);

            return $data;
        }

        if( $shm_id != 0)
        {
            shmop_close($shm_id);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Write to memory
     * 
     * @param  string $storeKey 
     * @param  string $cacheData 
     * @return mixed           
     */
    public function write($storeKey, $data)
    {
        $key    = crc32($storeKey);
        $size   = mb_strlen($data, 'UTF-8');
        $shm_id = shmop_open($key, "c", 0755, $size);     // Create shared memory block with system id

        if ( ! $shm_id)
        {
            die("Couldn't create shared memory segment.");
        }

        $shmop_size = shmop_size($shm_id); // Get shared memory block's size
        $shm_bytes_written = shmop_write($shm_id, $data, 0);     // Lets write a test string into shared memory

        if ($shm_bytes_written != $size)
        {
            die("Couldn't write the entire length of data.");
        }

        shmop_close($shm_id);

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Delete the memory segment
     * 
     * @param  string $storeKey
     * @return void          
     */
    public function delete($storeKey)
    {
        $key    = crc32($storeKey);
        $shm_id = shmop_open($key, "a", 0644, 0); 

        shmop_delete($shm_id);
        shmop_close($shm_id);
    }

}

Class Hello_World extends Controller {    
                                      
    function __construct()
    {
    	parent::__construct();
    }

    function index()
    {    

        $shm = new Tiny_Shm();
        $shm->write('test', 'sadasdasdasd');

        $shm->delete('test');
        echo $shm->read('test'); 

        view('hello_world',function() {
            $this->set('name', 'Obullo');
            $this->set('footer', tpl('footer', false));
        });
    }
}

/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */