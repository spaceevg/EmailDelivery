<?php

namespace EmailDelivery;

final class EmailDelivery
{
    private static $emails = [];
    private static $reportEmail = '';
    private static $report = '';
    private static $user = '';
    private static $text = '';
    private static $params = [
        'domain' => '',
        'age' => 18,
        'time' => [
            'from' => 10,
            'to' => 10
        ]
    ];
    private static $message = <<<MESSAGE
"<h1>{name}</h1>"

<p>
{text}
</p>

<p>
{from}
</p>
MESSAGE;
    private static $template = '';
    private static $valid = [];

    /**
     * getState
     *
     * @return array
     */
    public static function getState()
    {
        $state = [];
        $state['emails'] = self::$emails;
        $state['reportEmail'] = self::$reportEmail;
        $state['report'] = self::$report;
        $state['user'] = self::$user;
        $state['text'] = self::$text;
        $state['params'] = self::$params;
        $state['template'] = self::$template;
        return $state;
    }

    /**
     * setTemplate
     *
     * @param  mixed $template
     *
     * @return void
     */
    public static function setTemplate($template)
    {
        self::$template = $template;
    }

    /**
     * addState
     *
     * @param  mixed $user
     * @param  mixed $text
     * @param  mixed $emails
     * @param  mixed $reportEmail
     * @param  mixed $params
     *
     * @return void
     */
    public static function sendEmails(
        string $user = '',
        string $text = '',
        array $emails = [],
        string $reportEmail = '',
        array $params = [],
        string $template = ''
    ) {
        $time = date('H', time());
        if (!empty($user)) {
            self::$user = $user;
        }
        if (!empty($text)) {
            self::$text = $text;
        }
        if (count($emails)) {
            \array_merge(self::$emails, $emails);
        }
        if (!empty($repotEmail)) {
            self::$reportEmail = $reportEmail;
        }
        if (count($params)) {
            array_merge(self::$params, $params);
        }
        if (($time >= self::$params['time']['from']) && ($time < self::$params['time']['to'])) {
            self::validEmails();
            usort(self::$valid, function ($a, $b) {
                return ($a['date_registration'] <=> $b['date_registration']);
            });
            foreach (self::$valid as $email) {
                $result = self::sendEmail($email['name'], $email['email']);
            }
            return self::sendReport($result);
        }
    }

    /**
     * dropState
     *
     * @return void
     */
    public static function dropState()
    {
        self::$emails = [];
        self::$reportEmail = '';
        self::$user = '';
        self::$text = '';
        self::$params = [];
        self::$report = '';
    }

    /**
     * changeText
     *
     * @param  mixed $text
     *
     * @return void
     */
    public static function changeText(string $text)
    {
        self::$text = $text;
    }

    /**
     * changeParams
     *
     * @param  mixed $params
     *
     * @return void
     */
    public static function changeParams(array $params)
    {
        self::$params = $params;
    }

    /**
     * changeEmails
     *
     * @param  mixed $emails
     *
     * @return void
     */
    public static function changeEmails(array $emails)
    {
        self::$emails = $emails;
    }

    /**
     * validateEmails
     *
     * @return bool
     */
    private static function validEmails()
    {
        $bool = true;
        foreach (self::$emails as $email) {
            $result = preg_match('~^[^@\s]+@[^@\s]+\.[^@\.]+~', $email['email']);
            if (!$result) {
                self::$report .= $email['email'] . PHP_EOL;
                $bool = false;
                continue;
            }
            if ($email['age'] < self::$params['age']) {
                $bool = false;
                continue;
            }
            if (!empty(self::$params['domain']) && (strpos($email['email'], '@' . self::$params['domain']))) {
                $bool = false;
                continue;
            }
            self::$valid[] = $email;
        }
        return $bool;
    }

    /**
     * sendReport
     *
     * @return bool
     */
    private static function sendReport($result)
    {
        $result ? $string = 'successfully' : $string = 'unsuccessfully';
        self::$report =
            'Not valid emails:' . PHP_EOL .
            self::$report . PHP_EOL .
            'Count emails sent: ' . count(self::$valid) .
            'Result: ' . $string;
        if (!empty(self::$reportEmail)) {
            return mail(self::$reportEmail, 'Report', self::$report, ['From' => self::$user]);
        } else {
            return false;
        }
    }

    /**
     * sendEmail
     *
     * @return void
     */
    private static function sendEmail($name, $email)
    {

    }
}
