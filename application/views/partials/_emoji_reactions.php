<?php defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('partials/_emoji_reaction_item', ['reactions' => $reactions, 'reaction_vote' => $reactions->re_like, 'reaction' => 'like']);
$this->load->view('partials/_emoji_reaction_item', ['reactions' => $reactions, 'reaction_vote' => $reactions->re_dislike, 'reaction' => 'dislike']);
$this->load->view('partials/_emoji_reaction_item', ['reactions' => $reactions, 'reaction_vote' => $reactions->re_love, 'reaction' => 'love']);
$this->load->view('partials/_emoji_reaction_item', ['reactions' => $reactions, 'reaction_vote' => $reactions->re_funny, 'reaction' => 'funny']);
$this->load->view('partials/_emoji_reaction_item', ['reactions' => $reactions, 'reaction_vote' => $reactions->re_angry, 'reaction' => 'angry']);
$this->load->view('partials/_emoji_reaction_item', ['reactions' => $reactions, 'reaction_vote' => $reactions->re_sad, 'reaction' => 'sad']);
$this->load->view('partials/_emoji_reaction_item', ['reactions' => $reactions, 'reaction_vote' => $reactions->re_wow, 'reaction' => 'wow']);
?>