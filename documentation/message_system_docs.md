# Message System Documentation

## Overview

The Message System provides real-time notifications for unread broadcast messages. Users see a pulsing badge on the Messages link in the sidebar and a glowing dot on the bell icon in the topbar. When new messages are created, email notifications are automatically sent to targeted users.

## Features

Real-time unread message count that updates every 10 seconds without requiring page refresh. Visual indicators include a pulsing badge in the navbar sidebar showing the unread count, and a glowing blue dot on the bell icon in the topbar. The system automatically sends HTML email notifications to targeted users when new messages are created. Messages can be targeted to all students, specific student IDs, or by course enrollment. The system detects when a user returns to the browser tab and immediately refreshes the count.

## Files Involved

Two new files are required. The first is an API endpoint at `academy/api/get_unread_count.php` that returns the unread message count as JSON. The second is an email helper at `academy/helpers/message_email_helper.php` that handles sending notification emails.

Four existing files need to be modified. The navbar.php in includes needs a badge span added to the Messages link. The topbar.php needs a small dot span added to the bell icon. The main CSS stylesheet needs a pulse animation added. The footer.php needs JavaScript added before the closing body tag to poll the API every 10 seconds.

One additional file needs updating: wherever messages are created and inserted into the database, a call to the email helper function must be added after successful insertion to trigger email sending.

## How It Works

The system operates on a polling mechanism. When any page loads, JavaScript immediately fetches the unread count from the API and updates the visual indicators. It then repeats this every 10 seconds automatically. If the user switches browser tabs away and returns, it immediately checks for new messages.

The API endpoint queries the database to determine which broadcast messages the current user has not yet read, taking into account whether the message targets all students, specific student IDs, or students enrolled in particular courses. It returns the count as JSON.

When a new message is created and inserted into the database, the email helper function automatically identifies all targeted recipients, retrieves their email addresses, and sends them an HTML-formatted notification email via PHPMailer using the SMTP settings configured in email_config.php.

## Configuration

The polling interval can be adjusted by changing the setInterval value in footer.php. The default is 10 seconds. Email settings are configured in email_config.php, which automatically detects whether the system is running locally or on the live server and applies the appropriate SMTP configuration.

## Testing

To test locally, open DevTools in the browser and navigate to the Network tab. Refresh the page and verify that requests to get_unread_count.php appear every 10 seconds. Create a test message to verify that email notifications are sent. On the live server, follow the same network monitoring steps but ensure the file path in the fetch request matches the server structure. For the academy.slslanguage.com subdomain, use `/api/get_unread_count.php` as the fetch path.

## Troubleshooting

If the badge and dot are not appearing, verify that the navbar.php and topbar.php files have the correct CSS classes added. If the API returns 404 errors, check that the get_unread_count.php file exists in the api folder and that the fetch path in footer.php is correct. If emails are not sending, verify that the SMTP settings in email_config.php are correct for the environment.

If updates only work after clicking the Messages link, ensure that footer.php is included on all pages before the closing body tag. If path errors occur in nested subdirectories like courses, use an absolute path from the academy root instead of relative paths.

## Database Requirements

The system requires broadcast_messages table to store messages, broadcast_message_reads table to track which messages have been read by which users, users table for user information and email addresses, and enrollments table to determine which students are enrolled in which courses.

## Notes

The 10-second polling interval is a balance between responsiveness and server load. The system respects user privacy by only showing unread counts for the current user's own messages. The email system uses PHPMailer which requires the vendor directory with autoload.php to be present. The system automatically detects the local development environment versus live server and applies appropriate settings.