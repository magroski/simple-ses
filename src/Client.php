<?php

declare(strict_types=1);

namespace SimpleSes;

use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\Ses\SesClient;

class Client
{
    /** @var string */
    protected $senderName;
    /** @var string */
    protected $senderEmail;
    /** @var string */
    protected $bounceAddress;
    /** @var \Aws\Ses\SesClient */
    protected $client;

    public function __construct(Config $config)
    {
        $credentials = new Credentials($config->getAwsAccessKey(), $config->getAwsSecretKey());

        $this->client = new SesClient([
            'credentials' => $credentials,
            'region'      => $config->getAwsRegion(),
            'version'     => 'latest',
        ]);
    }

    public function setBounceAddress(string $bounceAddress) : void
    {
        $this->bounceAddress = $bounceAddress;
    }

    public function setSenderName(string $senderName) : void
    {
        $this->senderName = $senderName;
    }

    public function setSenderEmail(string $senderEmail) : void
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @param string|string[] $recipients
     */
    public function send(string $subject, string $body, $recipients, bool $asText = false) : Result
    {
        $recipients = $this->sanitizeRecipients($recipients);
        $emailData  = $this->buildHeaders($subject, $recipients);

        if ($asText) {
            $emailData['Message']['Body']['Text'] = [
                'Data'    => $body,
                'Charset' => 'utf-8',
            ];
        } else {
            $body = preg_replace('/[\s\t\n]+/', ' ', $body);

            $emailData['Message']['Body']['Html'] = [
                'Data'    => $body,
                'Charset' => 'utf-8',
            ];
        }

        if (!empty($this->bounceAddress)) {
            $emailData['ReturnPath'] = $this->bounceAddress;
        }

        $status = $this->client->sendEmail($emailData);

        return $status;
    }

    /**
     * @param string[]|string $recipients
     * @return string[]
     */
    private function sanitizeRecipients($recipients) : array
    {
        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }
        foreach ($recipients as $key => $value) {
            $recipients[$key] = trim($value);
        }

        return $recipients;
    }

    /**
     * @param string   $subject
     * @param string[] $recipients
     * @return mixed[]
     */
    private function buildHeaders(string $subject, array $recipients) : array
    {
        $emailData = [
            'Source'      => "$this->senderName <$this->senderEmail>",
            'Destination' => [
                'ToAddresses'  => $recipients,
                'CcAddresses'  => [],
                'BccAddresses' => [],
            ],
            'Message'     => [
                'Subject' => [
                    'Data'    => $subject,
                    'Charset' => 'utf-8',
                ],
            ],
        ];

        return $emailData;
    }
}
