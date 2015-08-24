<?php

namespace Widgets\Tutorials;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class HelloWorld extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        // $this->queue->push(
        //     'Workers@Mailer',
        //     'mailer.1',
        //     array('order_id' => 'x', 'order_data' => [])
        // );

        // $this->queue->push(
        //     'Workers@Mailer',
        //     'mailer.1',
        //     array('order_id' => 'x', 'order_data' => [])
        // );


        // $connection = new AMQPStreamConnection('owl.rmq.cloudamqp.com', 5672, 'pktvnxjy', 'ipwbWnS2iGLudr1kz3Gfrg25eOYch6E7', 'pktvnxjy');
        // $channel = $connection->channel();

        // echo $this->session->get('ersin');

        // $connection = new \AMQPConnection(
        //     [
        //         'host' => "127.0.0.1"
        //     ]
        // );
        // $connection->connect();
        
        // $password = 'kIrSZwrGRNnUE5KTDo0UQHftqBF59J1N';

        // var_dump($password);
        // die;

        // $connection = new \AMQPConnection(
        //     array(
        //     'host' => 'owl.rmq.cloudamqp.com',
        //     'vhost' => 'pktvnxjy',
        //     'port' => 5672,
        //     'login' => 'pktvnxjy',
        //     'password' => utf8_encode('kIrSZwrGRNnUE5KTDo0UQHftqBF59J1N')
        //     )
        // );
        // $connection->connect();

        // $connection = new \AMQPConnection;
        // $connection->setHost('owl.rmq.cloudamqp.com/pktvnxjy');
        // $connection->setPort(5672);
        // $connection->setLogin('pktvnxjy');
        // $connection->setPassword('ipwbWnS2iGLudr1kz3Gfrg25eOYch6E7');
        // $connection->setVHost('pktvnxjy');
        // $connection->connect();

        // var_dump($connection->getHost());
        // var_dump($connection->getPort());
        // var_dump($connection->getPassword());
        // var_dump($connection->getVHost());
        // var_dump($connection->getLogin());


        // $connection = new \AMQPConnection;
        // $connection->setHost("5.189.128.221");
        // $connection->setPort(5672);
        // $connection->setLogin("root");
        // $connection->setPassword("aZX0bjL");
        // $connection->setVHost("/"); 
        // $connection = $connection->connect();

        // var_dump($connection);

        // $connection = new \AMQPConnection;
        // $connection->setHost("127.0.0.1");
        // $connection->setPort(5672);
        // $connection->setLogin('pktvnxjy');
        // $connection->setPassword("kIrSZwrGRNnUE5KTDo0UQHftqBF59J1N");
        // $connection->setVHost("pktvnxjy"); 
        // $connection->connect();

        // var_dump($connection);

        $mailResult = $this->mailer
            ->setProvider('mandrill')
            ->from('Obullo\'s <noreply@news.obullo.com>')
            ->to('obullo@yandex.com')
            ->cc('eguvenc@gmail.com')
            ->replyTo('obullo <obullo@yandex.com>')
            ->subject('test')
            ->message("test message")
            ->attach("/var/www/files/logs.jpg")
            ->attach("/var/www/files/debugger.gif")
            ->queue();

        if ($mailResult->hasError()) {
            echo "Fail";
        } else {
            echo "Success !";
        }

        print_r($mailResult->getArray());

        $this->view->load(
            'hello_world', 
            [
                'title' => 'Hello World !',
            ]
        );
    }

}