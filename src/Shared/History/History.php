<?php

declare(strict_types=1);

namespace POS\Shared\History;

use DateTime;

final class History
{
    private static ?DateTime $startTime = null;
    private static ?DateTime $endTime = null;
    /**
     * @var Event[]
     */
    private static array $events = [];

    private static array $history = [];

    public static function start(): void
    {
        self::$startTime = new DateTime();
    }

    public static function setEvent(Event $event): void
    {
        self::$events[] = $event;
    }

    public static function end(): void
    {
        if (self::$startTime === null) {
            throw new \Exception('Histórico não iniciado');
        }

        self::$endTime = new DateTime();
        self::save();
    }

    private static function save(): void
    {
        self::$history = [
            'projectId' => '',
            'userId' => '',
            'startTime' => self::$startTime->format('Y-m-d H:i:s'),
            'endTime' => self::$endTime->format('Y-m-d H:i:s'),
            'data' => [],
        ];

        foreach (self::$events as $event) {
            self::$history['data'][] = $event->getData();
        }
    }
}
