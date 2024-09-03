<?php

namespace Source\Framework\Support;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Source\Repositories\ClubEmailRepository;
use Source\Repositories\ClubRepository;
use Source\Repositories\SystemRepository;

/**
 * Class Email
 *
 * @package Source\Core
 */
class Email
{
    /** @var object */
    private $data;

    /** @var PHPMailer */
    private $mail;

    /** @var Message */
    private $message;

    /** @var ClubRepository */
    private $club;

    /** @var string */
    private $license;

    /**
     * Email constructor.
     */
    public function __construct(int $typeEmail = 1)
    {
        $this->mail = new PHPMailer(true);
        $this->data = new \stdClass();
        $this->message = new Message();

        //setup
        $this->mail->isSMTP();
        $this->mail->setLanguage(CONF_MAIL_OPTION_LANG);
        $this->mail->isHTML(CONF_MAIL_OPTION_HTML);
        $this->mail->SMTPAuth = CONF_MAIL_OPTION_AUTH;
        $this->mail->SMTPSecure = CONF_MAIL_OPTION_SECURE;
        $this->mail->CharSet = CONF_MAIL_OPTION_CHARSET;

        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $this->club = (new ClubRepository())->find("", "", "SERV_EMAIL, PORTA_SMTP, USA_PROTSEC_ENVIOEMAIL, EMAIL, SIGLA")->fetch();

        $clubEmail = (new ClubEmailRepository())->find("TIPO = :type", "type={$typeEmail}", "EMAIL AS USER_ID, SENHA AS USER_PASSWORD")->fetch();
        $this->club->USER_ID = $clubEmail->USER_ID;
        $this->club->USER_PASSWORD = $clubEmail->USER_PASSWORD;

        $system = (new SystemRepository())->find("", "", "NOMELICENCA")->fetch();
        $this->license = $system->NOMELICENCA;

        //auth
        $this->mail->Host = $this->club->SERV_EMAIL;
        $this->mail->Port = $this->club->PORTA_SMTP;
        $this->mail->Username = $this->club->USER_ID;
        $this->mail->Password = $this->club->USER_PASSWORD;
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $recipient
     * @param string $recipientName
     * @return Email
     */
    public function bootstrap(string $subject, string $body, string $recipient = null, string $recipientName = null): Email
    {
        $recipientName = (empty($recipientName) ? $this->license : $recipientName);
        $recipient = (empty($recipient) ? $this->club->USER_ID : $recipient);

        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_email = $recipient;
        $this->data->recipient_name = $recipientName;
        return $this;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     * @return Email
     */
    public function attach(string $filePath, string $fileName): Email
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    /**
     * @param $from
     * @param $fromName
     * @return bool
     */
    public function send(string $from = null, string $fromName = null): bool
    {
        if (empty($this->data)) {
            $this->message->error("Erro ao enviar, favor verifique os dados");
            return false;
        }

        if (!is_email($this->data->recipient_email)) {
            $this->message->warning("O e-mail de destinatário não é válido");
            return false;
        }

        $fromName = (empty($fromName) ? $this->license : $fromName);
        $from = (empty($from) ? $this->club->USER_ID : $from);

        if (!is_email($from)) {
            $this->message->warning("O e-mail de remetente não é válido");
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);
            $this->mail->setFrom($from, $fromName);

            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            $this->mail->send();
            return true;
        } catch (Exception $exception) {
            $this->message->error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param string $from
     * @param string $fromName
     * @return bool
     */
    // public function queue(string $from = CONF_MAIL_SENDER['address'], string $fromName = CONF_MAIL_SENDER["name"]): bool
    // {
    //     try {
    //         $stmt = Connect::getInstance()->prepare(
    //             "INSERT INTO
    //                 mail_queue (subject, body, from_email, from_name, recipient_email, recipient_name)
    //                 VALUES (:subject, :body, :from_email, :from_name, :recipient_email, :recipient_name)"
    //         );

    //         $stmt->bindValue(":subject", $this->data->subject, \PDO::PARAM_STR);
    //         $stmt->bindValue(":body", $this->data->body, \PDO::PARAM_STR);
    //         $stmt->bindValue(":from_email", $from, \PDO::PARAM_STR);
    //         $stmt->bindValue(":from_name", $fromName, \PDO::PARAM_STR);
    //         $stmt->bindValue(":recipient_email", $this->data->recipient_email, \PDO::PARAM_STR);
    //         $stmt->bindValue(":recipient_name", $this->data->recipient_name, \PDO::PARAM_STR);

    //         $stmt->execute();
    //         return true;
    //     } catch (\PDOException $exception) {
    //         $this->message->error($exception->getMessage());
    //         return false;
    //     }
    // }

    /**
     * @param int $perSecond
     */
    // public function sendQueue(int $perSecond = 5)
    // {
    //     $stmt = Connect::getInstance()->query("SELECT * FROM mail_queue WHERE sent_at IS NULL");
    //     if ($stmt->rowCount()) {
    //         foreach ($stmt->fetchAll() as $send) {
    //             $email = $this->bootstrap(
    //                 $send->subject,
    //                 $send->body,
    //                 $send->recipient_email,
    //                 $send->recipient_name
    //             );

    //             if ($email->send($send->from_email, $send->from_name)) {
    //                 usleep(1000000 / $perSecond);
    //                 Connect::getInstance()->exec("UPDATE mail_queue SET sent_at = NOW() WHERE id = {$send->id}");
    //             }
    //         }
    //     }
    // }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }
}