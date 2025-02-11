<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Storage;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('services.elasticsearch.hosts')])
            ->build();
    }

    public function indexWord($word)
    {
        return $this->client->index([
            'index' => 'words',
            'id'    => $word->id,
            'body'  => [
                'word'          => $word->word,
                'meaning'       => $word->meaning,
                'pronunciation' => $word->pronunciation,
                'description'   => $word->description,
                'voice_url'     => $word->voice ? Storage::disk('liara')->url($word->voice) : null,
                'image_url'     => $word->image ? Storage::disk('liara')->url($word->image) : null,
                'user'          => [
                    'id'   => $word->user->id,
                    'name' => $word->user->name,
                ],
                'teams'         => $word->user->teams->map(function ($team) {
                    return [
                        'id'   => $team->id,
                        'name' => $team->name,
                        'owner' => [
                            'id'   => $team->owner->id,
                            'name' => $team->owner->name,
                        ],
                    ];
                })->toArray(),
                'category'      => [
                    'id'          => $word->category->id,
                    'name'        => $word->category->name,
                ]
            ],
        ]);
    }

    public function searchWords($query)
    {
        return $this->client->search([
            'index' => 'words',
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query'  => $query,
                        'fields' => ['word', 'meaning', 'description', 'user.name', 'category.name', 'teams.name'],
                    ],
                ],
            ],
        ]);
    }
}
