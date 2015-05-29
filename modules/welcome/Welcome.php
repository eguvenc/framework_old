<?php

namespace Welcome;

class Welcome extends \Controller
{
    use \View\Layout\Base;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];

        // $data = array(
        //     0 => [
        //         'id' => 1,
        //         'username' => 'user@example.com',
        //     ],
        //     2 => [
        //         'id' => 2,
        //         'username' => 'test@example.com',
        //     ]
        // );

        // $this->session->set('key', $data);

        // $a = $this->session->get('key');
        // print_r($a);

        $this->db = $this->c['app']->provider('qb')->get();

    
        // // $this->db = $this->c->get('qb', ['connection' => 'default']);

        // // var_dump($this->db);


        // // $this->c['db'];

        // // echo $this->db
        // //     ->select('id', 'name')
        // //     ->from('users')
        // //     ->getSQL();

        // $or = $this->db->expr()->orx();
        // $or->add($this->db->expr()->eq('u.id', 1));
        // $or->add($this->db->expr()->eq('u.id', 2));

        // echo $this->db->update('users', 'u')
        //     ->set('u.password', md5('password'))
        //     ->where($or);


        // try {

        //     $this->db->beginTransaction(); // Operasyonları başlat
        //     echo $this->db->delete('uses', ['id' => 17], ['id' => \PDO::PARAM_INT]);
        //     $this->db->commit();      // Operasyonu bitti olarak kaydet

        //     echo 'Veri başarı ile silindi.';

        // } catch(\Exception $e)
        // {    
        //     $this->db->rollBack();    // İşlem başarısız olursa kaydedilen tüm verileri geri al.
        //     echo $e->getMessage();    // Hata mesajını ekrana yazdır.
        // }

        // $this->db->update(
        //     'users', 
        //     ['password' => '123456', 'username' => 'user@example.com'], 
        //     ['id' => 1], 
        //     [
        //         'id' => \PDO::PARAM_INT,
        //         'username' => \PDO::PARAM_STR,
        //         'password' => \PDO::PARAM_STR
        //     ]
        // );
        
        // $this->db->insert(
        //     'users', 
        //     ['username' => 'last@example.com', 'password' => 123456], 
        //     ['username' => \PDO::PARAM_STR, 'password' => \PDO::PARAM_INT]
        // );


        // $result = $this->db->query("SELECT * FROM ".$this->db->quoteIdentifier('users'))->resultArray();
        // $this->db->prepare("SELECT * FROM users WHERE id = ?")->bindValue(1, 1, \PDO::PARAM_INT)->execute();


        // var_dump($this->db->getConfiguration());

        // // var_dump($db);

        // var_dump($this->db);

        // $url = 'sqlite:dbname=:memory:';
        // $url = 'sqlite:dbname=/usr/local/var/db.sqlite';

        /**
         * http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html
         *
         * DOCTRINE DBAL CONNECTION class A EXTEND OL SONUCLAR resultArray() olarak alınabilisin.
         * 
         * db - servisine aşağıdaki bağlantıya bir adapter yazılacak ve o class içerisinden DBAL tanımlanabilecek.
         * dql - servisine doctrine query kurulacak
         */

        // http://www.doctrine-project.org/api/orm/2.0/class-Doctrine.ORM.Configuration.html

        // http://doctrine-orm.readthedocs.org/en/latest/reference/query-builder.html#executing-a-query
        
        // LOGLAR DAKI SQL QUERY I BOYLE YAP
        // 
        // http://php.net/manual/en/pdostatement.debugdumpparams.php

        // $result = $this->db->prepare("SELECT * FROM users WHERE id = ?")->bindValue(1, 1, \PDO::PARAM_INT)->execute()->resultArray();
        // var_dump($result);

        // http://doctrine-orm.readthedocs.org/en/latest/reference/query-builder.html#executing-a-query

        // $query = $this->db->createQueryBuilder();

        // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html

        // example 1:
        // $result = $this->db->select('id', 'username')
        //     ->from('users')
        //     ->where('id = ?')->setParameters([0 => 1])->execute()->resultArray();

        // print_r($result);

        // example 2:
        // $result = $this->db->select('id', 'username')
        //     ->where('id = ?')->setParameters([0 => 1])->get('users')->resultArray();

        // echo $result = $this->db
        //     ->insert('users')
        //     ->values(
        //         [
        //             'username' => '?',
        //             'password' => '?'
        //         ]
        //     )
        //     ->setParameter(0, 'example@doctrine.com')
        //     ->setParameter(1, '1232456')
        //     ->execute();


        // echo $this->db->lastQuery();


        // $result = $this->db->executeQuery($sql, $query->getParameters())->resultArray();

        // var_dump($result);


        // var_dump($stmt->fetch());
        // $stmt->debugDumpParams();

        // $query->prepare($sql)->project($sql, $query->getParameters());


// $qb = $this->em->createQueryBuilder();
// $q = $qb->update('models\User', 'u')
//         ->set('u.username', $qb->expr()->literal($username))
//         ->set('u.email', $qb->expr()->literal($email))
//         ->where('u.id = ?1')
//         ->setParameter(1, $editId)
//         ->getQuery();
// $p = $q->execute();

        // $this->qb->update('model/User', 'u')
        //     ->set('u.username', $this->qb->expr()->literal($username))

        // $conn->query($sql);


         // var_dump($result);

         // $conn->debugDumpParams();

        // $this->entry = new \Model\Entry;
        // var_dump($this->entry);

        // // $row = $this->db->query(sprintf("SELECT * FROM users LIMIT %d", 1))->resultArray();
        

        // return $this->db->exec(
        //     sprintf(
        //         "UPDATE entries SET title = %s, content = %s, date = %d WHERE entry_id = %d"
        //         $this->title,
        //         $this->content,
        //         $this->date,
        //         $id
        //     )
        // );

        // print_r($row);

    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !',
            ]
        );
    }
}


/* End of file welcome.php */
/* Location: .modules/welcome/welcome.php */