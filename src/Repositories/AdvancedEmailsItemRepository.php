<?php

namespace TheBiggerBoat\StatamicAdvancedEmails\Repositories;

use Statamic\Facades\YAML;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

class AdvancedEmailsItemRepository
{
    protected string $path;

    public function __construct(string $directory = null)
    {
        $this->directory = $directory ?? base_path('content/advanced-emails');

        if (! File::exists($this->directory)) {
            File::makeDirectory($this->directory, 0755, true);
        }
    }

    public function all(): Collection
    {
        return collect(File::files($this->directory))
            ->mapWithKeys(function ($file) {
                // Get the filename without extension.
                $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    
                // Parse the file’s YAML into an array.
                $data = YAML::parse(File::get($file));
    
                // Return an array in the format [key => value].
                return [$filename => $data];
            });
    }

    public function byForm(string $form): Collection
    {
        return $this->all()->filter(function ($item) use ($form) {
            return $item['form'] === $form;
        });
    }

    public function get(string $id): array
    {
        return YAML::parse(File::get($this->directory.'/'.$id.'.yaml'));
    }

    public function save(string $id, array $data): void
    {
        File::put($this->directory.'/'.$id.'.yaml', YAML::dump($data));
    }

    public function delete(string $id): void
    {
        File::delete($this->directory.'/'.$id.'.yaml');
    }

    public function exists(string $id): bool
    {
        return File::exists($this->directory.'/'.$id.'.yaml');
    }
}
