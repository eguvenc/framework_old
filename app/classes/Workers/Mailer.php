<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Queue\JobInterface;
use Obullo\Mail\Provider\Mailgun;
use Obullo\Mail\Provider\Mandrill;
use Obullo\Container\ContainerInterface;

class Mailer implements JobInterface
{
    /**
     * Application
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
    }

    /**
     * Fire the job
     * 
     * @param Job   $job  object
     * @param array $data data array
     * 
     * @return void
     */
    public function fire($job, array $data)
    {
        switch ($data['mailer']) { 
        case 'mailgun':
            $this->sendWithMailgun($data);
            break;
        case 'mandrill': 
            $this->sendWithMandrill($data);
            break;
        }
        if ($job instanceof Job) {
            $job->delete(); 
        }       
    }

    /**
     * Sent mail with Mailgun Api
     * 
     * @param array $msgEvent queue data
     * 
     * @return object MailResult
     */
    protected function sendWithMailgun(array $msgEvent)
    {
        print_r($msgEvent);

        $mail = new Mailgun($this->c, $this->c['mailer']->getParameters());
        $mailtype = (isset($msgEvent['html'])) ? 'html' : 'text';

        $mail->from($msgEvent['from']);

        if (! empty($msgEvent['to'])) {
            foreach ($msgEvent['to'] as $email) {
                $mail->to($email);
            }
        }
        if (! empty($msgEvent['cc'])) {
            foreach ($msgEvent['cc'] as $email) {
                $mail->cc($email);
            }
        }
        if (! empty($msgEvent['bcc'])) {
            foreach ($msgEvent['bcc'] as $email) {
                $mail->bcc($email);
            }
        }
        if (! empty($headers['Reply-To'])) {
            $this->msgEvent['h:Reply-To'] = $headers['Reply-To'];
        }
        if (! empty($headers['Message-ID'])) {
            $this->msgEvent['h:Message-Id'] = $headers['Message-ID'];
        }
        $mail->subject($msgEvent['subject']);
        $mail->message($msgEvent[$mailtype]);

        if (isset($msgEvent['files'])) {
            foreach ($msgEvent['files'] as $value) {
                $mail->attach($value['fileurl'], $value['disposition']);
            }
        }
        $mail->addMessage('o:deliverytime', $mail->setDate());
        return $mail->send();
    }

    /**
     * Sent mail with Mandrill Api
     * 
     * @param array $msgEvent queue data
     * 
     * @return void
     */
    protected function sendWithMandrill(array $msgEvent)
    {
        $mail = new Mandrill($this->c, $this->c['mailer']->getParameters());
        $mailtype = (isset($msgEvent['html'])) ? 'html' : 'text';

        $mail->from($msgEvent['from_email'], $msgEvent['from_name']);

        foreach ($msgEvent['to'] as $to) {  // Parse to, cc and bcc 
            $method = $to['type'];
            $mail->$method($to['email']);
        }
        $mail->subject($msgEvent['subject']);
        $mail->message($msgEvent[$mailtype]);

        if (isset($msgEvent['files'])) {
            foreach ($msgEvent['files'] as $value) {
                $mail->attach($value['fileurl'], $value['disposition']);
            }
        }
        $mail->addMessage('send_at', $mail->setDate());
        return $mail->send();
    }

}