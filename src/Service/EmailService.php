<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class EmailService {

    const DEBUG = 0; // IN DEV ONLY -- SEE EMAIL IN PLAIN HTML

	private $adminEmail;
	private $env;
	private $data;
    private $logger;
    private $mailerInterface;
    private $trans;
    private $twig;
    private $assetsManager;
    private $PROJECT_NAME;

    /**
     * EmailService constructor.
     * @param $PROJECT_NAME
     * @param $ADMIN_EMAIL
     * @param $APP_ENV
     * @param MailerInterface $mailerInterface
     * @param TranslatorInterface $trans
     * @param Environment $twig
     * @param LoggerInterface $mailerLogger
     */
    public function __construct(
        $PROJECT_NAME,
		$ADMIN_EMAIL,
		$APP_ENV,
		MailerInterface $mailerInterface,
    	TranslatorInterface $trans,
    	Environment $twig,
        LoggerInterface $mailerLogger,
        Packages $assetsManager
	) {
		$this->adminEmail = $ADMIN_EMAIL;
		$this->env = $APP_ENV;
		$this->data = array(
			'to' => false,
			'toName' => false,
			'from' => false,
			'fromName' => false,
			'replyTo' => false,
			'template' => false,
			'subject' => false,
			'context' => [],
			'attachements' => [], // [ [ 0 => "path/to/file", 1 => "File Name" ], ... ]
            'locale' => 'fr',
		);
        $this->logger = $mailerLogger;
        $this->mailerInterface = $mailerInterface;
        $this->trans = $trans;
        $this->twig = $twig;
        $this->assetsManager = $assetsManager;
        $this->PROJECT_NAME = $PROJECT_NAME;
    }

	/**
	 * Displays email in browser if DEBUG == 1
	 */
	private function debugEmail($email) {
		if ($this->env == 'dev' && self::DEBUG) {
   			$vars = array_merge($email->getContext(), [
	            'email' => new WrappedTemplatedEmail($this->twig, $email),
	        ]);
	        $htmlEmail = $this->twig->render($email->getHtmlTemplate(), $vars);
	        $htmlEmail = str_replace('cid:@images/', $this->assetsManager->getUrl('build/images/'), $htmlEmail);
	        echo $htmlEmail; die();
		}
	}

	public function sendEmail(array $data): void {

		$data = array_merge($this->data, $data);
		if (!$data['to']) {
            $data['to'] = $this->adminEmail;
            $data['toName'] = $this->PROJECT_NAME;
        }
		if (!$data['from']) {
            $data['from'] = $this->adminEmail;
            $data['fromName'] = $this->PROJECT_NAME;
        }
		if (!$data['replyTo'])    $data['replyTo'] = $data['from'];
        if ($data['locale'])      $this->trans->setLocale($data['locale']);

        $to = new Address($data['to'], $data['toName'] ?: '');
        $from = new Address($data['from'], $data['fromName'] ?: '');


		if ($this->env == 'dev') $to = new Address($this->adminEmail, $this->PROJECT_NAME); // DEV :  Redirect all emails

        $subject = is_array($data['subject']) ? $this->trans->trans($data['subject']['text'], $data['subject']['params']) : $this->trans->trans($data['subject']);
		$email = (new TemplatedEmail())
			->to($to)
            ->from($from)
            ->replyTo($data['replyTo'])
            ->subject($subject)
            ->htmlTemplate($data['template'])
            ->context(array_merge($data['context'], [ '_to' => $data['to'] ]))
       	;

   		foreach ($data['attachements'] as $attachement) {
   			$file = $attachement[0];
   			$name = $attachement[1];
            $email->attachFromPath($file, $name);
   		}

   		$this->debugEmail($email);

        try {
            $this->mailerInterface->send($email);
        } catch (Exception $e){
            $this->logger->error('['.__FUNCTION__." in ".__FILE__." at ".__LINE__."] ".$e->getMessage());
            throw $e;
        }
	}

}
