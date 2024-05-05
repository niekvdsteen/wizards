<?php

$video_id = $_POST["id"];

if(!preg_match('/^[a-zA-Z0-9]+[a-zA-Z0-9._]+$/', $video_id))
die();

shell_exec("/usr/bin/ffmpeg -y -i uploads/$video_id.mp4 -c copy -map 0 -movflags +faststart uploads/$video_id-0.mp4 > /dev/null 2> ffmpeg.log");

// Load video page
$videoPage = file_get_contents("clips/$video_id.html");

// Prepare HTML string for insertion & Insert video
$insertFile = "<video id='uploaded-video' style='width: 100%; height: 100%;' poster='../uploads/$video_id.jpeg' playsinline='true' controls='true' preload='auto'><source src='../uploads/$video_id-0.mp4' type='video/mp4'>Your browser does not support the video tag</video>";
$videoPage = str_replace("{%insertFile%}", $insertFile, $videoPage);

// Insert video file link for meta tags
$videoPage = str_replace("{%videoFileLink%}", "http://dm-wizards.com/uploads/$video_id-0.mp4", $videoPage);
// Save page
file_put_contents("clips/$video_id.html", $videoPage);

unlink("uploads/$video_id.mp4");

echo json_encode(['done' => 'done']);

exit;
