<?php

return [
    'categories' => [
        'trash_concern' => 'Trash / Waste Concern',
        'report_follow_up' => 'Waste Report Follow-up',
        'inquiry' => 'General Inquiry',
        'website_suggestion' => 'Website Suggestion',
        'account_assistance' => 'Account Assistance',
        'volunteer_cleanup' => 'Volunteer / Cleanup Drive',
        'other' => 'Other Concern',
    ],

    // Starter list. Add or remove terms here as the community's moderation rules evolve.
    // Matching is case-insensitive and checks whole words/phrases to reduce false positives.
    'banned_words' => [
        'fuck', 'fucking', 'fucker', 'motherfucker', 'shit', 'shitty', 'bullshit',
        'bitch', 'bastard', 'asshole', 'dickhead', 'dick', 'piss off',
        'putangina', 'putang ina', 'tangina', 'tang ina', 'puta', 'gago', 'gaga',
        'bobo', 'tanga', 'ulol', 'leche', 'bwisit', 'yawa', 'piste',
    ],

    // A ticket remains active until staff resolve or close it.
    'active_statuses' => ['open', 'in_progress', 'waiting_for_user'],
    'response_wait_days' => 2,
    'max_attachment_kb' => 5120,
    'max_title_length' => 120,
    'max_description_length' => 3000,
    'max_reply_length' => 3000,
];
