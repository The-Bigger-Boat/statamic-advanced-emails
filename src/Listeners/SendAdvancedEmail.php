<?php

namespace TheBiggerBoat\StatamicAdvancedEmails\Listeners;

use Statamic\Events\FormSubmitted;
use TheBiggerBoat\StatamicAdvancedEmails\Repositories\AdvancedEmailsItemRepository;
use Illuminate\Support\Facades\Mail;
use Statamic\Facades\Site;
use Statamic\Forms\Email;

class SendAdvancedEmail
{
    protected $repository;

    public function __construct(AdvancedEmailsItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /** Handle the event.
     *
     * @param  \Statamic\Events\FormSubmitted  $event
     * @return void
     */
    public function handle(FormSubmitted $event)
    {
        $advancedEmails = $this->repository->byForm($event->submission->form->handle());

        if (is_null($advancedEmails)) return;

        foreach ($advancedEmails as $advancedEmail) {
            // check the conditional logic
            if ($this->checkConditionalLogic($advancedEmail, $event)) {
                // send the email
                $this->sendEmail($advancedEmail, $event);
            }
        }
    }

    private function checkConditionalLogic($advancedEmail, FormSubmitted $event)
    {
        foreach ($event->submission->data() as $key => $value) {
            if ($advancedEmail['field'] == $key) {
                if ($advancedEmail['operator'] == 'equals') {
                    return $value == $advancedEmail['value'];
                }
                if ($advancedEmail['operator'] == 'not_equals') {
                    return $value != $advancedEmail['value'];
                }
                if ($advancedEmail['operator'] == 'contains') {
                    $values = explode(',', $advancedEmail['value']);
                    if (in_array($value, $values)) {
                        return true;
                    }
                }
                if ($advancedEmail['operator'] == 'not_contains') {
                    $values = explode(',', $advancedEmail['value']);
                    if (!in_array($value, $values)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function sendEmail($config, FormSubmitted $event)
    {
        $submission = $event->submission;
        $site = Site::default();

        Mail::mailer($this->config['mailer'] ?? null)
            ->send(new Email($submission, $config, $site));                
    }

}
