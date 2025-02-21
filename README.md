# Statamic Advanced Emails

> Statamic Advanced Emails is a Statamic addon that provides advanced email functionality with conditional logic.

## Features

This addon allows you to:

- Create and manage advanced email configurations.
- Define conditional logic for sending emails based on form submissions.
- Customize email recipients, CC, BCC, sender, reply-to, subject, and content.
- Use templates for HTML and text versions of emails.
- Attach uploaded assets to emails.
- Choose different mailers for sending emails.

## How to Install

You can install this addon via Composer:

```bash
composer require the-bigger-boat/statamic-advanced-emails
```

## How to Use

### Creating Advanced Emails

1. Navigate to the "Advanced Emails" section in the Statamic control panel.
2. Click "Create New" to create a new advanced email configuration.
3. Fill out the form with the necessary details, including recipients, subject, and content templates.
4. Define conditional logic to determine when the email should be sent based on form submissions.
5. Save the configuration.

### Managing Advanced Emails

- You can view, edit, and delete existing advanced email configurations from the "Advanced Emails" section in the control panel.

### Handling Form Submissions

- The addon listens for form submissions and sends emails based on the defined configurations and conditional logic.

### Customizing Email Templates

- Email templates can be customized using Blade templates located in the `resources/views` directory.

### Running Tests

- To run the tests for this addon, use PHPUnit:

```bash
vendor/bin/phpunit
```

For more detailed usage instructions, refer to the codebase and documentation.