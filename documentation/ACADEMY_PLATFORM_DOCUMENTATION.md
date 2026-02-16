# Academy Platform - Complete Technical Documentation

**Last Updated**: November 23, 2025  
**Version**: 2.0  
**Author**: System Administrator

---

## Table of Contents

1. [File Structure Overview](#file-structure-overview)
2. [Core System Files](#core-system-files)
3. [Authentication System](#authentication-system)
4. [API & Rate Limiting](#api--rate-limiting)
5. [Essay Analyzer](#essay-analyzer)
6. [Audio Analyzer](#audio-analyzer)
7. [Common Issues & Solutions](#common-issues--solutions)
8. [Adding New Features](#adding-new-features)
9. [Environment Configuration](#environment-configuration)

---

## File Structure Overview

```
ROOT/
├── config/                                    (Shared configuration - sibling to academy)
│   ├── api_keys.php                          (API keys & rate limit settings)
│   ├── db_connect.php                        (Database connection)
│   ├── email_helper.php                      (Email functions)
│   └── edu_hub_registration_handler.php      (Registration/login logic)
│
└── academy.slslanguage.com/                  (Academy platform)
    ├── bootstrap.php                         ⭐ MAIN INIT FILE
    ├── paths.php                             (Path definitions)
    ├── process_registration.php              (Registration form handler)
    ├── edu_hub_registration.php              (Registration/login UI)
    ├── learning_dashboard.php                (Student dashboard)
    │
    ├── api/
    │   └── api_handler.php                   (Backend API for analyzers)
    │
    ├── includes/
    │   ├── rate_limiter.php                  (Rate limiting class)
    │   ├── navbar.php                        (Navigation bar)
    │   ├── navbar_styles.php                 (Navigation styles)
    │   ├── navbar_scripts.php                (Navigation scripts)
    │   ├── topbar.php                        (Top bar)
    │   ├── mobile_header.php                 (Mobile header)
    │   └── adverts.php                       (Advertisement section)
    │
    ├── resources/
    │   ├── essay_analyzer.php                (Essay analysis tool with word counter)
    │   ├── audio_analyzer.php                (Speaking analysis tool)
    │   └── resources.php                     (Resources hub)
    │
    ├── courses/
    │   └── courses_detail.php
    │   └── (other course files)
    │
    └── assets/
        ├── css/
        │   ├── essay_analyzer.css
        │   ├── dashboard.css
        │   └── edu_hub_reg.css
        └── js/
```

---

## Core System Files

### 1. `/config/api_keys.php`

**Purpose**: Central storage for all API keys and configuration settings

**Location**: `/config/api_keys.php` (sibling to academy folder)

```php
<?php
// Prevent multiple inclusions
if (defined('API_KEYS_LOADED')) {
    return;
}
define('API_KEYS_LOADED', true);

// API Keys - Replace with your actual keys
if (!defined('OPENAI_API_KEY')) {
    define('OPENAI_API_KEY', 'sk-YOUR_OPENAI_KEY_HERE');
}
if (!defined('ANTHROPIC_API_KEY')) {
    define('ANTHROPIC_API_KEY', 'sk-ant-YOUR_ANTHROPIC_KEY_HERE');
}
if (!defined('GEMINI_API_KEY')) {
    define('GEMINI_API_KEY', 'YOUR_GEMINI_KEY_HERE');
}

// Rate Limiting Settings
if (!defined('MAX_REQUESTS_PER_USER_PER_DAY')) {
    define('MAX_REQUESTS_PER_USER_PER_DAY', 10);
}
if (!defined('MAX_REQUESTS_PER_USER_PER_HOUR')) {
    define('MAX_REQUESTS_PER_USER_PER_HOUR', 3);
}

// API Preferences
if (!defined('TRANSCRIPTION_API')) {
    define('TRANSCRIPTION_API', 'browser');  // 'browser' (free) or 'openai' (paid)
}
if (!defined('ANALYSIS_API')) {
    define('ANALYSIS_API', 'gemini');  // 'gemini' or 'claude'
}
```

**Key Features**:
- Uses `if (!defined())` to prevent redefinition errors
- Supports multiple API providers
- Configurable rate limits

**To Modify**:
1. Update API keys when you get new ones
2. Adjust rate limits: `MAX_REQUESTS_PER_USER_PER_DAY` and `MAX_REQUESTS_PER_USER_PER_HOUR`
3. Change AI provider: Set `ANALYSIS_API` to `'gemini'` or `'claude'`

---

### 2. `/academy.slslanguage.com/bootstrap.php`

**Purpose**: Central initialization file that loads everything the app needs

**⚠️ CRITICAL**: Every page in the academy should start with:
```php
<?php
require_once dirname(__DIR__) . '/bootstrap.php';  // If in subfolder
// OR
require_once __DIR__ . '/bootstrap.php';  // If in root
```

**Complete Code**:

```php
<?php
// /academy.slslanguage.com/bootstrap.php

// Load paths
require_once __DIR__ . '/paths.php';

// Load database connection
require_once CONFIG_PATH . '/db_connect.php';

// Load API keys configuration
if (file_exists(CONFIG_PATH . '/api_keys.php')) {
    require_once CONFIG_PATH . '/api_keys.php';
} else {
    // Fallback defaults if api_keys.php doesn't exist
    if (!defined('MAX_REQUESTS_PER_USER_PER_DAY')) {
        define('MAX_REQUESTS_PER_USER_PER_DAY', 10);
    }
    if (!defined('MAX_REQUESTS_PER_USER_PER_HOUR')) {
        define('MAX_REQUESTS_PER_USER_PER_HOUR', 3);
    }
}

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global database query function
function executeQuery($db, $sql, $params = []) {
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

// Global constants for the app
define('MAX_DESCRIPTION_LENGTH', 80);
define('MAX_COURSE_RECOMMENDATIONS', 3);
define('MAX_ASSIGNMENTS_DISPLAY', 5);
```

**What Bootstrap Provides** (automatically available after including):
- ✅ `$db` - PDO database connection
- ✅ `$_SESSION` - Session already started
- ✅ `CONFIG_PATH` - Path to `/config/` folder
- ✅ `ACADEMY_ROOT` - Path to `/academy.slslanguage.com/` folder
- ✅ `INCLUDES_PATH` - Path to `/academy.slslanguage.com/includes/`
- ✅ `ACADEMY_URL` - Web URL for creating links
- ✅ All API keys (GEMINI_KEY, OPENAI_KEY, etc.)
- ✅ Rate limit constants
- ✅ `executeQuery()` helper function

**Usage Example**:
```php
<?php
require_once __DIR__ . '/bootstrap.php';

// Now you can use:
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = executeQuery($db, $sql, [$user_id]);

// Access session
$user_id = $_SESSION['user_id'];

// Use paths
include INCLUDES_PATH . '/navbar.php';
```

---

### 3. `/academy.slslanguage.com/paths.php`

**Purpose**: Defines all file paths and URLs for the application

```php
<?php
// ABSOLUTE PATH to the academy root (local or live)
define('ACADEMY_ROOT', __DIR__);

// PATH to includes folder
define('INCLUDES_PATH', ACADEMY_ROOT . '/includes');

// PATH to config (shared folder - sibling to academy)
define('CONFIG_PATH', dirname(ACADEMY_ROOT) . '/config');

// WEB ROOT for academy (used for href links)
$academy_url = '/academy.slslanguage.com/'; // local
if (strpos($_SERVER['HTTP_HOST'], 'academy.') !== false) {
    $academy_url = '/'; // live domain root
}
define('ACADEMY_URL', $academy_url);
```

**Key Constants**:
- `ACADEMY_ROOT` - Absolute file system path to academy folder
- `INCLUDES_PATH` - Path to includes folder (for server-side includes)
- `CONFIG_PATH` - Path to config folder (sibling to academy)
- `ACADEMY_URL` - Web URL (automatically adjusts for local vs production)

**How to Use**:
```php
// For file includes (PHP require/include)
include INCLUDES_PATH . '/navbar.php';
require_once CONFIG_PATH . '/db_connect.php';

// For HTML links (href, src, action)
<a href="<?php echo ACADEMY_URL; ?>resources/essay_analyzer.php">Essay Analyzer</a>
<link href="<?php echo ACADEMY_URL; ?>assets/css/style.css" rel="stylesheet">
<form action="<?php echo ACADEMY_URL; ?>api/api_handler.php" method="post">
```

**Path Hierarchy for Different File Depths**:

| File Location | Include Bootstrap | Include Files from `includes/` |
|---------------|-------------------|-------------------------------|
| Root (`/academy.slslanguage.com/file.php`) | `require_once __DIR__ . '/bootstrap.php';` | `include INCLUDES_PATH . '/navbar.php';` |
| 1 level deep (`/resources/file.php`) | `require_once dirname(__DIR__) . '/bootstrap.php';` | `include INCLUDES_PATH . '/navbar.php';` |
| 2 levels deep (`/courses/lessons/file.php`) | `require_once dirname(dirname(__DIR__)) . '/bootstrap.php';` | `include INCLUDES_PATH . '/navbar.php';` |

**Note**: The `INCLUDES_PATH` constant never changes regardless of file depth!

---

## Authentication System

### Registration/Login Flow Diagram

```
User visits edu_hub_registration.php
        ↓
User submits registration/login form
        ↓
Form posts to process_registration.php
        ↓
process_registration.php loads bootstrap.php
        ↓
bootstrap.php loads:
  - paths.php (defines all paths)
  - db_connect.php (database connection)
  - api_keys.php (API keys)
  - Starts session
        ↓
process_registration.php includes edu_hub_registration_handler.php
        ↓
Handler validates data and:
  - Registration: Creates user, sends verification email
  - Login: Verifies credentials, sets session variables
        ↓
Redirect to appropriate page with status message
```

### File Locations

- **UI Form**: `/academy.slslanguage.com/edu_hub_registration.php`
- **Form Handler Bridge**: `/academy.slslanguage.com/process_registration.php`
- **Business Logic**: `/config/edu_hub_registration_handler.php`

---

### `/academy.slslanguage.com/process_registration.php`

**Purpose**: Bridge file that connects the registration form to the handler

```php
<?php
/**
 * Registration Bridge File
 * Location: /academy.slslanguage.com/process_registration.php
 */

// Log errors, don't display (prevents "headers already sent" errors)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Error: This page only processes form submissions.');
}

// Bootstrap is in the SAME directory as this file
require_once __DIR__ . '/bootstrap.php';

// Include the registration handler
$handler_path = CONFIG_PATH . '/edu_hub_registration_handler.php';

if (!file_exists($handler_path)) {
    die('Error: Registration handler not found at: ' . $handler_path);
}

require_once($handler_path);
```

**⚠️ IMPORTANT**: 
- Use `__DIR__ . '/bootstrap.php'` because both files are in the same directory
- Never use relative paths like `../../` here
- This file should NEVER output anything (no echo, no HTML) to avoid header issues

---

### `/config/edu_hub_registration_handler.php`

**Purpose**: Contains all registration and login business logic

**Key Features**:
- Email validation with domain verification
- Password hashing with bcrypt (cost 12)
- Email verification system
- Session management
- Environment-aware redirects

**Important Changes from Old System**:
- ❌ **Removed**: `require_once path_config.php` (bootstrap handles this)
- ❌ **Removed**: `session_start()` (bootstrap already started session)
- ❌ **Removed**: `IS_LOCAL` constant checks
- ✅ **Added**: Environment detection based on HTTP_HOST
- ✅ **Uses**: `$db` from bootstrap
- ✅ **Uses**: `$_SESSION` from bootstrap

**Environment Detection Logic**:
```php
$is_academy_domain = (strpos($_SERVER['HTTP_HOST'], 'academy.') !== false);
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

if ($is_local) {
    $redirect_base = '/academy.slslanguage.com/edu_hub_registration.php';
    $dashboard_page = '/academy.slslanguage.com/learning_dashboard.php';
} else if ($is_academy_domain) {
    $redirect_base = '/edu_hub_registration.php';
    $dashboard_page = '/learning_dashboard.php';
}
```

**Registration Process**:
1. Validate all fields are filled
2. Check email format and domain exists (DNS check)
3. Verify passwords match and meet length requirement (8+ chars)
4. Generate verification token
5. Hash password with bcrypt (cost 12)
6. Insert user into database with `is_verified = 0`
7. Send verification email
8. Redirect with success/error message

**Login Process**:
1. Validate email and password provided
2. Query database for user by email
3. Verify password with `password_verify()`
4. Check if email is verified (`is_verified = 1`)
5. Set session variables
6. Redirect to dashboard

**Session Variables Set on Login**:
```php
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_firstname'] = $user['firstname'];
$_SESSION['user_lastname'] = $user['lastname'];
$_SESSION['email_verified'] = 1;
```

---

## API & Rate Limiting

### System Architecture

```
Frontend (essay_analyzer.php, audio_analyzer.php)
        ↓
    Submits data via fetch() to
        ↓
Backend (api/api_handler.php)
        ↓
    Checks rate limit
        ↓
    Calls AI API (Gemini/Claude/OpenAI)
        ↓
    Logs request to database
        ↓
    Returns response to frontend
```

---

### `/academy.slslanguage.com/api/api_handler.php`

**Purpose**: Backend API that handles all AI analysis requests securely

**Key Features**:
- Hides API keys from frontend
- Enforces rate limiting
- Logs all API usage
- Supports multiple AI providers
- Returns JSON responses

**Supported Actions**:
- `analyze_essay` - Analyze essay with AI
- `transcribe_audio` - Transcribe audio with Whisper (if configured)
- `analyze_speaking` - Analyze speaking with AI
- `check_rate_limit` - Get user's current usage stats

**Example Request** (from frontend):
```javascript
const response = await fetch('/api/api_handler.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        action: 'analyze_essay',
        question: 'Essay question here...',
        essay: 'Essay text here...',
        exam_type: 'IELTS'
    })
});

const data = await response.json();
if (data.success) {
    console.log(data.feedback);
    console.log(data.remaining); // Remaining daily requests
}
```

**Response Format**:
```json
{
    "success": true,
    "feedback": "AI analysis text here...",
    "remaining": 8
}
```

**Error Response**:
```json
{
    "error": "Rate limit exceeded. Try again in 3600 seconds.",
    "reset_time": "2025-11-24 10:00:00"
}
```

**Switching AI Providers**:

Edit `/config/api_keys.php`:
```php
// Use Gemini (default)
define('ANALYSIS_API', 'gemini');

// OR use Claude
define('ANALYSIS_API', 'claude');
```

**API Functions in Handler**:
- `callGemini($prompt)` - Calls Gemini API
- `callClaude($prompt)` - Calls Claude API
- `callAI($prompt, $provider)` - Calls specified provider
- `transcribeWithWhisper($audioFilePath)` - Transcribes audio

**⚠️ Security Note**: 
- API keys are NEVER exposed to the browser
- All AI calls happen on the server side
- Rate limiting prevents abuse

---

### `/academy.slslanguage.com/includes/rate_limiter.php`

**Purpose**: Class-based rate limiting system with database logging

**Database Table** (auto-created):
```sql
CREATE TABLE api_usage_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    api_type VARCHAR(50) NOT NULL,
    endpoint VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_time (user_id, created_at),
    INDEX idx_user_type (user_id, api_type)
)
```

**Usage Example**:
```php
require_once INCLUDES_PATH . '/rate_limiter.php';

$rateLimiter = new RateLimiter($db);

// Check if user can make request
$limitCheck = $rateLimiter->checkLimit($user_id, 'essay_analysis');

if (!$limitCheck['allowed']) {
    // User exceeded limit
    echo $limitCheck['message'];
    echo "Try again at: " . $limitCheck['reset_time'];
    exit();
}

// Process the request...

// Log the successful request
$rateLimiter->logRequest($user_id, 'essay_analysis', 'analyze');

// Get user stats
$stats = $rateLimiter->getUserStats($user_id);
echo "Used: " . $stats['daily_used'] . " / " . $stats['daily_limit'];
echo "Remaining: " . $stats['daily_remaining'];
```

**Methods**:
- `checkLimit($userId, $apiType)` - Check if user can make request
- `logRequest($userId, $apiType, $endpoint)` - Log successful request
- `getUserStats($userId)` - Get usage statistics

**Configuration**:

Edit limits in `/config/api_keys.php`:
```php
define('MAX_REQUESTS_PER_USER_PER_DAY', 10);   // Daily limit
define('MAX_REQUESTS_PER_USER_PER_HOUR', 3);   // Hourly limit
```

---

## Essay Analyzer

### File Location
`/academy.slslanguage.com/resources/essay_analyzer.php`

### Features
1. ✅ Real-time word counter with color coding
2. ✅ Grammar checking via LanguageTool API (free)
3. ✅ AI analysis with Gemini
4. ✅ Rate limiting display
5. ✅ Practice mode support (with timer and charts)
6. ✅ IELTS & CELPIP exam types

### Word Counter Color Coding

**Practice Mode**:
- 🟡 Yellow: < 80% of target words
- 🟢 Green: 80-120% of target (ideal)
- 🔴 Red: > 120% of target

**Normal Mode (IELTS)**:
- 🟡 Yellow: < 250 words (below minimum)
- 🟢 Green: 250-350 words (ideal range)
- ⚪ Default: 350+ words (acceptable but long)

### Practice Mode

**Accessing from URL**:
```
essay_analyzer.php?type=ielts_task2&question=Your+question+here&time=40&words=250&title=Practice+Test
```

**URL Parameters**:
- `type` - Test type (ielts_task2, celpip_writing, etc.)
- `question` - Essay question (URL encoded)
- `time` - Time limit in minutes (default: 40)
- `words` - Target word count (default: 250)
- `title` - Test title (default: Practice Test)
- `testType` - Display label (default: Writing Test)
- `visualType` - 'chart' or 'image' (for Task 1)
- `chartConfig` - JSON chart configuration
- `imageUrl` - URL to diagram/image
- `imageAlt` - Alt text for image

**Practice Mode Features**:
- Timer with start/pause functionality
- Question locked (read-only)
- Word target display
- Color-coded word counter based on target
- Chart/image visualization support
- Alarm when time expires

### API Integration

**Current Setup** (temporary for demo):
```javascript
// Calls Gemini directly from frontend
const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent?key=${GEMINI_KEY}`, {
    method: "POST",
    body: JSON.stringify({...})
});
```

**Switching to Backend** (recommended for production):

Replace the analyze function in essay_analyzer.php:
```javascript
// Remove direct Gemini call, use backend instead
const response = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        action: 'analyze_essay',
        question: question,
        essay: essay,
        exam_type: selectedExam
    })
});
```

### Adding to Resources Page

In `/academy.slslanguage.com/resources/resources.php`:
```html
<a href="essay_analyzer.php" class="text-decoration-none">
    <div class="resource-card" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
        <div class="card-body">
            <i class="bi bi-pencil-square card-icon"></i>
            <h3 class="card-title">Essay Analyzer</h3>
            <p class="card-text">Get instant band scores</p>
        </div>
    </div>
</a>
```

---

## Audio Analyzer

### File Location
`/academy.slslanguage.com/resources/audio_analyzer.php`

### Features
1. ✅ Browser-based recording (MediaRecorder API)
2. ✅ Real-time transcription (Web Speech API - FREE)
3. ✅ Live transcription preview
4. ✅ AI analysis with Gemini
5. ✅ Timer during recording
6. ✅ Rate limiting display
7. ✅ Audio playback before analysis

### Transcription Methods

**Method 1: Web Speech API (Currently Used - FREE)**
- Built into Chrome, Edge, Safari
- Real-time transcription as user speaks
- Shows live preview during recording
- No API costs
- Requires internet connection
- Works in: Chrome ✅, Edge ✅, Safari ✅, Firefox ⚠️ (limited)

**Method 2: OpenAI Whisper (Optional - PAID)**
- More accurate transcription
- Costs ~$0.006 per minute
- Supports more languages
- Processes after recording completes

**Switching to Whisper**:

1. Add OpenAI key to `/config/api_keys.php`:
```php
define('OPENAI_API_KEY', 'sk-YOUR_KEY_HERE');
define('TRANSCRIPTION_API', 'openai');
```

2. Update audio_analyzer.php to use backend:
```javascript
// Instead of using Web Speech API
const response = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
    method: 'POST',
    body: JSON.stringify({
        action: 'transcribe_audio',
        audio_base64: audioBase64
    })
});
```

### Troubleshooting Audio Analyzer

**Issue: "No transcription available"**

Solutions:
1. Check browser compatibility (use Chrome/Edge)
2. Grant microphone permissions
3. Speak clearly and loudly
4. Check live transcription preview appears
5. Ensure internet connection (Speech API needs it)

**Issue: Microphone not working**

1. Check browser permissions: `chrome://settings/content/microphone`
2. Test microphone in system settings
3. Try different browser
4. Check HTTPS (Speech API requires secure context)

---

## Common Issues & Solutions

### Issue 1: "Headers Already Sent" Error

**Cause**: PHP output before header() redirect

**Solution**:
```php
// At top of any file that redirects
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Don't display errors
ini_set('log_errors', 1);       // Log them instead
```

**Check for**:
- Echo statements before redirects
- Whitespace before `<?php`
- BOM (Byte Order Mark) in files
- Warning/Notice messages

---

### Issue 2: "Bootstrap file not found"

**Cause**: Wrong path to bootstrap.php

**Solution**: Count folder depth from academy root

```php
// Same directory as bootstrap
require_once __DIR__ . '/bootstrap.php';

// One folder deep (e.g., /resources/file.php)
require_once dirname(__DIR__) . '/bootstrap.php';

// Two folders deep (e.g., /courses/lessons/file.php)
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
```

---

### Issue 3: "CONFIG_PATH already defined"

**Cause**: Multiple includes of path_config.php or api_keys.php

**Solution**: All files now use this pattern:
```php
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', 'value');
}
```

**Prevention**: Always use bootstrap.php, never load configs directly

---

### Issue 4: "Session already started"

**Cause**: Multiple session_start() calls

**Solution**: Bootstrap handles sessions. Never call session_start() in your files.

If you must check:
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

---

### Issue 5: Rate Limit Errors

**Symptoms**: 
- "Quota exceeded for metric"
- "You exceeded your current quota"

**Solutions**:

1. **Gemini API**: Switch model in `/config/api_keys.php`:
```php
// In api_handler.php, change:
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent...';
// Instead of gemini-2.0-flash-exp
```

2. **Check your rate limiter settings**:
```php
define('MAX_REQUESTS_PER_USER_PER_DAY', 10);  // Increase if needed
```

3. **View user's usage**:
```php
$stats = $rateLimiter->getUserStats($user_id);
print_r($stats);
```

---

### Issue 6: Double Slashes in URLs (e.g., `//resources/`)

**Cause**: ACADEMY_URL has trailing slash, you add leading slash

**Bad**:
```php
<a href="<?php echo ACADEMY_URL; ?>/resources/file.php">  <!-- Creates // -->
```

**Good**:
```php
<a href="<?php echo ACADEMY_URL; ?>resources/file.php">  <!-- Correct -->
```

**Helper Function** (add to bootstrap.php):
```php
function academy_url($path) {
    return rtrim(ACADEMY_URL, '/') . '/' . ltrim($path, '/');
}

// Usage:
echo academy_url('/resources/file.php');  // Always correct
```

---

## Adding New Features

### Creating a New Tool/Analyzer

**Step 1**: Create the file
```
/academy.slslanguage.com/resources/my_new_tool.php
```

**Step 2**: Add bootstrap at top
```php
<?php
require_once dirname(__DIR__) . '/bootstrap.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ACADEMY_URL . "edu_hub_registration.php?message=Please+login");
    exit();
}

// Get rate limit stats (optional)
require_once INCLUDES_PATH . '/rate_limiter.php';
$rateLimiter = new RateLimiter($db);
$stats = $rateLimiter->getUserStats($_SESSION['user_id']);
?>
```

**Step 3**: Add includes
```php
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    
    <main class="main-wrapper">
        <!-- Your content here -->
    </main>
    
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>
```

**Step 4**: Add to resources page
```php
// In /resources/resources.php
<a href="my_new_tool.php" class="text-decoration-none">
    <div class="resource-card" style="background: linear-gradient(135deg, #color1, #color2);">
        <div class="card-body">
            <i class="bi bi-icon-name card-icon"></i>
            <h3 class="card-title">My New Tool</h3>
            <p class="card-text">Description</p>
        </div>
    </div>
</a>
```

---

### Adding API Endpoint

**Step 1**: Add action handler in `/api/api_handler.php`

```php
// In the switch statement, add:
case 'my_new_action':
    handleMyNewAction($input, $userId, $rateLimiter);
    break;
```

**Step 2**: Create handler function

```php
function handleMyNewAction($input, $userId, $rateLimiter) {
    // Check rate limit
    $limitCheck = $rateLimiter->checkLimit($userId, 'my_action');
    if (!$limitCheck['allowed']) {
        http_response_code(429);
        echo json_encode(['error' => $limitCheck['message']]);
        return;
    }
    
    // Validate input
    if (!isset($input['required_field'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required field']);
        return;
    }
    
    // Process the request
    $result = processMyAction($input);
    
    // Log the request
    $rateLimiter->logRequest($userId, 'my_action', 'endpoint_name');
    
    // Return response
    echo json_encode([
        'success' => true,
        'data' => $result,
        'remaining' => $limitCheck['remaining']
    ]);
}
```

**Step 3**: Call from frontend

```javascript
const response = await fetch('<?php echo ACADEMY_URL; ?>api/api_handler.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        action: 'my_new_action',
        required_field: 'value'
    })
});

const data = await response.json();
if (data.success) {
    console.log(data.data);
}
```

---

### Adding a New Include File

**Example**: Creating a footer

**Step 1**: Create `/academy.slslanguage.com/includes/footer.php`

```php
<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Academy Platform. All rights reserved.</p>
    </div>
</footer>
```

**Step 2**: Use in pages

```php
<?php include INCLUDES_PATH . '/footer.php'; ?>
```

**Always use INCLUDES_PATH**, never relative paths like `../includes/`

---

## Environment Configuration

### Local vs Production Detection

The system automatically detects environment based on:

```php
// Check if on academy subdomain
$is_academy_domain = (strpos($_SERVER['HTTP_HOST'], 'academy.') !== false);

// Check if local
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || 
             strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
```

### Environment-Specific Settings

**Local Environment**:
- Domain: `localhost:8888` or `127.0.0.1`
- Academy path: `/academy.slslanguage.com/`
- Document root: `/Applications/MAMP/htdocs/` (Mac) or `C:/xampp/htdocs/` (Windows)

**Production Environment**:
- Domain: `academy.slslanguage.com`
- Academy path: `/`
- Document root: `/home/slslanguage/academy.slslanguage.com/`

### URL Building

**Wrong** (breaks between environments):
```php
<a href="/academy/resources/file.php">  <!-- Only works locally -->
```

**Correct** (works everywhere):
```php
<a href="<?php echo ACADEMY_URL; ?>resources/file.php">
```

### Path Building

**Wrong**:
```php
include '../includes/navbar.php';  // Breaks when file moves
```

**Correct**:
```php
include INCLUDES_PATH . '/navbar.php';  // Always works
```

---

## Database Schema

### Key Tables

**students** - User accounts
```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phonenumber VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(255),
    token_created_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**api_usage_log** - Rate limiting & analytics
```sql
CREATE TABLE api_usage_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    api_type VARCHAR(50) NOT NULL,
    endpoint VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_time (user_id, created_at),
    INDEX idx_user_type (user_id, api_type),
    FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE
);
```

**courses** - Course catalog
```sql
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    thumbnail VARCHAR(255),
    total_lessons INT DEFAULT 0,
    total_hours DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**enrollments** - Student course enrollments
```sql
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    progress_percentage DECIMAL(5,2) DEFAULT 0,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);
```

---

## Security Best Practices

### 1. Input Validation

**Always validate and sanitize user input**:

```php
// Validate email
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    die('Invalid email');
}

// Sanitize strings
$name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');

// Validate integers
$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$user_id) {
    die('Invalid user ID');
}
```

### 2. SQL Injection Prevention

**Always use prepared statements**:

```php
// ❌ NEVER DO THIS
$sql = "SELECT * FROM students WHERE email = '$email'";

// ✅ ALWAYS DO THIS
$sql = "SELECT * FROM students WHERE email = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$email]);
```

### 3. Password Security

**Use password_hash and password_verify**:

```php
// Registration
$hashed = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

// Login
if (password_verify($input_password, $stored_hash)) {
    // Valid password
}
```

### 4. Session Security

**Regenerate session ID after login**:

```php
session_start();
session_regenerate_id(true);
$_SESSION['user_id'] = $user_id;
```

### 5. API Key Security

**Never expose API keys in frontend**:

```javascript
// ❌ BAD - Key visible in browser
const GEMINI_KEY = "AIzaSy...";
fetch(`https://api.google.com?key=${GEMINI_KEY}`);

// ✅ GOOD - Use backend
fetch('/api/api_handler.php', {
    method: 'POST',
    body: JSON.stringify({ action: 'analyze_essay' })
});
```

### 6. File Upload Security

If implementing file uploads:

```php
// Validate file type
$allowed = ['jpg', 'jpeg', 'png', 'pdf'];
$extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowed)) {
    die('Invalid file type');
}

