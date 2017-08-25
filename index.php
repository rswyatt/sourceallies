<?php
/**
 * @file Coding for Source Allies Moby Dick challenge.
 */

class MobyDickChallenge {
  public $stop_words_file = 'stop-words.txt';
  public $story_file = 'mobydick.txt';
  public $stop_words = array();
  public $story_text = FALSE;

  /**
   * This function gets the stop words.
   */
  function get_stop_words() {
    $stop_words = array();
    if ($file = fopen($this->stop_words_file, 'r')) {
      $text = fread($file, filesize($this->stop_words_file));
      fclose($file);

      // We know this stop words file has # # # so we will explode there to
      // get the array.
      $text_parts = explode('# # #', $text);
      $this->stop_words = explode(' ', $text_parts[1]);      
    }
    else {
      die("Unable to open file.");
    }
  }

  /**
   * This function retrieves the story text.
   */
  function get_story_text() {
    $story_text = FALSE;
    if ($file = fopen($this->story_file, 'r')) {
      $this->story_text = fread($file, filesize($this->story_file));
      fclose($file);
    }
    else {
      die("Unable to open file.");
    }
  }
}
