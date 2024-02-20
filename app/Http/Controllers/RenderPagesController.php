<?php

namespace App\Http\Controllers;

class RenderPagesController extends Controller
{
    /**
     * @TODO: Store all page URLs in the JSON file for future checks
     * @TODO: Check if any pages was deleted and remove them from the build_production folder
     * @TODO: Check if any pages was added and add them to the build_production folder
     * @TODO: Check the last modified date of the page from the remote URL compare to the local JSON file and re-render the page if it was modified
     */
    public function __invoke(): string
    {
        $allPages = file_get_contents('https://platform.kizim.care/public/all-pages');

        $allPages = json_decode($allPages, true);

        foreach ($allPages as $page) {
            $dirPath = app_path(
                sprintf('../build_production/%s', trim($page['slug'], '/'))
            );

            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            $html = file_get_contents(sprintf('https://platform.kizim.care/public/pages/%s', $page['slug']));

            file_put_contents(sprintf('%s/index.html', $dirPath), $html);
        }

        return 'ok';
    }
}