// Validate file size (5MB max)
if ($_FILES['file']['size'] > 5 * 1024 * 1024) {
    die('File too large');
}

// Generate random filename
$filename = bin2hex(random_bytes(16)) . '.' . $extension;
move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $filename);
```

### 7. CSRF Protection

For forms that modify data:

```php
// Generate token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// In form
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

// Validate on submission
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Invalid request');
}
```

---

## Performance Optimization

### 1. Database Query Optimization

**Use indexes**:
```sql
-- Add indexes for frequently queried columns
CREATE INDEX idx_email ON students(email);
CREATE INDEX idx_user_course ON enrollments(student_id, course_id);
CREATE INDEX idx_created ON api_usage_log(created_at);
```

**Limit query results**:
```php
// Don't fetch all rows
$sql = "SELECT * FROM courses ORDER BY created_at DESC LIMIT 10";
```

### 2. Caching

**Cache API responses** (add to bootstrap.php):

```php
function getCachedApiResponse($key, $callback, $ttl = 3600) {
    // Simple file-based cache
    $cache_file = sys_get_temp_dir() . '/cache_' . md5($key);
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $ttl) {
        return unserialize(file_get_contents($cache_file));
    }
    
    $data = $callback();
    file_put_contents($cache_file, serialize($data));
    return $data;
}

