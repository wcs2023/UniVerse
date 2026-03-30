<?php

/**
 * Routes for mentorship feature
 */

// Mentorship Management Dashboard
$router->get('/mentorships', 'Mentorships@index');

// Request Mentorship
$router->get('/mentorships/request', 'Mentorships@request');
$router->get('/mentorships/request/{id}', 'Mentorships@request');
$router->post('/mentorships/request', 'Mentorships@request');

// Respond to Mentorship Request
$router->post('/mentorships/respond', 'Mentorships@respond');

// View Time Slots
$router->get('/mentorships/getTimeSlots/{id}', 'Mentorships@getTimeSlots');

// Schedule Session
$router->post('/mentorships/schedule', 'Mentorships@schedule');

// Provide Feedback
$router->get('/mentorships/feedback/{id}', 'Mentorships@feedback');
$router->post('/mentorships/feedback/{id}', 'Mentorships@feedback');

// View Alumni Directory
$router->get('/alumniDirectory', 'Alumni@directory');
