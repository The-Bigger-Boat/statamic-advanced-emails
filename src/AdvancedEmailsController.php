<?php

namespace TheBiggerBoat\StatamicAdvancedEmails;

use Statamic\Facades\User;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Facades\Blueprint;
use Illuminate\Support\Str;
use TheBiggerBoat\StatamicAdvancedEmails\Repositories\AdvancedEmailsItemRepository;
use Statamic\CP\Breadcrumbs;

class AdvancedEmailsController extends CpController
{
    protected $repository;

    public function __construct(AdvancedEmailsItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        abort_unless(User::current()->can('configure form advanced emails'), 403);

        $entries = $this->repository->all();

        return view('statamic-advanced-emails::index', [
            'title' => 'Advanced Emails',
            'entries' => $entries
        ]);
    }

    public function create()
    {
        // Provide an empty set of values to the form
        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->preProcess();

        $crumbs = Breadcrumbs::make([
            ['text' => 'Advanced Emails', 'url' => cp_route('advanced-emails.index')],
            ['text' => 'Create', 'url' => ''],
        ]);

        return view('statamic-advanced-emails::form', [
            'isNew'      => true,
            'blueprint'  => $blueprint->toPublishArray(),
            'values'     => $fields->values(),
            'meta'       => $fields->meta(),
            'entry'      => null, // No existing entry
            'crumbs'     => $crumbs
        ]);
    }

    public function store()
    {
        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues(request()->all());

        $form = \Statamic\Facades\Form::find($fields->get('form')->value());
        
        $fields->validate([
            'field' => 'in:' . implode(',', $form->fields()->keys()->all())
        ], [
            'field.in' => 'The field name must match of the field keys in the selected form blueprint.'
        ]);

        // Process the fields first
        $processed = $fields->process()->values()->all();

        // Now set the ID on the fully processed array
        $id = (string) Str::uuid();

        // Finally save the processed data (with ID)
        $this->repository->save($id, $processed);

        return redirect()->route('statamic.cp.advanced-emails.index');
    }

    public function edit($id)
    {
        // Retrieve blueprint
        $blueprint = $this->getBlueprint();
        $entry = $this->repository->get($id);
        $fields = $blueprint->fields()->addValues($entry)->preProcess();

        $crumbs = Breadcrumbs::make([
            ['text' => 'Advanced Emails', 'url' => cp_route('advanced-emails.index')],
            ['text' => 'Edit', 'url' => ''],
        ]);

        return view('statamic-advanced-emails::form', [
            'isNew'      => false,
            'blueprint'  => $blueprint->toPublishArray(),
            'values'     => $fields->values(),
            'meta'       => $fields->meta(),
            'entry'      => $entry,
            'crumbs'     => $crumbs,
            'id'         => $id
        ]);
    }

    public function update($id)
    {
        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues(request()->all());

        $form = \Statamic\Facades\Form::find($fields->get('form')->value());

        $fields->validate(
            [
                'field' => 'in:' . implode(',', $form->fields()->keys()->all())
            ],
            [
                'field.in' => 'The field name must match of the field keys in the selected form blueprint.'
            ]
        );

        $processed = $fields->process()->values()->all();

        // Process the fields and store them back into the entry
        $this->repository->save($id, $processed);

        return redirect()->route('statamic.cp.advanced-emails.index');
    }

    public function delete($id)
    {
        $this->repository->delete($id);

        return redirect()->route('statamic.cp.advanced-emails.index');
    }

    public function getBlueprint()
    {
        return Blueprint::makeFromFields(
            [
                'form' => [
                    'type' => 'select',
                    'display' => 'Form',
                    'options' => $this->getForms(),
                    'required' => true
                ],
                'to' => [
                    'type' => 'text',
                    'display' => 'Recipients',
                    'validate' => 'required|email',
                    'instructions' => 'Email address of the recipient(s) - comma separated.'
                ],
                'cc' => [
                    'type' => 'text',
                    'display' => 'CC Recipients',
                    'validate' => 'email',
                    'instructions' => 'Email address of the CC recipient(s) - comma separated.'
                ],
                'bcc' => [
                    'type' => 'text',
                    'display' => 'BCC Recipients',
                    'validate' => 'email',
                    'instructions' => 'Email address of the BCC recipient(s) - comma separated.'
                ],
                'sender' => [
                    'type' => 'text',
                    'display' => 'Sender',
                    'validate' => 'email',
                    'instructions' => 'Leave blank to fall back to the site default (hello@example.com).'
                ],
                'reply_to' => [
                    'type' => 'text',
                    'display' => 'Reply To',
                    'validate' => 'email',
                    'instructions' => 'Leave blank to fall back to sender.'
                ],
                'subject' => [
                    'type' => 'text',
                    'display' => 'Subject',
                    'validate' => 'required',
                    'instructions' => 'Email subject line.'
                ],
                'html' => [
                    'type' => 'template',
                    'display' => 'HTML view',
                    'validate' => 'required',
                    'folder' => config('statamic.forms.email_view_folder'),
                    'instructions' => 'The view for the html version of this email.'
                ],
                'text' => [
                    'type' => 'template',
                    'display' => 'Text view',
                    'validate' => 'required',
                    'folder' => config('statamic.forms.email_view_folder'),
                    'instructions' => 'The view for the text version of this email.'
                ],
                'markdown' => [
                    'type' => 'toggle',
                    'display' => 'Markdown',
                    'instructions' => 'Render the HTML version of this email using markdown.'
                ],
                'attachments' => [
                    'type' => 'toggle',
                    'display' => 'Attachments',
                    'instructions' => 'Attach uploaded assets to this email.'
                ],
                'mailer' => [
                    'type' => 'select',
                    'display' => 'Mailer',
                    'instructions' => 'Choose the mailer for sending this email. Leave blank to fall back to the default mailer.',
                    'options' => array_keys(config('mail.mailers')),
                    'clearable' => true,
                ],
                'field' => [
                    'type' => 'text',
                    'display' => 'Field',
                    'validate' => 'required',
                    'instructions' => 'The form field to use for the conditional logic.',
                ],
                'operator' => [
                    'type' => 'select',
                    'display' => 'Operator',
                    'validate' => 'required',
                    'default' => 'equals',
                    'options' => [
                        'equals' => 'Equals',
                        'not_equals' => 'Not Equals',
                        'contains' => 'Contains',
                        'not_contains' => 'Not Contains',
                    ],
                    'instructions' => 'The operator to use for the conditional logic.',
                ],
                'value' => [
                    'type' => 'text',
                    'display' => 'Value',
                    'validate' => 'required',
                    'instructions' => 'The value to compare the form field against.',
                ],
            ]
        );
    }

    public function getForms()
    {
        $forms = [];

        \Statamic\Facades\Form::all()->map(function ($form) use (&$forms) {
            $forms[$form->handle] = $form->title;
        });

        return $forms;
    }
}
