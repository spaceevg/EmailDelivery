<?php

namespace EmailDelivery;

final class EmailDelivery
{
    private static $emails = [];
    private static $reportEmail = '';
    private static $report = '';
    private static $user = '';
    private static $text = '';
    private static $params = [];
    private static $message = <<<MESSAGE
"<h1>{name}</h1>"

<p>
{text}
</p>

<p>
{from}
</p>
MESSAGE;

    /**
     * getState
     *
     * @return void
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
        return $state;
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
    public static function addState(string $user = '', string $text = '', array $emails = [], string $reportEmail = '', array $params = [])
    {
        if (!empty($user)) {
            self::$user = $user;
        }
        if (!empty($text)) {
            self::$text = \htmlspecialchars($text);
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
     * pushEmails
     *
     * @param  mixed $user
     * @param  mixed $text
     * @param  mixed $emails
     * @param  mixed $reportEmail
     * @param  mixed $params
     *
     * @return void
     */
    public function pushEmails(string $user, string $text, array $emails, string $reportEmail, array $params = [])
    {
        self::$emails = $emails;
        self::$reportEmail = $reportEmail;
        self::$user = $user;
        self::$text = $text;
        self::$params = $params;
        if (self::validateEmails()) {
            foreach (self::$emails as $email) {
                pushEmail();
            }
        } else {
            self::pushReport();
        }
    }

    /**
     * validateEmails
     *
     * @return void
     */
    private static function validateEmails()
    {
        $bool = true;
        foreach (self::$emails as $email) {
            $result = preg_match('~^[^@\s]+@[^@\s]+\.[^@\.]+~', $email['email']);
            if (!$result) {
                self::$report .= $email['email'] . PHP_EOL;
                $bool = false;
            }
        }
        return $bool;
    }

    /**
     * pushReport
     *
     * @return void
     */
    private static function pushReport()
    {

    }

    /**
     * pushEmail
     *
     * @return void
     */
    private static function pushEmail()
    {

    }
}