// Usage:
$result = getCachedApiResponse('essay_prompt_123', function() {
    return callGemini($prompt);
}, 1800); // Cache for 30 minutes
```

### 3. Asset Optimization

**Minify CSS/JS in production**:
```html
<!-- Development -->
<link href="assets/css/style.css">

<!-- Production -->
<link href="assets/css/style.min.css">
```

**Use CDN for libraries**:
```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

### 4. Lazy Loading

**Lazy load images**:
```html
<img src="placeholder.jpg" data-src="actual-image.jpg" loading="lazy" alt="Description">
```

---

## Debugging & Logging

### Error Logging

**Enable error logging** (in bootstrap.php):

```php
// Log to file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_error.log');

// Custom logging function
function logDebug($message, $data = null) {
    $log_file = __DIR__ . '/logs/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message";
    
    if ($data !== null) {
        $log_message .= "\nData: " . print_r($data, true);
    }
    
    file_put_contents($log_file, $log_message . "\n\n", FILE_APPEND);
}

// Usage:
logDebug("User login attempt", ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR']]);
```

### SQL Query Debugging

```php
function executeQuery($db, $sql, $params = []) {
    try {
        $stmt = $db->prepare($sql);
        
        // Log query in development
        if (IS_LOCAL) {
            logDebug("SQL Query", ['query' => $sql, 'params' => $params]);
        }
        
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        logDebug("SQL Error", ['query' => $sql, 'error' => $e->getMessage()]);
        return false;
    }
}
```

