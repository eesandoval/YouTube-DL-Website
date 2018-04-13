<?php
function cleanup_files() {
	// Before every download clean up any previous files (mp3's, mp4's, and zips)
	$files = glob("./*.{mp4,mp3,zip}", GLOB_BRACE);
	foreach ($files as $file) {
		unlink($file);
	}
}
cleanup_files();
set_time_limit(300); // youtube-dl may take awhile so give it some time (5 minutes)
// Using REST get the url sent and the action sent, also declare any other variables we'll need
$url = $_GET["url"];
$action = $_GET["action"];
$zip = new ZipArchive();

// <TODO:>Attempt at making a uniqueid incase multiple users are downloading</TODO:>
$filename = "./download.zip";
$uniqueid = "";//uniqid(); 
//mkdir($uniqueid, 0777);

// Call youtube-dl depending on the request made
	// NOTE: This requires installation of youtube-dl on the server and ffmpeg and ffprobe (Libav for Linux systems)
// Get all the files downloaded in $files
if ($action == "get_mp3") {
	shell_exec("youtube-dl -x --audio-format mp3 " . escapeshellarg($url));
	$files = glob("./*.mp3");
} else if ($action == "get_mp4") {
	shell_exec("youtube-dl -f mp4 " . escapeshellarg($url));
	$files = glob("./*.mp4");
} else { // By default assume they meant get_both
	shell_exec("youtube-dl -f mp4 " . $url);
	shell_exec("youtube-dl -x --audio-format mp3 " . escapeshellarg($url));
	$files = glob("./*.{mp4,mp3}", GLOB_BRACE);
}

// Attempt to create a zip archive
if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
	exit("Cannot open <$filename>\n");
}

// Add all the files we just downloaded into this zip archive
foreach ($files as $file) {
	$zip->addFile($file, basename($file));
}

// Close our zip archive and return it back to the JavaScript call (or output to HTTP if this wasn't invoked from JavaScript)
$zip->close();
echo "./script/" . $filename 
?>