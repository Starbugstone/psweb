<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use xPaw\SourceQuery\SourceQuery;

class PzRconService
{
    private string $server;
    private int $port;
    private int $timeout;
    private int $engine;
    private string $rconPass;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->server = 'pz.starbugstone.eu';
        $this->port = 17000;
        $this->timeout = 1;
        $this->engine = SourceQuery::SOURCE;
        $this->rconPass = 'M7%e9p@nX1$u3L-z';
        $this->logger = $logger;
    }

    public function getPlayerInfo(): array
    {
        $query = new SourceQuery();

        $playerCount = 0;
        $players = [];

        try
        {
            $query->Connect($this->server, $this->port, $this->timeout, $this->engine);
            $query->SetRconPassword($this->rconPass);

            $output = $query->Rcon('players');

            preg_match('/Players connected \((\d+)\)/', $output, $matches);
            $playerCount = $matches[1] ?? 0;

            // Remove the "Players connected (x): \n" part
            $cleanedOutput = preg_replace('/^Players connected \(\d+\): \n/', '', $output);

            // Split the remaining string by newline character and remove empty lines
            $players = array_filter(explode("\n", $cleanedOutput), function($value) {
                return !empty(trim($value));
            });

            // Remove the leading "-" from each player name
            $players = array_map(function($player) {
                return ltrim($player, '-');
            }, $players);

        }
        catch(Exception $e)
        {
            $this->logger->error('An error occurred getting connected players', [
                'error' => $e->getMessage(),
            ]);
        }
        finally
        {
            $query->Disconnect();
        }
        return [
            'playerCount' => $playerCount,
            'players' => $players
        ];
    }



}