### Frontend Debugging

**Add debug mode**:

```javascript
const DEBUG = window.location.hostname === 'localhost';

function debug(message, data) {
    if (DEBUG) {
        console.log(`[DEBUG] ${message}`, data);
    }
}

// Usage:
debug('Form submitted', { question, essay });
debug('API response', data);
```

---

## Backup & Deployment

### Daily Backup Script

**Create**: `/scripts/backup.sh`

```bash
#!/bin/bash

# Configuration
BACKUP_DIR="/home/backups/academy"
DB_USER="your_db_user"
DB_PASS="your_db_password"
DB_NAME="your_db_name"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /home/slslanguage/academy.slslanguage.com/

# Delete backups older than 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

**Make executable**:
```bash
chmod +x /scripts/backup.sh
```

**Add to crontab** (daily at 2 AM):
```bash
crontab -e
# Add line:
0 2 * * * /scripts/backup.sh
```

### Deployment Checklist

**Before deploying to production**:

- [ ] Test all features locally
- [ ] Update API keys in `/config/api_keys.php`
- [ ] Set `display_errors = 0` in all files
- [ ] Enable error logging
- [ ] Test database connection
- [ ] Verify file permissions (644 for files, 755 for folders)
- [ ] Check `.htaccess` security rules
- [ ] Test registration/login flow
- [ ] Test essay analyzer
- [ ] Test audio analyzer
- [ ] Verify rate limiting works
- [ ] Check all email notifications
- [ ] Test on mobile devices
- [ ] Run backup before deployment
- [ ] Update this documentation

### Production Environment Variables

**Create**: `/config/environment.php`

```php
<?php
// Production environment settings
define('ENVIRONMENT', 'production'); // or 'development'
define('DEBUG_MODE', false);
define('DISPLAY_ERRORS', false);
define('LOG_ERRORS', true);
define('ERROR_LOG_PATH', '/home/slslanguage/logs/php_error.log');

