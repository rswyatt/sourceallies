<?php
/**
 * @file Coding for Source Allies Moby Dick challenge.
 */

class MobyDickChallenge {
  public $stop_words_file = 'stop-words.txt';
  public $story_file = 'mobydick.txt';
  public $stop_words = array();
  public $story_text = FALSE;

  function __construct() {
    $this->get_stop_words();
    $this->get_story_text();
  }

  /**
   * This function gets the stop words.
   */
  function get_stop_words() {
    $stop_words = array();
    if ($file = new SplFileObject($this->stop_words_file)) {
      // There are 11 unnecessary lines in the file. Skip those.
      $ct = 11;
      $i = 0;
      while (!$file->eof()) {
        $matches = FALSE;
        $txt = $file->fgets();
        
        if ($i > $ct && $i%2 == 0) {
          $this->stop_words[] = trim($txt);
        }
        $i++;
      }
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

  /**
   * This function calculates the 100 most frequently occuring words.
   *
   * A list of stop words has been provided so the list will exclude any words
   * appearing in that list.
   */
  function calculate_frequency() {
    if (!empty($this->story_text)) {
      $text = preg_replace('/ss+/i', '', $this->story_text);
      $text = trim($text);

      // Only handle characters that are alphabetical.
      // Also make all the text lowercase.
      $text = strtolower(preg_replace('/[^a-zA-Z -]/', '', $text));

      // Turn this text into an array.
      preg_match_all('/\b.*?\b/i', $text, $words);
      $word_list = $words[0];

      // Get rid of empty array values and stop word values.
      foreach ($word_list as $key => $word) {
        if ($word === '' || in_array(trim($word), $this->stop_words)) {
          unset($word_list[$key]);
        }
      }

      // Unfortunately there are still some blank array elements.
      $word_list = array_filter($word_list);

      // Get the count, count the frequency and sort descending.
      $count = str_word_count(implode(" ", $word_list), 1);
      $frequencies = array_count_values($count);
      arsort($frequencies);

      // Now we only want to return the first 100.
      return array_slice($frequencies, 0, 100);
    }
    else {
      print "We have no story to calculate.";
    }
  }
}

$mdc = new MobyDickChallenge();
$frequency_list = $mdc->calculate_frequency();
echo "<pre>";
print_r($frequency_list);
echo "</pre>";
