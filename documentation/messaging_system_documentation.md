# SLS EduHub - Messaging System Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Database Architecture](#database-architecture)
3. [File Structure & Responsibilities](#file-structure--responsibilities)
4. [Data Flow](#data-flow)
5. [Security & Authorization](#security--authorization)
6. [Formatting System](#formatting-system)
7. [Troubleshooting Guide](#troubleshooting-guide)
8. [Future Enhancements](#future-enhancements)

---

## System Overview

### Purpose
The messaging system enables administrators to send targeted announcements, course updates, and personalized messages to students through their learning dashboard. Messages support rich formatting using Markdown syntax and can be filtered by type.

### Key Features
- **Targeted Messaging**: Send to all students, specific courses, or individual students
- **Message Types**: Announcements, course updates, new materials, targeted messages
- **Rich Formatting**: Markdown support for bold, italic, lists, headers, links
- **Read Tracking**: Automatic tracking of which students have read which messages
- **Real-time Preview**: Admin can preview formatted messages before sending
- **Filtering**: Students can filter messages by type or read/unread status
- **Responsive Design**: Works seamlessly on desktop and mobile devices

---

## Database Architecture

### Table: `broadcast_messages`

**Purpose**: Stores all messages created by administrators

**Key Columns**:
- `id` (INT UNSIGNED, PRIMARY KEY): Unique message identifier
- `title` (VARCHAR 255): Message subject/title
- `content` (TEXT): Full message body (supports Markdown)
- `message_type` (ENUM): One of 'course_update', 'new_material', 'announcement', 'targeted'
- `created_at` (TIMESTAMP): When message was sent
- `created_by` (INT UNSIGNED, FK → students.id): Admin who created the message
- `link_url` (VARCHAR 500, nullable): Optional external link
- `link_text` (VARCHAR 100, nullable): Text for link button
- `target_all_students` (TINYINT): 1 if message is for all students, 0 otherwise
- `target_course_ids` (TEXT, nullable): Comma-separated course IDs
- `target_student_ids` (TEXT, nullable): Comma-separated student IDs

**Indexes**:
- `idx_created_at`: Speeds up chronological queries
- Foreign key on `created_by` references `students(id)`

**Relationships**:
- One-to-Many with `broadcast_message_reads`
- Many-to-Many with `students` (via target_student_ids)
- Many-to-Many with `courses` (via target_course_ids)

---

### Table: `broadcast_message_reads`

**Purpose**: Tracks which students have read which messages

**Key Columns**:
- `id` (INT UNSIGNED, PRIMARY KEY): Unique read record identifier
- `message_id` (INT UNSIGNED, FK → broadcast_messages.id): The message that was read
- `student_id` (INT UNSIGNED, FK → students.id): The student who read it
- `read_at` (TIMESTAMP): When the message was opened

**Indexes**:
- `idx_student`: Speeds up queries for a student's read messages
- Unique constraint on (message_id, student_id) prevents duplicate reads

**Relationships**:
- Many-to-One with `broadcast_messages`
- Many-to-One with `students`

---

## File Structure & Responsibilities

### Admin-Side Files

#### `/sls-admin/create_message.php`

**Purpose**: Form interface for administrators to compose and send messages

**Dependencies**:
- `config/db_connect.php` - Database connection
- `config/auth.php` - Authentication check (redirectIfNotAuthorized)
- `css/admin.css` - Admin dashboard styling
- `marked.min.js` (CDN) - Markdown parsing library

**Key Functions**:
1. **Form Rendering**: Displays message composition form with targeting options
2. **Data Fetching**: 
   - Queries `courses` table for course selection dropdown
   - Queries `students` table for student selection dropdown (using firstname, lastname)
3. **Targeting Logic**: Shows/hides course/student selection based on radio button
4. **Preview System**: JavaScript function `togglePreview()` renders Markdown preview
5. **Form Submission**: 
   - Validates title and content are not empty
   - Processes targeting selection (all/courses/students)
   - Converts selected courses/students to comma-separated strings
   - Inserts record into `broadcast_messages` table
   - Shows success/error message

**Session Requirements**:
- `$_SESSION['user_id']` - Admin's user ID
- `$_SESSION['name']` - Admin's display name
- `$_SESSION['role']` - User role (must be admin/teacher)

**JavaScript Interactions**:
- Radio button change listeners for targeting options
- `togglePreview()` function uses Marked.js to parse and display formatted content
- Dynamic show/hide of course/student selection divs

---

### Student-Side Files

#### `/messages.php`

**Purpose**: List view of all messages accessible to the logged-in student

**Dependencies**:
- `bootstrap.php` - Application bootstrap (loads config, starts session)
- `includes/navbar.php` - Student navigation sidebar
- `includes/topbar.php` - Top bar with search and theme toggle
- `includes/mobile_header.php` - Mobile menu button
- `includes/navbar_styles.php` - Navigation styling
- `assets/css/dashboard.css` - Dashboard styles
- Bootstrap 5.3.3 (CDN) - UI framework
- Bootstrap Icons (CDN) - Icon library

**Key Functions**:
1. **Authentication Check**: Validates user is logged in, redirects if not
2. **Message Retrieval Query**:
   - Joins `broadcast_messages` with `broadcast_message_reads`
   - Checks if message targets student via:
     - `target_all_students = 1`, OR
     - Student ID in `target_student_ids` (using FIND_IN_SET), OR
     - Student enrolled in course listed in `target_course_ids` (subquery to `enrollments`)
   - Adds `is_read` column by counting matching records in `broadcast_message_reads`
   - Orders by `created_at DESC` (newest first)
3. **Unread Count**: Loops through messages to count where `is_read = 0`
4. **Message Display**: Renders each message as a clickable card with:
   - Type badge with color coding
   - Unread dot indicator (if applicable)
   - Title and preview (first 200 characters)
   - Creation timestamp
   - Data attributes for filtering (data-type, data-is-read)
5. **Filtering System**: JavaScript filter buttons toggle visibility based on:
   - Message type (announcement, course_update, etc.)
   - Read status (unread only)
   - All messages

**Click Handling**: Each message card is wrapped in `<a href="message_view.php?id=X">` for navigation

**Responsive Features**:
- Mobile menu toggle with overlay
- Theme toggle (light/dark mode) with localStorage persistence
- Smooth animations on page load

---

#### `/message_view.php`

**Purpose**: Detailed view of a single message with read tracking

**Dependencies**: Same as messages.php plus:
- `marked.min.js` (CDN) - Markdown rendering library

**Key Functions**:
1. **Message ID Validation**: 
   - Extracts `?id=` from URL query string
   - Validates it's a positive integer
   - Redirects to messages.php if invalid
2. **Authorization Check**:
   - Queries `broadcast_messages` with same targeting logic as messages.php
   - Verifies student has permission to view this specific message
   - Redirects if unauthorized
3. **Read Status Check**:
   - Queries `broadcast_message_reads` to see if student already read message
   - Returns boolean `$is_read`
4. **Automatic Read Marking**:
   - If `$is_read = false`, inserts record into `broadcast_message_reads`
   - Includes `message_id`, `student_id`, and timestamp
   - Sets `$is_read = true` after insertion
5. **Message Rendering**:
   - Displays type badge, title, metadata (date, time, read status)
   - Renders message content using Marked.js to convert Markdown to HTML
   - Shows optional action button if `link_url` exists
6. **Markdown Processing**:
   - JavaScript reads message content from PHP JSON output
   - Calls `marked.parse()` to convert Markdown to HTML
   - Injects rendered HTML into `messageBody` div

**Security Notes**:
- Uses `executeQuery()` helper with prepared statements (prevents SQL injection)
- Escapes all user-generated content with `e()` function before display
- Session-based authentication prevents unauthorized access

---

## Data Flow

### Admin Sending a Message

```
1. Admin opens create_message.php
   ↓
2. Page queries DB for courses and students lists
   ↓
3. Admin fills form:
   - Selects message type
   - Enters title and content (with Markdown)
   - Optionally adds link
   - Selects targeting (all/courses/students)
   ↓
4. Admin clicks "Preview" (optional)
   - JavaScript calls marked.parse() on content
   - Displays formatted preview in same page
   ↓
5. Admin clicks "Send Message"
   ↓
6. PHP validates input
   ↓
7. PHP processes targeting:
   - If "all": target_all_students = 1
   - If "courses": target_course_ids = "1,5,12"
   - If "students": target_student_ids = "45,67,89"
   ↓
8. INSERT query to broadcast_messages table
   ↓
9. Success message shown to admin
```

### Student Viewing Messages

```
1. Student logs into dashboard
   ↓
2. Clicks "Messages" in navbar
   ↓
3. messages.php loads:
   ↓
4. Query to broadcast_messages:
   - Filters by targeting logic
   - Joins with broadcast_message_reads for read status
   ↓
5. PHP generates message cards with data attributes
   ↓
6. Student sees list with unread count badge
   ↓
7. Student can filter by type or unread
   - JavaScript shows/hides cards based on data attributes
   ↓
8. Student clicks a message card
   ↓
9. Browser navigates to message_view.php?id=X
   ↓
10. message_view.php loads:
    ↓
11. Query validates student has access
    ↓
12. Query checks if already read
    ↓
13. If unread: INSERT to broadcast_message_reads
    ↓
14. Message displayed with Markdown rendering
    ↓
15. Student sees formatted content and optional link
```

---

## Security & Authorization

### Authentication Layer

**Admin Access** (`create_message.php`):
- `redirectIfNotAuthorized()` function checks session
- Verifies user has admin/teacher role
- Redirects to login if unauthorized

**Student Access** (`messages.php`, `message_view.php`):
- Checks `$_SESSION['user_id']` exists
- Redirects to registration page if not logged in
- Validates user_id is valid integer with `filter_var()`

### Authorization Logic

**Message Visibility Rules**:
A student can see a message if ANY of these conditions are true:
1. `target_all_students = 1` (broadcast to everyone)
2. Student's ID appears in `target_student_ids` (directly targeted)
3. Student is enrolled in a course listed in `target_course_ids` (course-based targeting)

**SQL Implementation**:
```
WHERE (
    m.target_all_students = 1
    OR FIND_IN_SET(?, m.target_student_ids) > 0
    OR EXISTS (
        SELECT 1 FROM enrollments e 
        WHERE e.student_id = ? 
        AND FIND_IN_SET(e.course_id, m.target_course_ids) > 0
    )
)
```

### SQL Injection Prevention

**All queries use prepared statements** via `executeQuery()` helper:
- User input is parameterized (?)
- Values are bound separately from SQL string
- Database driver handles escaping automatically

**Example**:
```php
$stmt = executeQuery($db, $query, [$message_id, $user_id, $user_id]);
```

### XSS Prevention

**All output is escaped** using `e()` helper function:
- Converts special characters to HTML entities
- Prevents execution of injected JavaScript
- Applied to: titles, names, emails, content previews

**Example**:
```php
<?= e($message['title']) ?>
```

### CSRF Protection

**Session-based authentication**:
- User must be logged in with valid session
- Session ID regenerated on login (handled by auth.php)
- Forms submit from authenticated pages only

---

## Formatting System

### Markdown Support

**Library**: Marked.js v9.1.6 (loaded from CDN)

**Admin Side** (`create_message.php`):
- **Preview Function**: Converts Markdown to HTML in real-time
- **Storage**: Content stored as plain Markdown in database
- **No server-side processing**: Keeps database agnostic to formatting

**Student Side** (`message_view.php`):
- **Rendering**: JavaScript calls `marked.parse()` on page load
- **Output**: HTML is injected into `messageBody` div
- **Safety**: Marked.js sanitizes HTML by default (prevents XSS via Markdown)

### Supported Markdown Syntax

**Text Formatting**:
- `**bold**` → bold
- `*italic*` → italic
- `***bold italic***` → bold italic

**Lists**:
- `- item` → bullet points
- `1. item` → numbered lists

**Headings**:
- `# Heading 1` → H1
- `## Heading 2` → H2
- `### Heading 3` → H3

**Links**:
- `[text](url)` → clickable link

**Blockquotes**:
- `> quote` → indented quote block

**Horizontal Rules**:
- `---` → horizontal line

### Styling Integration

**Message Display Styling**:
```
.message-body {
    line-height: 1.8;
    color: var(--text-dark);
    font-size: 1.05rem;
}
```

**Markdown-rendered HTML** inherits these base styles:
- Headers get appropriate sizing
- Lists get proper indentation
- Links get theme colors
- All elements respect theme (light/dark mode)

---

## Troubleshooting Guide

### Common Issues

#### 1. Messages Not Appearing for Students

**Symptoms**: Student sees "No messages yet" despite admin sending messages

**Possible Causes**:
- Targeting mismatch (message not targeted to student)
- Wrong table names (student_messages vs broadcast_messages)
- Student not enrolled in targeted courses

**Debug Steps**:
1. Check `broadcast_messages` table - does message exist?
2. Verify `target_all_students = 1` for test message
3. Check student's enrollments in `enrollments` table
4. Run the query manually with student's ID to see results
5. Check for PHP errors in browser console or server logs

**Solution**:
- Ensure message is targeted to "All Students" for testing
- Verify table names are `broadcast_messages` (not student_messages)
- Check foreign key relationships are correct

---

#### 2. Click Not Working on Message Cards

**Symptoms**: Clicking message card doesn't navigate to detail view

**Possible Causes**:
- JavaScript error blocking click event
- Missing or incorrect `<a>` tag
- Filter function interfering with links
- Browser console errors

**Debug Steps**:
1. Open browser console (F12) - check for red errors
2. Right-click message card → Inspect Element
3. Verify `<a href="message_view.php?id=X">` wraps the card
4. Try disabling filters, then clicking message
5. Check if URL changes in address bar

**Solution**:
- Ensure each message card is wrapped in anchor tag
- Remove onclick handlers if using anchor tags
- Check that `message_view.php` exists in same directory
- Verify no JavaScript errors on page load

---

#### 3. Markdown Not Rendering

**Symptoms**: Student sees raw Markdown syntax instead of formatted text

**Possible Causes**:
- Marked.js library not loading (CDN blocked/failed)
- JavaScript not executing
- Incorrect function call

**Debug Steps**:
1. Open browser console - look for "marked is not defined"
2. Check Network tab - verify marked.min.js loaded (200 status)
3. Verify `<script src="...marked.min.js">` is in `<head>`
4. Check if `marked.parse()` is being called in JavaScript

**Solution**:
- Confirm CDN link is correct and accessible
- Ensure script loads before calling `marked.parse()`
- Check for JavaScript syntax errors preventing execution
- Fallback: Download marked.js and host locally if CDN is blocked

---

#### 4. Preview Not Working in Admin Panel

**Symptoms**: Clicking "Preview" button does nothing

**Possible Causes**:
- Marked.js not loaded on admin page
- JavaScript function not defined
- Button onclick not connected

**Debug Steps**:
1. Check console for JavaScript errors
2. Verify `<script src="...marked.min.js">` is in admin page
3. Inspect button - verify `onclick="togglePreview()"`
4. Check if `togglePreview()` function exists in script tag

**Solution**:
- Add marked.js CDN link to create_message.php `<head>`
- Ensure `togglePreview()` function is defined in script section
- Verify button has correct onclick handler

---

#### 5. Messages Not Marking as Read

**Symptoms**: Messages stay in "unread" state even after opening

**Possible Causes**:
- `broadcast_message_reads` table doesn't exist
- INSERT query failing silently
- Foreign key constraint errors
- Wrong student_id being used

**Debug Steps**:
1. Check if `broadcast_message_reads` table exists in database
2. Look for PHP errors in server logs
3. Verify foreign keys reference correct tables
4. Check that `$user_id` matches session user_id
5. Run INSERT query manually in phpMyAdmin

**Solution**:
- Ensure `broadcast_message_reads` table is created
- Verify foreign keys reference `students(id)` not `users(id)`
- Check that `executeQuery()` is returning successfully
- Add error logging around INSERT statement for debugging

---

#### 6. Theme Toggle Not Working

**Symptoms**: Light/dark mode toggle doesn't change theme

**Possible Causes**:
- localStorage not working
- CSS variables not defined
- JavaScript not loading
- Body class not changing

**Debug Steps**:
1. Check console for errors
2. Open Application tab → Local Storage → verify 'eduhub-theme' key exists
3. Inspect `<body>` element → verify class changes between 'light' and 'dark'
4. Check if CSS variables are defined in navbar_styles.php

**Solution**:
- Verify `applyTheme()` function is defined
- Ensure CSS defines both `.light` and `.dark` class styles
- Check that localStorage is enabled in browser
- Clear localStorage and try again

---

### Database Issues

#### Foreign Key Constraints Fail

**Error**: "Cannot add or update a child row: a foreign key constraint fails"

**Causes**:
- Referenced table/column doesn't exist
- Data type mismatch (INT vs INT UNSIGNED)
- Trying to reference non-existent student/course ID

**Solutions**:
1. Verify all referenced tables exist:
   - `students` table with `id` column (INT UNSIGNED)
   - `courses` table with `id` column (INT UNSIGNED)
   - `enrollments` table with correct columns
2. Ensure data types match exactly:
   - If `students.id` is INT UNSIGNED, FK must be INT UNSIGNED
3. Check that IDs being inserted actually exist in parent tables

---

#### FIND_IN_SET Not Working

**Error**: Messages not showing for course-targeted messages

**Causes**:
- `target_course_ids` stored with spaces: "1, 5, 12" instead of "1,5,12"
- Course IDs stored as integers instead of strings
- NULL values in target fields

**Solutions**:
1. Store comma-separated values without spaces
2. Use `implode(',', $array)` in PHP to create proper format
3. Ensure TEXT columns accept NULL if fields are optional

---

### Performance Optimization

#### Slow Message Loading

**Symptoms**: messages.php takes several seconds to load

**Causes**:
- No indexes on foreign keys
- Complex EXISTS subquery on large tables
- Missing indexes on created_at

**Solutions**:
1. Add indexes:
   ```sql
   CREATE INDEX idx_student ON broadcast_message_reads(student_id);
   CREATE INDEX idx_created_at ON broadcast_messages(created_at);
   CREATE INDEX idx_enrollment ON enrollments(student_id, course_id);
   ```
2. Consider denormalizing data for very large datasets
3. Add pagination if messages exceed 50-100 records

---

## Future Enhancements

### Planned Features

1. **Email Notifications**
   - Send email when new message is posted
   - Configurable per student (email preferences)
   - Queue system for bulk emails

2. **Message Scheduling**
   - Schedule messages to be published at specific date/time
   - Add `publish_at` column to `broadcast_messages`
   - Cron job or scheduler to change status

3. **File Attachments**
   - Allow admin to attach PDFs, images, documents
   - Create `message_attachments` table
   - Use file upload system with size limits

4. **Reply Functionality**
   - Allow students to reply to messages
   - Create threaded conversation system
   - Add `parent_message_id` column

5. **Message Categories/Tags**
   - Beyond 4 types, allow custom tags
   - Create `message_tags` table (many-to-many)
   - Filter by multiple tags

6. **Rich Text Editor**
   - Replace plain textarea with WYSIWYG editor
   - Add image upload directly in content
   - Preview in real-time while typing

7. **Push Notifications**
   - Browser push notifications when new message
   - Use Web Push API
   - Requires service worker setup

8. **Analytics Dashboard**
   - Track message open rates
   - See which messages are most read
   - Student engagement metrics

9. **Draft Messages**
   - Save messages as drafts before sending
   - Add `status` column: 'draft', 'published', 'archived'
   - Allow editing published messages

10. **Bulk Actions**
    - Delete multiple messages at once
    - Archive old messages
    - Resend messages to new students

---

### Technical Improvements

1. **Caching Layer**
   - Cache message lists for students
   - Use Redis or Memcached
   - Invalidate on new message creation

2. **API Endpoints**
   - RESTful API for message CRUD operations
   - Enable mobile app integration
   - JSON responses for AJAX requests

3. **Automated Testing**
   - PHPUnit tests for query logic
   - Selenium tests for UI interactions
   - Test targeting logic thoroughly

4. **Logging System**
   - Log all message sends (who, when, to whom)
   - Track read timestamps for analytics
   - Audit trail for compliance

5. **Internationalization**
   - Support multiple languages
   - Translate UI strings
   - Store message content in multiple languages

---

## Maintenance Guide

### Regular Tasks

**Daily**:
- Monitor error logs for failed queries
- Check message delivery rate (are messages being created?)

**Weekly**:
- Review unread message counts (are students engaging?)
- Archive or delete very old messages (6+ months)

**Monthly**:
- Optimize database tables (OPTIMIZE TABLE)
- Review and clean up test messages
- Check disk space if storing attachments (future feature)

**Quarterly**:
- Review and update documentation
- Security audit (SQL injection, XSS testing)
- Performance testing with large datasets

---

## System Requirements

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB 10.2+)
- PDO extension enabled
- Session support enabled

### Browser Requirements
- Modern browser with JavaScript enabled
- localStorage support (for theme toggle)
- CSS3 support (for animations and flexbox)

### Dependencies
- Bootstrap 5.3.3 (CSS framework)
- Bootstrap Icons (icon library)
- Marked.js (Markdown parser)
- Chart.js (if using dashboard charts)

---

## Contact & Support

For questions or issues with the messaging system:
1. Check this documentation first
2. Review troubleshooting guide
3. Check browser console for JavaScript errors
4. Review PHP error logs for server-side issues
5. Test queries directly in phpMyAdmin

**Common Debugging Checklist**:
- [ ] User is logged in with valid session
- [ ] Database tables exist with correct names
- [ ] Foreign keys reference correct tables
- [ ] JavaScript libraries loading from CDN
- [ ] No console errors
- [ ] executeQuery() function is available
- [ ] e() helper function is defined

---

## Appendix: Key SQL Queries

### Get All Messages for Student
```sql
SELECT DISTINCT m.*, 
       (SELECT COUNT(*) FROM broadcast_message_reads 
        WHERE message_id = m.id AND student_id = ?) as is_read
FROM broadcast_messages m
WHERE (
    m.target_all_students = 1
    OR FIND_IN_SET(?, m.target_student_ids) > 0
    OR EXISTS (
        SELECT 1 FROM enrollments e 
        WHERE e.student_id = ? 
        AND FIND_IN_SET(e.course_id, m.target_course_ids) > 0
    )
)
ORDER BY m.created_at DESC
```

### Mark Message as Read
```sql
INSERT INTO broadcast_message_reads (message_id, student_id) 
VALUES (?, ?)
```

### Create New Message
```sql
INSERT INTO broadcast_messages 
(title, content, message_type, link_url, link_text, 
 target_all_students, target_course_ids, target_student_ids, 
 created_by, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
```

---

**Document Version**: 1.0  
**Last Updated**: 2025  
**System Version**: SLS EduHub Messaging v1.0