// Feature flags
define('MAINTENANCE_MODE', false);
define('ALLOW_REGISTRATION', true);
define('REQUIRE_EMAIL_VERIFICATION', true);

// Email settings
define('SMTP_HOST', 'smtp.your-domain.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'noreply@slslanguage.com');
define('SMTP_FROM_NAME', 'Academy Platform');
```

**Load in bootstrap.php**:
```php
if (file_exists(CONFIG_PATH . '/environment.php')) {
    require_once CONFIG_PATH . '/environment.php';
}
```

---

## Monitoring & Analytics

### Track User Activity

**Add to database**:
```sql
CREATE TABLE user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type VARCHAR(50) NOT NULL,
    activity_data JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_type (activity_type),
    INDEX idx_created (created_at)
);
```

**Log activity**:
```php
function logActivity($user_id, $type, $data = null) {
    global $db;
    
    $sql = "INSERT INTO user_activity (user_id, activity_type, activity_data, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $user_id,
        $type,
        json_encode($data),
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
}

// Usage:
logActivity($_SESSION['user_id'], 'essay_analyzed', ['words' => 280, 'score' => 7.5]);
logActivity($_SESSION['user_id'], 'login', ['method' => 'email']);
```

### Analytics Dashboard

**Create**: `/admin/analytics.php`

```php
<?php
require_once dirname(__DIR__) . '/bootstrap.php';

