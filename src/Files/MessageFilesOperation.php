<?php

namespace App\Files;

use DateTime;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Uid\Uuid;

readonly class MessageFilesOperation
{
    public function __construct(
        private Filesystem $filesystem,
        private ContainerBagInterface $params
    ) {
    }

    public function dumpFile(string $content): string
    {
        $namespace = Uuid::v4();
        $uuid = $namespace->toRfc4122();

        $this->filesystem->dumpFile($this->params->get('path_to_dump_file') . $uuid, $content);

        return $uuid;
    }

    public function findFiles(string $dir, array $sort): array
    {

        $finder = new Finder();

        if (!empty($sort["selector"])) {
            switch ($sort["selector"]) {
                case "id":
                    $finder->sortByName();
                    break;
                case "date":
                    $finder->sortByModifiedTime();
                    break;
            }
        }

        if (!empty($sort["desc"]) && $sort["desc"] === true) {
            $finder->reverseSorting();
        }

        $finder->files()->in($dir);
        $files = [];
        $files['data'] = $this->prepareFileResponse($finder);
        $files['totalCount'] = count($files['data']);

        return $files;
    }

    public function findFile(string $dir, string $name): array
    {
        $finder = new Finder();
        $finder->files()->in($dir);
        $finder->files()->name($name);

        return $this->prepareFileResponse($finder);
    }

    private function prepareFileResponse(Finder $finder): array
    {
        $files = [];
        $date = new DateTime();

        foreach ($finder as $file) {
            $date->setTimestamp($file->getMTime());
            $files[] = [
                'id' => $file->getRelativePathname(),
                'date' => $date->format('Y-m-d h:i:s'),
                'message' => $file->getContents()
            ];
        }

        return $files;
    }
}