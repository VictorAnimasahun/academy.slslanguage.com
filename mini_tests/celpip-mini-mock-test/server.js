const express = require('express');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
const { randomUUID } = require('crypto');

const app = express();
app.use(express.json());
app.use(express.static('public'));

// ensure upload dir exists
const UPLOAD_DIR = path.join(__dirname, 'uploads', 'audio');
fs.mkdirSync(UPLOAD_DIR, { recursive: true });

// accept only a few audio mimetypes, max 10MB
const fileFilter = (req, file, cb) => {
  const allowed = ['audio/wav', 'audio/x-wav', 'audio/webm', 'audio/mpeg', 'audio/mp3'];
  if (allowed.includes(file.mimetype)) cb(null, true);
  else cb(new Error('Only audio files are allowed'));
};

const storage = multer.diskStorage({
  destination: UPLOAD_DIR,
  filename: (req, file, cb) => {
    // sanitize testId
    const testId = (req.body.testId || 'unknown').toString().replace(/[^A-Za-z0-9_-]/g, '');
    const filename = `${testId || 't'}_${randomUUID()}` + path.extname(file.originalname || '.wav');
    cb(null, filename);
  }
});

const upload = multer({
  storage,
  fileFilter,
  limits: { fileSize: 10 * 1024 * 1024 } // 10 MB
});

// handle writing endpoint (validate inputs)
app.post('/api/submit-writing', (req, res) => {
  const { content, testId, timestamp, wordCount } = req.body;
  if (!testId || !content) return res.status(400).json({ success: false, error: 'Missing fields' });
  // TODO: save to DB, sanitize content when rendering later
  console.log('Writing:', { testId, wordCount, ts: timestamp });
  res.json({ success: true });
});

// audio upload route with explicit multer error handling
app.post('/api/submit-audio', (req, res) => {
  upload.single('audio')(req, res, function (err) {
    if (err instanceof multer.MulterError) {
      return res.status(400).json({ success: false, error: err.code });
    } else if (err) {
      return res.status(400).json({ success: false, error: err.message });
    }
    if (!req.file) return res.status(400).json({ success: false, error: 'No file uploaded' });

    const audioData = {
      testId: req.body.testId,
      filename: req.file.filename,
      path: req.file.path,
      size: req.file.size
    };
    // TODO: save metadata to DB, move file to more permanent storage, transcode if necessary
    console.log('Audio uploaded:', audioData);
    res.json({ success: true, audio: audioData });
  });
});

app.listen(process.env.PORT || 3000, () => console.log('Server running'));
