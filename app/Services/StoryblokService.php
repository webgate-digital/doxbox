<?php

namespace App\Services;

use App\Clients\StoryblokClient;

class StoryblokService
{
    /**
     * @param string $slug
     * @return array
     * @throws \Storyblok\ApiException
     */
    public static function getContent(string $slug, string $language): array
    {
        $storyblok = new StoryblokClient(config('storyblok.api_key'));
        $storyblok->editMode(); // always enable draft mode
	    $storyblok->language($language);
        $data = $storyblok->getStoryBySlug($slug)->getBody();
        $content = (object)$data['story']['content']['body'];
        $pageContent = [];

        foreach ($content as $item) {
            $pageContent[$item['component']] = [
                '_editable' => $item['_editable'],
                'type' => isset($item['columns']) ? 'columns' : 'block',
                'columns' => $item['columns'] ?? [],
                'block' => isset($item['columns']) ? null : $item
            ];
        }

        return $pageContent;
    }
}
