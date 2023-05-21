<?php

namespace Vormkracht10\Mails\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordMessage;
use NotificationChannels\Telegram\TelegramMessage;
use Vormkracht10\Mails\Models\Mail;

class BounceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Mail $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function via(): array
    {
        return [
            // 'discord',
            'mail',
            // 'slack',
            // 'telegram',
        ];
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getMessage(): string
    {
        $emoji = array_random([
            '🔥', '🧯', '‼️', '⁉️', '🔴', '📣', '😅', '🥵',
        ]);

        return $emoji.' mail has bounced';
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->greeting($this->getTitle())
            ->line($this->getMessage());
    }

    public function toDiscord(): DiscordMessage
    {
        return DiscordMessage::create($this->getMessage(), [
            'title' => $this->getTitle(),
            'color' => 0xF44336,
        ]);
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage)
            ->content($this->getMessage());
    }

    public function toTelegram(): TelegramMessage
    {
        return TelegramMessage::create()
            ->content($this->getMessage());
    }
}
