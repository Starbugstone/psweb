<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CohereAiService
{
    const COHERE_API_URL = 'https://api.cohere.ai/v1/chat';
    public function __construct(private HttpClientInterface $client, private ParameterBagInterface $params)
    {
    }

    public function getRandomZombieFact(): string
    {
        $body = [
            'message' => 'Give me a random fun fact about zombies. And start the phrase with "Here\'s a random fun fact about zombies:',
        ];
        try {
            $response = $this->client->request('POST', self::COHERE_API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->params->get('app.cohere_api_key'),
                    'content-type' => 'application/json',
                ],
                'json' => $body,
            ]);

            $content = $response->toArray();
        } catch (\Exception $e) {
            return 'Sorry, I couldn\'t find any fun facts about zombies. the AI might be down. Please try again later.';
        }

        return $content['text'];
    }
}