// Admin check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die('Access denied');
}

// Get statistics
$stats = [
    'total_users' => $db->query("SELECT COUNT(*) FROM students")->fetchColumn(),
    'verified_users' => $db->query("SELECT COUNT(*) FROM students WHERE is_verified = 1")->fetchColumn(),
    'total_api_calls' => $db->query("SELECT COUNT(*) FROM api_usage_log")->fetchColumn(),
    'api_calls_today' => $db->query("SELECT COUNT(*) FROM api_usage_log WHERE DATE(created_at) = CURDATE()")->fetchColumn(),
];

// Popular features
$popular = $db->query("
    SELECT api_type, COUNT(*) as count 
    FROM api_usage_log 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAYS)
    GROUP BY api_type 
    ORDER BY count DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Analytics Dashboard</h1>
        
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Total Users</h5>
                        <h2><?php echo number_format($stats['total_users']); ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Verified Users</h5>
                        <h2><?php echo number_format($stats['verified_users']); ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>API Calls (Total)</h5>
                        <h2><?php echo number_format($stats['total_api_calls']); ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>API Calls (Today)</h5>
                        <h2><?php echo number_format($stats['api_calls_today']); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>Popular Features (Last 7 Days)</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Usage Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($popular as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['api_type']); ?></td>
                        <td><?php echo number_format($row['count']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
```

---

## Troubleshooting Reference

### Quick Diagnostic Commands

**Check file permissions**:
```bash
ls -la /home/slslanguage/academy.slslanguage.com/
```

**Check PHP errors**:
```bash
tail -f /home/slslanguage/logs/php_error.log
```

**Check database connection**:
```php
<?php
require_once 'config/db_connect.php';
echo $db ? "Connected" : "Failed";
```

**Test API key**:
```bash
curl -X POST "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=YOUR_KEY" \
  -H "Content-Type: application/json" \
  -d '{"contents":[{"parts":[{"text":"Hello"}]}]}'
```

### Common Error Messages

| Error | Cause | Solution |
|-------|-------|----------|
| `Headers already sent` | Output before redirect | Set `display_errors = 0` |
| `Bootstrap file not found` | Wrong path | Use `__DIR__ . '/bootstrap.php'` |
| `CONFIG_PATH already defined` | Multiple includes | Use `if (!defined())` |
| `Session already started` | Multiple session_start() | Bootstrap handles sessions |
| `Call to undefined function` | Missing bootstrap | Add `require_once bootstrap.php` |
| `PDO connection failed` | Database credentials | Check `/config/db_connect.php` |
| `Rate limit exceeded` | Too many requests | Wait or increase limits |
| `Invalid API key` | Wrong/expired key | Update in `/config/api_keys.php` |

---

## Version History

### Version 2.0 (November 23, 2025)
- Migrated to bootstrap.php system
- Added rate limiting with database logging
- Implemented secure API handler
- Added essay analyzer with word counter
- Added audio analyzer with Web Speech API
- Removed dependency on path_config.php
- Unified authentication system
- Added practice mode for analyzers

### Version 1.0 (Previous)
- Basic registration/login
- Course management
- Dashboard
- Used path_config.php

---

## Contact & Support

**Developer Notes Location**: `ACADEMY_PLATFORM_DOCUMENTATION.md`

**File Locations**:
- Documentation: `/academy.slslanguage.com/ACADEMY_PLATFORM_DOCUMENTATION.md`
- Error logs: `/logs/php_error.log`
- Debug logs: `/logs/debug.log`

**When Adding Features**:
1. Update this documentation
2. Test locally first
3. Check all file paths use constants
4. Verify rate limiting works
5. Test on mobile
6. Deploy to production
7. Monitor error logs

---

## Quick Reference Card

### Most Used Commands

```php
// Start any page
require_once __DIR__ . '/bootstrap.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ACADEMY_URL . "edu_hub_registration.php");
    exit();
}

// Include files
include INCLUDES_PATH . '/navbar.php';

// Database query
$stmt = executeQuery($db, "SELECT * FROM students WHERE id = ?", [$user_id]);

// Build URL
<a href="<?php echo ACADEMY_URL; ?>resources/file.php">

// Rate limiting
$rateLimiter = new RateLimiter($db);
$check = $rateLimiter->checkLimit($user_id, 'action_type');
if ($check['allowed']) {
    // Process request
    $rateLimiter->logRequest($user_id, 'action_type', 'endpoint');
}

// Log debug info
logDebug("Message", $data);
```

---

**END OF DOCUMENTATION**

*Save this file as: `/academy.slslanguage.com/ACADEMY_PLATFORM_DOCUMENTATION.md`*

*Keep it updated as you add new features!*