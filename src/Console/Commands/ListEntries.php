<?php

namespace Streams\Cli\Console\Commands;

use Illuminate\Support\Str;
use Streams\Core\Field\Field;
use Illuminate\Console\Command;
use Streams\Core\Support\Facades\Streams;

class ListEntries extends Command
{

    /**
     * @inheritDoc
     *
     * @var string
     */
    protected $signature = 'entries:list
        {stream : The stream to list entries from.}
        {--query= : Query constraints.}
        {--columns= : Columns to display.}
        {--per-page=15 : Entries per page.}
        {--page= : Page to list.}';

    protected $description = 'Display a table of stream entries.';

    public function handle($page = 0)
    {
        $stream = Streams::make($this->argument('stream'));

        $criteria = $stream->entries();

        if ($query = $this->option('query')) {

            $query = explode('|', $query);

            foreach ($query as $parameter) {

                list($method, $parameters) = explode(':', $parameter);

                $criteria->{$method}(...explode(',', $parameters));
            }
        }

        $results = $criteria->paginate([
            'per_page' => $this->option('per-page'),
            'page' => $page ?: $this->option('page'),
        ]);

        $this->info('Total: ' . $results->total());
        $this->info('Per Page: ' . $results->perPage());
        $this->info('Last Page: ' . $results->lastPage());
        $this->info('Current Page: ' . $results->currentPage());

        $headers = [];
        $data = [];

        $stream->fields->each(function (Field $field) use (&$headers) {
            $headers[] = $field->handle;
        });

        if ($columns = $this->option('columns')) {
            $headers = array_intersect_key($headers, explode(',', $columns));
        }

        $results->each(function ($entry) use (&$data, $headers) {

            $row = $entry->getAttributes();

            foreach ($row as &$value) {

                if (is_string($value)) {
                    $value = Str::limit($value, 150);
                }

                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value);
                }

                $value = wordwrap((string) $value, 75);
            }

            $row = array_intersect_key($row, array_flip($headers));

            $data[] = $row;
        });

        $this->table($headers, $data);

        if ($results->currentPage() < $results->lastPage()) {
            if ($this->confirm('Next Page?', true)) {
                $this->handle($results->currentPage() + 1);
            }
        }
    }
}
