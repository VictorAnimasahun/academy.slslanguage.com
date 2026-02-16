<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Mini Mock Test</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<style>
	.question-container {
		margin: 20px 0;
		padding: 15px;
		border-left: 3px solid var(--primary-color, #007bff);
		background-color: #f8f9fa;
	}
	.question-text {
		font-weight: bold;
		margin-bottom: 10px;
		color: #333;
	}
	.options {
		list-style: none;
		padding: 0;
		margin: 10px 0;
	}
	.options li {
		margin: 8px 0;
		padding: 8px;
		background-color: white;
		border: 1px solid #ddd;
		border-radius: 4px;
		cursor: pointer;
		transition: background-color 0.2s;
	}
	.options li:hover {
		background-color: #e9ecef;
	}
	.options input[type="radio"] {
		margin-right: 10px;
	}
	.question-number {
		color: var(--primary-color, #007bff);
		font-size: 0.9em;
		margin-bottom: 5px;
	}
	</style>
</head>
<body>
	<!-- ===== QUIZ HEADER ===== -->
	<header class="quiz-header">
		<div class="quiz-logo">
			<img src="images/Logo5.png" alt="SLS Logo" onerror="this.style.display='none'">
		</div>
		<div id="quiz-timer">
			Time Left: <span id="time">60:00</span>
		</div>
	</header>
    
    <div class="container">
        <!-- Introduction Page -->
        <div id="intro" class="page active">
            <h1>CELPIP Mini Mock Test</h1>
            <div class="intro-text">
                <p>Welcome to the CELPIP Mini Mock Test. This test simulates parts of the CELPIP exam so you can practice under timed conditions. The full CELPIP exam has four sections (Listening, Reading, Writing, and Speaking). This Mock Test will also evaluate the same sections.</p>
                <br>
                <p>You have a total of 1 hour to complete all four sections. The timer will begin counting down as soon as you click the button below:</p>
                <ul style="text-align: left; max-width: 500px; margin: 20px auto;">
                    <li>Listening (10 questions)</li>
                    <li>Reading (6 questions)</li>
                    <li>Writing (1 task)</li>
                    <li>Speaking (examiner administered)</li>
                </ul>
                <br>
                <p>Click on the <strong>Start Test</strong> button below when you are ready to begin. At the end, you'll receive a detailed assessment of your English level and personalized feedback on areas for improvement.</p>
            </div>
            <div class="navigation">
                <div></div>
                <button class="btn btn-primary" onclick="startQuiz()">Start Test</button>
            </div>
        </div>

		<!-- Section 1: Listening -->
		<div id="section1" class="page">
			<div class="progress-bar">
				<div class="progress" style="width: 25%"></div>
			</div>

			<h2>Section 1: Listening</h2>
			
			<!-- Audio Player Section -->
			<div class="audio-section">
			<div class="wrap">
				<div class="card" style="border-left:4px solid var(--primary-color);">
				<h3 style="color:var(--primary-color); margin-bottom:15px;">🎧 Audio Instructions</h3>

				<div class="audio-info">
					<p><strong>Important:</strong> Click the play button on the video below to begin listening. You can play the video only ONCE for this test - just like the real CELPIP test.</p>
					<p>Listen carefully and answer the questions as you hear the information. The relevant discussion begins at 2:18 in the video.</p>
					<p><em>Note: This video is used for educational purposes only.</em></p>
				</div>

				<!-- YouTube Video Embed (Audio Only Style) -->
				<div id="youtubePlayer" style="max-width: 100%; margin: 15px 0;">
					<iframe 
						id="listeningVideo"
						width="100%" 
						height="315" 
						src="https://www.youtube.com/embed/elNy7TmUvWA?start=138&enablejsapi=1&controls=1&rel=0&modestbranding=1" 
						title="Universal Basic Income Discussion" 
						frameborder="0" 
						allow="autoplay; encrypted-media" 
						allowfullscreen>
					</iframe>
				</div>
				
				<!-- Backup Audio Element (for future local files) -->
				<audio id="listeningAudio" preload="auto" style="display: none;">
					<source src="audio/celpip_ubi_audio.mp3" type="audio/mpeg">
					Your browser does not support the audio element.
				</audio>

				<!-- Single, canonical status block (no duplicate ids) -->
				<div class="audio-status" id="audioStatus">
					<div class="status-indicator">
					<span id="audioIndicator">⚪</span>
					<span id="audioText">Click "Start Listening" to begin</span>
					</div>
					<div id="audioStatusText" style="margin-bottom:8px; color:#444">Ready</div>
					<div class="audio-timer">Audio Time: <span id="audioTimeDisplay">00:00</span></div>
				</div>

				<div style="margin-top:12px;">
					<p><strong>Instructions:</strong> Use the YouTube player controls above to start the video. Remember: you should only play it once for test authenticity!</p>
					<button id="nextSectionBtn" onclick="goToSection(2)">Next Section →</button>
				</div>

				<div class="audio-warning" style="margin-top:14px;">
					<p style="color:#d32f2f; font-weight:bold;">⚠️ Test Rules: Play the video only once, then proceed to answer all questions before moving to the next section!</p>
				</div>
				</div>
			</div>
		</div>

		<!-- Questions Section -->
		<div class="wrap">
			<div class="card" role="main" aria-labelledby="title">
				<h1 id="title">Listening Section</h1>
				<div class="subtitle">Questions 1–10 — Choose the best answer for each question.</div>

				<h2 style="margin-top:6px;text-align:center">Universal Basic Income Discussion</h2>

				<div class="question-container">
					<div class="question-number">Question 1 of 10</div>
					<div class="question-text">Choose the best answer. What is Megan Gilmore's profession?</div>
					<ul class="options">
						<li><input type="radio" name="l1" id="l1a" value="A"> She is a government policy advisor.</li>
						<li><input type="radio" name="l1" id="l1b" value="B"> She is a journalist.</li>
						<li><input type="radio" name="l1" id="l1c" value="C"> She is a university researcher.</li>
						<li><input type="radio" name="l1" id="l1d" value="D"> She is a social worker.</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 2 of 10</div>
					<div class="question-text">Choose the best answer. Why was Ontario's UBI pilot program terminated?</div>
					<ul class="options">
						<li><input type="radio" name="l2" id="l2a" value="A"> The program was too expensive to maintain.</li>
						<li><input type="radio" name="l2" id="l2b" value="B"> Research results showed negative outcomes.</li>
						<li><input type="radio" name="l2" id="l2c" value="C"> A new government chose to end it.</li>
						<li><input type="radio" name="l2" id="l2d" value="D"> Participants requested program cancellation.</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 3 of 10</div>
					<div class="question-text">Choose the best answer. What was the intended duration of Ontario's pilot program?</div>
					<ul class="options">
						<li><input type="radio" name="l3" id="l3a" value="A"> One year</li>
						<li><input type="radio" name="l3" id="l3b" value="B"> Two years</li>
						<li><input type="radio" name="l3" id="l3c" value="C"> Three years</li>
						<li><input type="radio" name="l3" id="l3d" value="D"> Five years</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 4 of 10</div>
					<div class="question-text">Choose the best answer. According to the interview, what was lost when Ontario's program ended?</div>
					<ul class="options">
						<li><input type="radio" name="l4" id="l4a" value="A"> Comprehensive research data about UBI impacts</li>
						<li><input type="radio" name="l4" id="l4b" value="B"> Federal funding for future programs</li>
						<li><input type="radio" name="l4" id="l4c" value="C"> Public support for basic income</li>
						<li><input type="radio" name="l4" id="l4d" value="D"> Employment opportunities for participants</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 5 of 10</div>
					<div class="question-text">Choose the best answer. How did participants react to the program's cancellation?</div>
					<ul class="options">
						<li><input type="radio" name="l5" id="l5a" value="A"> They expressed anger toward the government.</li>
						<li><input type="radio" name="l5" id="l5b" value="B"> They felt disappointed about lost opportunities.</li>
						<li><input type="radio" name="l5" id="l5c" value="C"> They were relieved the program ended.</li>
						<li><input type="radio" name="l5" id="l5d" value="D"> They organized protests against the decision.</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 6 of 10</div>
					<div class="question-text">Choose the best answer. Which Ontario municipalities have supported UBI initiatives?</div>
					<ul class="options">
						<li><input type="radio" name="l6" id="l6a" value="A"> Toronto, Ottawa, and Hamilton</li>
						<li><input type="radio" name="l6" id="l6b" value="B"> Kingston, London, and Windsor</li>
						<li><input type="radio" name="l6" id="l6c" value="C"> Guelph, Niagara Falls, and Waterloo</li>
						<li><input type="radio" name="l6" id="l6d" value="D"> Sudbury, Thunder Bay, and Barrie</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 7 of 10</div>
					<div class="question-text">Choose the best answer. What is the current status of British Columbia's UBI involvement?</div>
					<ul class="options">
						<li><input type="radio" name="l7" id="l7a" value="A"> The province has launched a pilot program.</li>
						<li><input type="radio" name="l7" id="l7b" value="B"> Some communities are considering municipal programs.</li>
						<li><input type="radio" name="l7" id="l7c" value="C"> The provincial government rejected UBI proposals.</li>
						<li><input type="radio" name="l7" id="l7d" value="D"> BC is waiting for federal approval to proceed.</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 8 of 10</div>
					<div class="question-text">Choose the best answer. What did the 2021 federal bills propose?</div>
					<ul class="options">
						<li><input type="radio" name="l8" id="l8a" value="A"> Immediate implementation of UBI across Canada</li>
						<li><input type="radio" name="l8" id="l8b" value="B"> Specific payment amounts for program participants</li>
						<li><input type="radio" name="l8" id="l8c" value="C"> Creation of a framework for basic income</li>
						<li><input type="radio" name="l8" id="l8d" value="D"> Replacement of existing social assistance programs</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 9 of 10</div>
					<div class="question-text">Choose the best answer. According to Gilmore, what typically happens to UBI payments when recipients work?</div>
					<ul class="options">
						<li><input type="radio" name="l9" id="l9a" value="A"> Payments increase to encourage employment.</li>
						<li><input type="radio" name="l9" id="l9b" value="B"> Payments remain unchanged regardless of earnings.</li>
						<li><input type="radio" name="l9" id="l9c" value="C"> Payments are reduced based on employment income.</li>
						<li><input type="radio" name="l9" id="l9d" value="D"> Payments are suspended until employment ends.</li>
					</ul>
				</div>

				<div class="question-container">
					<div class="question-number">Question 10 of 10</div>
					<div class="question-text">Choose the best answer. What does Gilmore suggest people should monitor regarding UBI's future?</div>
					<ul class="options">
						<li><input type="radio" name="l10" id="l10a" value="A"> Provincial election outcomes and policy changes</li>
						<li><input type="radio" name="l10" id="l10b" value="B"> Senate committee decisions and disability benefit discussions</li>
						<li><input type="radio" name="l10" id="l10c" value="C"> Public opinion polls and media coverage</li>
						<li><input type="radio" name="l10" id="l10d" value="D"> Economic indicators and employment rates</li>
					</ul>
				</div>
				
				<div class="note">
					<p><strong>Tip:</strong> You can change your answers by selecting a different option. Only one answer per question is allowed.</p>
					<p><strong>Audio Status:</strong> <span id="audioStatusTextDuplicate">Ready to play</span></p>
				</div>
			</div>
		</div>

		<div class="navigation">
			<button class="btn btn-secondary" onclick="goToPage('intro')">← Back</button>
			<button class="btn btn-primary" onclick="goToSection(2)" id="nextSectionBtnDuplicate">Next Section →</button>
		</div>
	</div>

        <!-- Section 2: Reading -->
        <div id="section2" class="page">
            <div class="progress-bar">
                <div class="progress" style="width: 50%"></div>
            </div>
            <h2>Section 2: Reading</h2>
            <p style="margin-bottom: 25px; color: #666;">Reading Part 1: Reading Correspondence — 11 minutes</p>

			<div class="wrap">
    			<div class="card" role="main">
      				<h1>Reading Section</h1>
      				<div class="subtitle"><strong>Read the following message.</strong></div>

					<div class="passage">
						<div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #007bff; margin: 20px 0;">
							<p>Hi Sarah,</p>
							
							<p>I hope you're doing well! I wanted to reach out about the community garden project 
								we discussed at last month's neighborhood meeting. The city council has finally 
								approved our proposal, and we can start planting in the vacant lot behind the 
								community center next spring.</p>
							
							<p>We've received a grant of $3,500 from the Green Spaces Initiative, which should 
								cover most of our startup costs. I've been researching what vegetables grow best 
								in our climate zone, and I think we should focus on tomatoes, peppers, 
								and leafy greens for the first year. These are popular with families and 
								relatively easy to maintain.</p>
							
							<p>The plot will be divided into 20 individual garden beds, each measuring 4x6 feet. 
								We're charging a seasonal fee of $25 per bed to cover water costs and basic maintenance supplies. 
								So far, eight people have signed up, including the Martinez family and Mrs. Chen from Oak Street.</p>
							
							<p>I'm organizing a planning meeting for this Saturday at 2 PM at the library. 
								We need to discuss tool storage, a watering schedule, and rules for the garden. 
								Could you help me create a simple flyer to post around the neighborhood? 
								Your graphic design skills would be perfect for this.</p>
							
							<p>Also, my cousin Jake is a landscaper and has offered to donate his time to help with the 
								initial soil preparation. He's available the first weekend of March if that works for everyone.</p>
							
							<p>Let me know if you can make it to Saturday's meeting. I'm really excited to get 
								this project off the ground!</p>
							
							<p>Best regards,<br>Emma</p>
						</div>
					</div>

					<div class="questions-section">
						<p><strong>Choose the best option according to the information given in the message.</strong></p>

						<div class="question-container">
							<div class="question-number">1.</div>
							<div class="question-text">Emma's community garden project is now ___________</div>
							<ul class="options">
								<li><input type="radio" name="r1" id="r1a" value="A"> approved by the city</li>
								<li><input type="radio" name="r1" id="r1b" value="B"> receiving grant funding</li>
								<li><input type="radio" name="r1" id="r1c" value="C"> ready for planting</li>
								<li><input type="radio" name="r1" id="r1d" value="D"> supported by neighbors</li>
							</ul>
						</div>

						<div class="question-container">
							<div class="question-number">2.</div>
							<div class="question-text">In a few months, the community garden will ___________</div>
							<ul class="options">
								<li><input type="radio" name="r2" id="r2a" value="A"> start growing vegetables</li>
								<li><input type="radio" name="r2" id="r2b" value="B"> host neighborhood meetings</li>
								<li><input type="radio" name="r2" id="r2c" value="C"> expand to more locations</li>
								<li><input type="radio" name="r2" id="r2d" value="D"> require additional funding</li>
							</ul>
						</div>

						<div class="question-container">
							<div class="question-number">3.</div>
							<div class="question-text">Emma is feeling ___________</div>
							<ul class="options">
								<li><input type="radio" name="r3" id="r3a" value="A"> worried about the costs</li>
								<li><input type="radio" name="r3" id="r3b" value="B"> uncertain about plant choices</li>
								<li><input type="radio" name="r3" id="r3c" value="C"> enthusiastic about the project</li>
								<li><input type="radio" name="r3" id="r3d" value="D"> concerned about participation</li>
							</ul>
						</div>

						<div class="question-container">
							<div class="question-number">4.</div>
							<div class="question-text">The garden participants are ___________</div>
							<ul class="options">
								<li><input type="radio" name="r4" id="r4a" value="A"> experienced with landscaping</li>
								<li><input type="radio" name="r4" id="r4b" value="B"> paying for individual plots</li>
								<li><input type="radio" name="r4" id="r4c" value="C"> providing their own tools</li>
								<li><input type="radio" name="r4" id="r4d" value="D"> meeting every weekend</li>
							</ul>
						</div>

						<div class="question-container">
							<div class="question-number">5.</div>
							<div class="question-text">Emma and Sarah ___________</div>
							<ul class="options">
								<li><input type="radio" name="r5" id="r5a" value="A"> are neighbors</li>
								<li><input type="radio" name="r5" id="r5b" value="B"> work together</li>
								<li><input type="radio" name="r5" id="r5c" value="C"> are family members</li>
								<li><input type="radio" name="r5" id="r5d" value="D"> attend the same school</li>
							</ul>
						</div>

						<div class="question-container">
							<div class="question-number">6.</div>
							<div class="question-text">Emma and Jake ___________</div>
							<ul class="options">
								<li><input type="radio" name="r6" id="r6a" value="A"> are co-workers</li>
								<li><input type="radio" name="r6" id="r6b" value="B"> are cousins</li>
								<li><input type="radio" name="r6" id="r6c" value="C"> are neighbors</li>
								<li><input type="radio" name="r6" id="r6d" value="D"> are old friends</li>
							</ul>
						</div>
					</div>

					<div style="margin: 30px 0; padding: 20px; background-color: #f1f3f4; border-radius: 8px;">
						<p><strong>Here is a response to the message. Complete the response by filling in the blanks.</strong></p>
						
						<div style="background-color: white; padding: 20px; margin: 15px 0; border-left: 4px solid #28a745;">
							
							<p>Hi Emma,</p>
							<p>This is such wonderful news! Count us in, we would love to have 
							7. <select id="r7">
								<option value=""></option>
								<option value="A">the planning meeting</option>
								<option value="B">the grant application</option>
								<option value="C">the garden plot</option>
								<option value="D">the tool storage</option>
							</select>.

							We'll reserve 
							8. <select id="r8">
								<option value=""></option>
								<option value="A">tomatoes</option>
								<option value="B">peppers</option>
								<option value="C">two plots</option>
								<option value="D">March</option>
							</select> 

							at 6am Saturday morning. We'll be there by early afternoon. That way if you need any help setting up the 
							9. <select id="r9">
								<option value=""></option>
								<option value="A">flyer</option>
								<option value="B">meeting</option>
								<option value="C">soil</option>
								<option value="D">tools</option>
							</select> 

							you'll have some extra help. Sarah is great with decorations.</p>

							<p style="margin-bottom: 5px"></p>

							<p>Also, we want to give the garden a welcome gift. Initially we thought about a sofa for the community area, but I guess that 
							10. <select id="r10">
								<option value=""></option>
								<option value="A">isn't practical for outdoors</option>
								<option value="B">is too expensive for us</option>
								<option value="C">won't fit in the space</option>
								<option value="D">would be damaged by rain</option>
							</select>.

							Do you have any suggestions? Does the garden have everything it needs for the 
							11. <select id="r11">
								<option value=""></option>
								<option value="A">planting season</option>
								<option value="B">watering schedule</option>
								<option value="C">community meetings</option>
								<option value="D">soil preparation</option>
							</select>?

							Winter preparations, perhaps?</p>


						</div>

						

					</div>

      				<div class="hint">Click "Next Section" to continue with the Writing test.</div>
    			</div>
  			</div>

            <div class="navigation">
                <button class="btn btn-secondary" onclick="goToSection(1)">← Previous</button>
                <button class="btn btn-primary" onclick="goToSection(3)">Next Section →</button>
            </div>
        </div>

        <!-- Section 3: Writing -->
        <div id="section3" class="page">
            <div class="progress-bar">
                <div class="progress" style="width: 75%"></div>
            </div>
            <h2>Section 3: Writing</h2>
            
            <div class="wrap">
                <div class="card" role="main">
                    <h1>Writing Section</h1>
                    <div class="subtitle">Writing Task 1 — You should spend about 20 minutes on this task.</div>
                    
                    <div class="writing-task">
                        <div class="task-prompt">
                            <h3>Task:</h3>
                            <p>You recently bought a piece of equipment for your kitchen but it did not work. You phoned the shop but no action was taken.</p>
                            <p><strong>Write a letter to the shop manager. In your letter:</strong></p>
                            <ul>
                                <li>describe the problem with the equipment</li>
                                <li>explain what happened when you phoned the shop</li>
                                <li>say what you would like the manager to do</li>
                            </ul>
                            <p><strong>Write at least 150 words.</strong></p>
                            <p>You do NOT need to write any addresses.</p>
                            <p>Begin your letter as follows:</p>
                            <p><em>Dear Sir or Madam,</em></p>
                        </div>
                        
                        <div class="writing-area">
                            <div class="writing-box">
                                <textarea 
                                    id="writingResponse" 
                                    class="writing-textarea" 
                                    placeholder="Begin your letter here...&#10;&#10;Dear Sir or Madam,&#10;&#10;"
                                    rows="20"
                                    maxlength="2000"
                                ></textarea>
                                <div class="word-count">
                                    Word count: <span id="wordCount">0</span> / 150 minimum
                                </div>
                            </div>
                        </div>
                        
                        <div class="writing-tips">
                            <h4>Tips:</h4>
                            <ul>
                                <li>Use formal language appropriate for a complaint letter</li>
                                <li>Organize your letter with clear paragraphs</li>
                                <li>Include all three bullet points in your response</li>
                                <li>Aim for 150-200 words</li>
                                <li>End with an appropriate closing (e.g., "Yours faithfully,")</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="navigation">
                <button class="btn btn-secondary" onclick="goToSection(2)">← Previous</button>
                <button class="btn btn-primary" onclick="goToSection(4)">Next Section →</button>
            </div>
        </div>

		<!-- Section 4: Speaking -->
		<div id="section4" class="page">
			<div class="progress-bar">
				<div class="progress" style="width: 100%"></div>
			</div>
			<h2>Section 4: Speaking</h2>
			
			<div class="wrap">
				<div class="card" role="main">
					<h1>Speaking Section</h1>
					<div class="subtitle">The Speaking test will be administered to you by the examiner.</div>
					
					<div class="speaking-info">
						<div class="speaking-overview">
							<h3>CELPIP Speaking Test Overview</h3>
							<p>The CELPIP Speaking test is a computer-delivered test. It takes approximately 15-20 minutes and consists of eight tasks:</p>
						</div>
						
						<div class="speaking-parts">
							<div class="speaking-part">
								<h4>Task 1: Giving Advice (1 minute preparation, 1 minute speaking)</h4>
								<ul>
									<li>Give advice to someone in a specific situation</li>
									<li>Example: "Your friend wants to learn a new language. What advice would you give?"</li>
								</ul>
								<!-- Task 1 Recorder -->
								<div id="audioRecorder1" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording1" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording1" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording1" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus1">Ready to record</span>
										<span id="recordingTimer1">00:00</span>
									</div>
									<audio id="recordingPlayback1" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>
							
							<div class="speaking-part">
								<h4>Task 2: Talking about a Personal Experience (1 minute prep, 2 minutes speaking)</h4>
								<ul>
									<li>Share a personal story or experience</li>
									<li>Include details about what happened and how you felt</li>
								</ul>
								<!-- Task 2 Recorder -->
								<div id="audioRecorder2" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording2" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording2" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording2" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus2">Ready to record</span>
										<span id="recordingTimer2">00:00</span>
									</div>
									<audio id="recordingPlayback2" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>

							<div class="speaking-part">
								<h4>Task 3: Describing a Scene (1 minute prep, 1 minute speaking)</h4>
								<ul>
									<li>Describe what you see in a picture</li>
									<li>Focus on details, people, objects, and activities</li>
								</ul>
								<!-- Task 3 Recorder -->
								<div id="audioRecorder3" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording3" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording3" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording3" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus3">Ready to record</span>
										<span id="recordingTimer3">00:00</span>
									</div>
									<audio id="recordingPlayback3" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>

							<div class="speaking-part">
								<h4>Task 4: Making Predictions (1 minute prep, 1 minute speaking)</h4>
								<ul>
									<li>Look at a picture and predict what might happen next</li>
									<li>Use your imagination to create a logical scenario</li>
								</ul>
								<!-- Task 4 Recorder -->
								<div id="audioRecorder4" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording4" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording4" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording4" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus4">Ready to record</span>
										<span id="recordingTimer4">00:00</span>
									</div>
									<audio id="recordingPlayback4" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>

							<div class="speaking-part">
								<h4>Task 5: Comparing and Persuading (1 minute prep, 1 minute speaking)</h4>
								<ul>
									<li>Choose between two options and explain your choice</li>
									<li>Persuade someone to agree with your decision</li>
								</ul>
								<!-- Task 5 Recorder -->
								<div id="audioRecorder5" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording5" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording5" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording5" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus5">Ready to record</span>
										<span id="recordingTimer5">00:00</span>
									</div>
									<audio id="recordingPlayback5" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>

							<div class="speaking-part">
								<h4>Task 6: Dealing with a Difficult Situation (1 minute prep, 1 minute speaking)</h4>
								<ul>
									<li>Respond to a challenging or unexpected situation</li>
									<li>Show how you would handle the problem</li>
								</ul>
								<!-- Task 6 Recorder -->
								<div id="audioRecorder6" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording6" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording6" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording6" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus6">Ready to record</span>
										<span id="recordingTimer6">00:00</span>
									</div>
									<audio id="recordingPlayback6" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>

							<div class="speaking-part">
								<h4>Task 7: Expressing Opinions (1 minute prep, 1 minute speaking)</h4>
								<ul>
									<li>Share your opinion on a topic of general interest</li>
									<li>Support your viewpoint with reasons and examples</li>
								</ul>
								<!-- Task 7 Recorder -->
								<div id="audioRecorder7" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording7" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording7" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording7" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus7">Ready to record</span>
										<span id="recordingTimer7">00:00</span>
									</div>
									<audio id="recordingPlayback7" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>

							<div class="speaking-part">
								<h4>Task 8: Describing an Unusual Situation (1 minute prep, 1 minute speaking)</h4>
								<ul>
									<li>Describe what you see in an unusual or strange picture</li>
									<li>Explain what might be happening and why</li>
								</ul>
								<!-- Task 8 Recorder -->
								<div id="audioRecorder8" class="audio-recorder" style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f8f9fa;">
									<div class="recording-controls">
										<button id="startRecording8" class="btn btn-primary">🎤 Start Recording</button>
										<button id="stopRecording8" class="btn btn-secondary" disabled>⏹ Stop Recording</button>
										<button id="playRecording8" class="btn btn-secondary" disabled>▶ Play Recording</button>
									</div>
									<div class="recording-status">
										<span id="recordingStatus8">Ready to record</span>
										<span id="recordingTimer8">00:00</span>
									</div>
									<audio id="recordingPlayback8" controls style="display: none; width: 100%; margin-top: 10px;"></audio>
								</div>
							</div>
						</div>

						<!-- Rest of your existing speaking section content -->
						<div class="speaking-assessment">
							<h3>Assessment Criteria</h3>
							<p>Your speaking will be assessed on four criteria:</p>
							<div class="criteria-grid">
								<div class="criterion">
									<h5>Content/Coherence</h5>
									<p>How well you address the topic and organize ideas</p>
								</div>
								<div class="criterion">
									<h5>Vocabulary</h5>
									<p>Range and accuracy of words and expressions</p>
								</div>
								<div class="criterion">
									<h5>Listenability</h5>
									<p>How easy you are to understand</p>
								</div>
								<div class="criterion">
									<h5>Task Fulfillment</h5>
									<p>How completely you address the task requirements</p>
								</div>
							</div>
						</div>
						
						<div class="speaking-tips">
							<h3>Speaking Test Tips</h3>
							<ul>
								<li>Use your preparation time effectively to organize your thoughts</li>
								<li>Speak clearly and at a natural pace</li>
								<li>Use the full time given for each response</li>
								<li>Include specific details and examples in your answers</li>
								<li>Practice with a variety of topics and situations</li>
								<li>Stay calm and confident during the test</li>
							</ul>
						</div>
						
						<div class="examiner-note">
							<h3>Note for Test Day</h3>
							<p><strong>The CELPIP Speaking test is completed on a computer with a headset and microphone.</strong> You will record your responses which will be evaluated by certified raters.</p>
							<p>Make sure to test your equipment before beginning and speak clearly into the microphone.</p>
						</div>
					</div>
				</div>
			</div>

			<div class="navigation">
				<button class="btn btn-secondary" onclick="goToSection(3)">← Previous</button>
				<button class="btn btn-primary" onclick="finishQuiz()">Finish Test & See Results →</button>
			</div>
		</div>

        <!-- Results Page -->
		<div id="results" class="page">
			<div class="download-buttons">
				<button class="btn btn-download" id="downloadSummaryPDF">📄 Download Summary PDF</button>
				<button class="btn btn-download" id="downloadFullPDF">📑 Download Full Results PDF</button>
			</div>
			
			<div class="results-section" id="quiz-results">
				<div class="wrap">
					<div class="card">
						<h1>CELPIP Mock Test Results</h1>
						<div id="summary" class="scoreRow" aria-live="polite">
							<!-- populated by script -->
						</div>
						<div class="total" id="totalArea"></div>
						<div class="details" id="detailsArea">
							<!-- detailed per-question breakdowns -->
						</div>
						<div class="controls">
							<button class="btn btn-restart" id="restartBtn">Take Test Again</button>
						</div>
						<div class="ielts-info">
							<p><strong>Note:</strong> This is a practice test. Your actual CELPIP score may vary.</p>
							<p>For official CELPIP testing, visit <a href="https://celpip.ca" target="_blank">celpip.ca</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>

	<script src="scripts/script.js" defer></script>
</body>
</html>