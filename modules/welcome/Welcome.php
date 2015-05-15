<?php

namespace Welcome;

// use Doctrine\Common\ClassLoader;

// $classLoader = new ClassLoader('Doctrine', '/vendor/doctrine');
// $classLoader->register();

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

        /**
         * http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html
         *
         * DOCTRINE DBAL CONNECTION class A EXTEND OL SONUCLAR resultArray() olarak alınabilisin.
         * 
         * db - servisine aşağıdaki bağlantıya bir adapater yazılacak ve o class içerisinden DBAL tanımlanabilecek.
         * dql - servisine doctrine query kurulacak
         */

        // http://www.doctrine-project.org/api/orm/2.0/class-Doctrine.ORM.Configuration.html

        // http://doctrine-orm.readthedocs.org/en/latest/reference/query-builder.html#executing-a-query
        
        // LOGLAR DAKI SQL QUERY I BOYLE YAP
        // 
        // http://php.net/manual/en/pdostatement.debugdumpparams.php
        //         SQL: [96] SELECT name, colour, calories
        //     FROM fruit
        //     WHERE calories < :calories AND colour = :colour
        // Params:  2
        // Key: Name: [9] :calories
        // paramno=-1
        // name=[9] ":calories"
        // is_param=1
        // param_type=1
        // Key: Name: [7] :colour
        // paramno=-1
        // name=[7] ":colour"
        // is_param=1
        // param_type=2

        $config = new \Doctrine\DBAL\Configuration();
        //..
        $connectionParams = array(
            'pdo' => $this->c['app']->provider('pdo')->get(['connection' => 'default'])
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bindValue(1, 1);
        $stmt->execute();
        
        // var_dump($stmt->fetchAll());

        // var_dump($result);
        
        // http://doctrine-orm.readthedocs.org/en/latest/reference/query-builder.html#executing-a-query

        // $query = $conn->createQueryBuilder();

        // $sql = $query->select('id', 'username')
        //     ->from('users')
        //     ->where('id = ?')->setParameters([0 => 1]);

        // $stmt = $conn->executeQuery($sql, $query->getParameters());

        // var_dump($stmt);

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