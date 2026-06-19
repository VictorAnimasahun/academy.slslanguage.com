<?php
// model_answers_library.php
// NOTE: Assuming bootstrap.php defines INCLUDES_PATH and handles session/login logic.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect logic remains, as this is a resource page requiring login
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// =========================================================================
// 1. MODEL ANSWERS DATA STRUCTURE 📚
// This is the core data driving the library.
// =========================================================================
$model_answers = [
    'ielts' => [
        'name' => 'IELTS',
        'icon' => 'bi-book',
        'color' => '#667eea',
        'sections' => [
            'academic_writing' => [
                'name' => 'Academic Writing',
                'categories' => [
                    'task1' => [
                        'name' => 'Task 1: Data Analysis (Academic)',
                        'questions' => [
                            ['id' => 1, 'title' => 'Bar Chart: Population Shifts', 'type' => 'Chart', 'url' => 'answer_display.php?q_id=1'],
                            ['id' => 2, 'title' => 'Process Diagram: Water Cycle', 'type' => 'Process', 'url' => 'answer_display.php?q_id=2'],
                        ]
                    ],
                    'task2' => [
                        'name' => 'Task 2: Essay (Academic)',
                        'questions' => [
                            ['id' => 3, 'title' => 'Opinion Essay on Remote Work', 'type' => 'Opinion', 'url' => 'answer_display.php?q_id=3'],
                        ]
                    ]
                ]
            ],
            'general_writing' => [
                'name' => 'General Writing',
                'categories' => [
                    'task1' => [
                        'name' => 'Task 1: Letter (General)',
                        'questions' => [
                            ['id' => 4, 'title' => 'Formal Complaint Letter (Band 9)', 'type' => 'Formal', 'url' => 'answer_display.php?q_id=4'],
                        ]
                    ]
                ]
            ],
            'speaking' => [
                'name' => 'Speaking',
                'categories' => [
                    'part2' => [
                        'name' => 'Part 2: Cue Card',
                        'questions' => [
                            ['id' => 5, 'title' => 'Describe an Interesting Place', 'type' => 'Experience', 'url' => 'answer_display.php?q_id=5'],
                        ]
                    ]
                ]
            ]
        ]
    ],
    'celpip' => [
        'name' => 'CELPIP',
        'icon' => 'bi-person-check',
        'color' => '#f43f5e',
        'sections' => [
            'writing' => [
                'name' => 'Writing',
                'categories' => [
                    'task1' => [
                        'name' => 'Task 1: Email (CLB 10)',
                        'questions' => [
                            ['id' => 6, 'title' => 'Formal Email to Manager', 'type' => 'Email', 'url' => 'answer_display.php?q_id=6'],
                        ]
                    ]
                ]
            ],
            'speaking' => [
                'name' => 'Speaking',
                'categories' => [
                    'practice' => [
                        'name' => 'Practice Tasks',
                        'questions' => [
                            ['id' => 7, 'title' => 'Giving Advice to a Friend', 'type' => 'Advice', 'url' => 'answer_display.php?q_id=7'],
                        ]
                    ]
                ]
            ]
        ]
    ],
    'pte' => [
        'name' => 'PTE',
        'icon' => 'bi-mortarboard',
        'color' => '#10b981',
        'sections' => [
            'writing' => [
                'name' => 'Writing',
                'categories' => [
                    'swt' => [
                        'name' => 'Summarize Written Text (SWT)',
                        'questions' => [
                            ['id' => 8, 'title' => 'SWT: Impact of Social Media', 'type' => 'Summary', 'url' => 'answer_display.php?q_id=8'],
                        ]
                    ]
                ]
            ],
            'speaking' => [
                'name' => 'Speaking',
                'categories' => [
                    'ra' => [
                        'name' => 'Read Aloud (RA)',
                        'questions' => [
                            ['id' => 9, 'title' => 'RA: The History of Space Travel', 'type' => 'Fluency', 'url' => 'answer_display.php?q_id=9'],
                        ]
                    ]
                ]
            ]
        ]
    ]
];
// =========================================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Answers Library | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php // include INCLUDES_PATH . '/navbar_styles.php'; // Included here for reference ?>

    <style>
        /* General Layout */
        body { background-color: #f7f9fc; }
        .main-wrapper { padding: 2rem 1.5rem; }
        .exercises-container { max-width: 1200px; margin: 0 auto; }
        
        /* Header */
        .page-header { margin-bottom: 3rem; text-align: center; }
        .page-header h1 { font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; }
        .page-header p { color: #6b7280; font-size: 1.1rem; }

        /* Test Category Tabs (Top Level) */
        .test-tabs { display: flex; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap; justify-content: center; }
        .test-tab {
            padding: 0.75rem 1.5rem; border: 2px solid #e5e7eb; border-radius: 8px;
            background: white; cursor: pointer; font-weight: 600; transition: all 0.3s ease;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .test-tab:hover { border-color: #667eea; color: #667eea; }
        .test-tab.active { background: #667eea; color: white; border-color: #667eea; }

        /* Section Tabs (Writing, Speaking, etc.) */
        .section-tabs {
            display: flex; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 2px solid #e5e7eb;
            overflow-x: auto; justify-content: flex-start;
        }
        .section-tab {
            padding: 0.75rem 1.2rem; border: none; background: transparent; cursor: pointer;
            font-weight: 500; color: #6b7280; border-bottom: 3px solid transparent;
            transition: all 0.3s ease; white-space: nowrap;
        }
        .section-tab:hover { color: #667eea; }
        .section-tab.active { color: #667eea; border-bottom-color: #667eea; }

        /* Content Display */
        .content-section { display: none; animation: fadeIn 0.3s ease; }
        .content-section.active { display: block; }
        
        /* Category Header (Task 1, Task 2, etc.) */
        h3 { 
            font-size: 1.4rem; font-weight: 600; color: #374151; margin-top: 2rem; margin-bottom: 1.5rem;
            border-left: 5px solid #667eea; padding-left: 10px;
        }

        /* Question Grid (repurposing exercise-grid style) */
        .questions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Question Card */
        .question-card {
            background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08); transition: all 0.3s ease;
            text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;
        }

        .question-card:hover { transform: translateY(-4px); box-shadow: 0 8px 16px rgba(0,0,0,0.12); }

        .card-header {
            padding: 1.2rem; background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; border-top-left-radius: 12px; border-top-right-radius: 12px;
        }

        .card-header h3 { font-size: 1.1rem; font-weight: 600; margin: 0; padding-left: 0; border-left: none; }

        .card-body { padding: 1.5rem; flex: 1; }

        .card-description { color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem; }

        .card-link {
            display: inline-block; padding: 0.5rem 1rem; background: #667eea;
            color: white; border-radius: 6px; text-decoration: none; font-weight: 500;
            transition: background 0.3s ease; text-align: center;
        }

        .card-link:hover { background: #764ba2; color: white; text-decoration: none; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Media Queries */
        @media (max-width: 768px) {
            .questions-grid { grid-template-columns: 1fr; }
            .test-tabs { justify-content: flex-start; overflow-x: auto; flex-wrap: nowrap; }
            .page-header h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>
    <?php 
    // You would include your site-specific components here:
    // include INCLUDES_PATH . '/mobile_header.php';
    // include INCLUDES_PATH . '/navbar.php'; 
    ?>

    <main class="main-wrapper">
        <div class="exercises-container">

            <div class="page-header">
                <h1><i class="bi bi-person-lines-fill"></i> Model Answers Library</h1>
                <p>Browse high-scoring answers for all major language tests</p>
            </div>

            <div class="test-tabs">
                <?php $is_first_test = true; ?>
                <?php foreach ($model_answers as $key => $test): ?>
                    <button class="test-tab <?php echo $is_first_test ? 'active' : ''; ?>" 
                            onclick="switchTest('<?php echo $key; ?>')">
                        <i class="bi <?php echo $test['icon']; ?>"></i>
                        **<?php echo $test['name']; ?>**
                    </button>
                    <?php $is_first_test = false; ?>
                <?php endforeach; ?>
            </div>

            <?php $is_first_test_content = true; ?>
            <?php foreach ($model_answers as $testKey => $test): ?>
                <div id="test-<?php echo $testKey; ?>" class="content-section <?php echo $is_first_test_content ? 'active' : ''; ?>">
                    
                    <div class="section-tabs">
                        <?php $is_first_section = true; ?>
                        <?php foreach ($test['sections'] as $sectionKey => $section): ?>
                            <button class="section-tab <?php echo $is_first_section ? 'active' : ''; ?>" 
                                    onclick="switchSection('<?php echo $testKey; ?>', '<?php echo $sectionKey; ?>')">
                                <?php echo $section['name']; ?>
                            </button>
                            <?php $is_first_section = false; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php $is_first_section_content = true; ?>
                    <?php foreach ($test['sections'] as $sectionKey => $section): ?>
                        <div id="section-<?php echo $testKey; ?>-<?php echo $sectionKey; ?>" 
                             class="content-section <?php echo $is_first_section_content ? 'active' : ''; ?>">
                            
                            <?php if (!empty($section['categories'])): ?>
                                
                                <?php foreach ($section['categories'] as $catKey => $category): ?>
                                    
                                    <h3><i class="bi bi-lightbulb"></i> <?php echo $category['name']; ?></h3>

                                    <div class="questions-grid">
                                        <?php foreach ($category['questions'] as $question): ?>
                                            <a href="<?php echo $question['url']; ?>" class="question-card">
                                                <div class="card-header">
                                                    <h3><?php echo $question['title']; ?></h3>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-description">Question Type: **<?php echo $question['type']; ?>**</p>
                                                    <div class="card-link">View Model Answers →</div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No model answers available yet for this section.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php $is_first_section_content = false; ?>
                    <?php endforeach; ?>

                </div>
                <?php $is_first_test_content = false; ?>
            <?php endforeach; ?>

        </div>
    </main>
    <?php 
    // include INCLUDES_PATH . '/adverts.php'; 
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php // include INCLUDES_PATH . '/navbar_scripts.php'; // Included here for reference ?>

    <script>
        // Set the initial active state for the first test content on page load
        document.addEventListener('DOMContentLoaded', () => {
            // Find the first test tab and simulate a click to initialize the sections
            const firstTestTab = document.querySelector('.test-tab.active');
            if (firstTestTab) {
                // Manually trigger the switch to ensure correct initial state
                const testKey = firstTestTab.textContent.trim().split(' ')[0].toLowerCase();
                
                // Set initial test visibility
                document.querySelectorAll('[id^="test-"]').forEach(el => el.classList.remove('active'));
                document.getElementById('test-' + testKey).classList.add('active');

                // Set initial section visibility (defaulting to the first section of the first active test)
                const firstSectionTab = document.querySelector(`#test-${testKey} .section-tab`);
                if (firstSectionTab) {
                    const sectionKey = firstSectionTab.textContent.trim().toLowerCase().replace(/\s+/g, '_');
                    
                    document.querySelectorAll('[id^="section-' + testKey + '-"]').forEach(el => el.classList.remove('active'));
                    document.getElementById('section-' + testKey + '-' + sectionKey).classList.add('active');
                }
            }
        });


        function switchTest(testKey) {
            // Hide all test content sections
            document.querySelectorAll('[id^="test-"]').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected test content
            const activeTestContent = document.getElementById('test-' + testKey);
            activeTestContent.classList.add('active');
            
            // Update test tab styling
            document.querySelectorAll('.test-tabs .test-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.closest('.test-tab').classList.add('active');

            // Find and activate the first section tab within the new test
            const firstSectionTab = activeTestContent.querySelector('.section-tabs .section-tab');
            if (firstSectionTab) {
                // Use the key derived from the switchSection logic
                const sectionKey = firstSectionTab.textContent.trim().toLowerCase().replace(/\s+/g, '_');
                
                // Manually call switchSection logic
                switchSection(testKey, sectionKey, firstSectionTab);
            }
        }

        function switchSection(testKey, sectionKey, targetElement = null) {
            // Determine the target element for styling if not passed directly (i.e., clicked)
            const clickedTab = targetElement || event.target;
            
            // Hide all section content for the current test
            document.querySelectorAll('[id^="section-' + testKey + '-"]').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show selected section content
            document.getElementById('section-' + testKey + '-' + sectionKey).classList.add('active');
            
            // Update section tab styling
            document.querySelectorAll('[id="test-' + testKey + '"] .section-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            clickedTab.classList.add('active');
        }
    </script>

</body>
</html>